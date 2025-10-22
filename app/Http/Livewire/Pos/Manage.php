<?php

namespace App\Http\Livewire\Pos;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

use App\models\pos\pos as Pos;
use App\models\pos\posLine as PosLine;
use App\models\product\product as Product;
use App\models\unit\unit as Unit;
use App\models\product\category as Category;
use App\models\inventory\warehouse as Warehouse;
use App\models\customer\customer as Customer;

class Manage extends Component
{
    public $pos_id = null;

    public $pos_date;
    public $warehouse_id;
    public $customer_id;
    public $notes_ar = '';
    public $notes_en = '';

    public $activeCategoryId = null;
    public $filterProductText = '';

    /** @var array<int,array> */
    public $rows = [];

    public $subtotal = 0.0;
    public $discount = 0.0;
    public $tax      = 0.0;
    public $grand    = 0.0;

    protected $listeners = [
        'deleteConfirmed' => 'removeRow',
        'openHistory'     => 'noop',
    ];

    public function mount($id = null): void
    {
        $this->pos_date = now()->toDateString();

        if ($id) {
            $this->pos_id = (int) $id;
            $pos = Pos::with('lines.product')->findOrFail($id);

            $t = (array) ($pos->getTranslations('notes') ?? []);
            $this->notes_ar = $t['ar'] ?? '';
            $this->notes_en = $t['en'] ?? '';

            $this->pos_date     = $pos->pos_date;
            $this->warehouse_id = $pos->warehouse_id;
            $this->customer_id  = $pos->customer_id;

            $this->rows = $pos->lines->map(function ($l) {
                $p = $l->product;
                $uomText = $l->uom_text ?: ($p?->unit?->name ?? null);
                return [
                    'category_id'  => $p?->category_id,
                    'product_id'   => $l->product_id,
                    'unit_id'      => $l->unit_id,
                    'qty'          => (float) $l->qty,
                    'unit_price'   => (float) $l->unit_price,
                    'uom_text'     => $this->safeTransName($uomText),
                    'has_expiry'   => !empty($l->expiry_date),
                    'expiry_date'  => $l->expiry_date,
                    'onhand'       => 0,
                    'preview'      => [
                        'name'        => $this->safeTransName($p?->name),
                        'sku'         => $p?->sku,
                        'barcode'     => $p?->barcode,
                        'description' => $this->safeTransName($p?->description),
                        'uom'         => $this->safeTransName($uomText),
                        'price'       => (float) $l->unit_price,
                    ],
                    // ↓↓↓ كانت سبب الخطأ — الدالة أضفناها أسفل
                    'unit_options' => $this->unitOptionsForProduct($l->product_id),
                ];
            })->toArray();
        } else {
            $this->rows = [$this->blankRow()];
        }

        $this->recalcTotals();
    }

    /* ======================== Helpers ======================== */

    private function isJsonString($v): bool
    {
        if (!is_string($v)) return false;
        $t = ltrim($v);
        return str_starts_with($t, '{') || str_starts_with($t, '[');
    }

    private function ensureArray($value): array
    {
        if (is_array($value)) return $value;
        if ($this->isJsonString($value)) {
            $d = json_decode($value, true);
            return is_array($d) ? $d : [];
        }
        return [];
    }

    private function safeTransName($value)
    {
        if (is_array($value)) {
            return $value[app()->getLocale()] ?? ($value['ar'] ?? reset($value) ?? '');
        }
        if ($this->isJsonString($value)) {
            $a = json_decode($value, true) ?: [];
            return $a[app()->getLocale()] ?? ($a['ar'] ?? ($a['en'] ?? $value));
        }
        return $value ?: null;
    }

    private function getUnitName(?int $unitId): ?string
    {
        if (!$unitId) return null;
        $u = Unit::find($unitId);
        return $u ? $this->safeTransName($u->name) : null;
    }

    /**
     * إرجاع لائحة الوحدات + خريطة الأسعار من units_matrix (array أو JSON)
     * @return array{0: array<int,string>, 1: array<int,float>}
     */
    private function parseUnitMatrix($matrix): array
    {
        $unitOptions = [];
        $priceMap    = [];

        $data = $this->ensureArray($matrix);
        if (empty($data)) return [$unitOptions, $priceMap];

        // مصفوفة؟
        if (array_keys($data) === range(0, count($data) - 1)) {
            foreach ($data as $row) {
                if (!is_array($row)) continue;
                $uid   = (int) ($row['unit_id'] ?? $row['id'] ?? 0);
                if (!$uid) continue;
                $uname = $row['name'] ?? $this->getUnitName($uid);
                $price = $row['price'] ?? $row['sale_price'] ?? $row['selling_price'] ?? 0;
                $unitOptions[$uid] = $this->safeTransName($uname);
                $priceMap[$uid]    = (float) $price;
            }
        } else {
            // كائن {}
            foreach ($data as $uid => $row) {
                $uid   = (int) $uid;
                $uname = is_array($row) ? ($row['name'] ?? null) : null;
                $price = is_array($row) ? ($row['price'] ?? $row['sale_price'] ?? $row['selling_price'] ?? 0) : 0;
                $unitOptions[$uid] = $this->safeTransName($uname) ?: $this->getUnitName($uid);
                $priceMap[$uid]    = (float) $price;
            }
        }

        return [$unitOptions, $priceMap];
    }

    private function priceForProductUnit(?int $productId, ?int $unitId): float
    {
        if (!$productId || !$unitId) return 0.0;
        $p = Product::find($productId);
        if (!$p) return 0.0;
        [, $priceMap] = $this->parseUnitMatrix($p->units_matrix);
        return (float)($priceMap[$unitId] ?? 0.0);
    }

    private function minPriceFromMatrix($matrix): float
    {
        [, $priceMap] = $this->parseUnitMatrix($matrix);
        return $priceMap ? (float) min($priceMap) : 0.0;
    }

    /** ✅ الدالة الناقصة التي تسببت بالخطأ */
    private function unitOptionsForProduct(?int $productId): array
    {
        if (!$productId) return [];
        $p = Product::find($productId);
        if (!$p) return [];
        [$unitOptions, ] = $this->parseUnitMatrix($p->units_matrix);

        // تأكد أن الوحدة الأساسية للمنتج موجودة ضمن الخيارات
        if ($p->unit_id && !array_key_exists((int)$p->unit_id, $unitOptions)) {
            $unitOptions[(int)$p->unit_id] = $this->getUnitName((int)$p->unit_id);
        }

        return $unitOptions;
    }

    public function blankRow(): array
    {
        return [
            'category_id'  => null,
            'product_id'   => null,
            'unit_id'      => null,
            'qty'          => 1,
            'unit_price'   => 0,
            'uom_text'     => null,
            'has_expiry'   => false,
            'expiry_date'  => null,
            'onhand'       => 0,
            'preview'      => null,
            'unit_options' => [],
        ];
    }

    /* ======================== Actions ======================== */

    public function selectCategory($categoryId): void
    {
        $this->activeCategoryId = (int) $categoryId;
    }

    public function addProductToCart(int $productId): void
    {
        $p = Product::find($productId);
        if (!$p) return;

        [$unitOptions, ] = $this->parseUnitMatrix($p->units_matrix);
        $chosenUnit = $p->unit_id ?: (int) array_key_first($unitOptions);
        if ($chosenUnit && !array_key_exists((int)$chosenUnit, $unitOptions)) {
            $unitOptions[(int)$chosenUnit] = $this->getUnitName((int)$chosenUnit);
        }
        $price = $this->priceForProductUnit($p->id, $chosenUnit);

        foreach ($this->rows as $idx => $row) {
            if ((int)($row['product_id'] ?? 0) === (int)$p->id && (int)($row['unit_id'] ?? 0) === (int)$chosenUnit) {
                $this->rows[$idx]['qty'] = (float)($this->rows[$idx]['qty'] ?? 0) + 1;
                $this->recalcTotals();
                return;
            }
        }

        $row = $this->blankRow();
        $row['category_id']  = $p->category_id;
        $row['product_id']   = $p->id;
        $row['unit_id']      = $chosenUnit;
        $row['unit_price']   = (float) $price;
        $row['uom_text']     = $unitOptions[$chosenUnit] ?? $this->getUnitName($chosenUnit);
        $row['unit_options'] = $unitOptions;
        $row['preview'] = [
            'name'        => $this->safeTransName($p->name),
            'sku'         => $p->sku,
            'barcode'     => $p->barcode,
            'description' => $this->safeTransName($p->description),
            'uom'         => $row['uom_text'],
            'price'       => (float) $row['unit_price'],
        ];
        $this->rows[] = $row;

        $this->recalcTotals();
    }

    public function incQty(int $i): void
    {
        $this->rows[$i]['qty'] = max(0.0001, (float)($this->rows[$i]['qty'] ?? 0) + 1);
        $this->recalcTotals();
    }

    public function decQty(int $i): void
    {
        $q = (float)($this->rows[$i]['qty'] ?? 0);
        $q = $q - 1;
        $this->rows[$i]['qty'] = $q > 0 ? $q : 1;
        $this->recalcTotals();
    }

    public function clearCart(): void
    {
        $this->rows = [$this->blankRow()];
        $this->recalcTotals();
    }

    public function addRow(): void
    {
        $this->rows[] = $this->blankRow();
    }

    public function removeRow($idx): void
    {
        array_splice($this->rows, (int) $idx, 1);
        if (!$this->rows) {
            $this->rows[] = $this->blankRow();
        }
        $this->recalcTotals();
    }

    public function updated($field): void
    {
        if (str_starts_with($field, 'rows.') || in_array($field, ['discount','tax'], true)) {
            $this->recalcTotals();
        }
    }

    public function rowCategoryChanged(int $i): void
    {
        $this->rows[$i]['product_id']   = null;
        $this->rows[$i]['unit_id']      = null;
        $this->rows[$i]['unit_price']   = 0;
        $this->rows[$i]['uom_text']     = null;
        $this->rows[$i]['preview']      = null;
        $this->rows[$i]['unit_options'] = [];
        $this->recalcTotals();
    }

    public function rowProductChanged(int $i): void
    {
        $pid = $this->rows[$i]['product_id'] ?? null;
        if (!$pid) {
            $this->rowCategoryChanged($i);
            return;
        }

        $p = Product::find($pid);
        if (!$p) return;

        [$unitOptions, ] = $this->parseUnitMatrix($p->units_matrix);

        $chosenUnit = $this->rows[$i]['unit_id'] ?: ($p->unit_id ?? null);
        if ($chosenUnit && !array_key_exists((int)$chosenUnit, $unitOptions)) {
            $unitOptions[(int)$chosenUnit] = $this->getUnitName((int)$chosenUnit);
        }
        if (!$chosenUnit && !empty($unitOptions)) {
            $chosenUnit = (int) array_key_first($unitOptions);
        }

        $price = $this->priceForProductUnit($pid, $chosenUnit);

        $this->rows[$i]['unit_id']      = $chosenUnit;
        $this->rows[$i]['unit_price']   = (float) $price;
        $this->rows[$i]['uom_text']     = $unitOptions[$chosenUnit] ?? $this->getUnitName($chosenUnit);
        $this->rows[$i]['unit_options'] = $unitOptions;

        $this->rows[$i]['preview'] = [
            'name'        => $this->safeTransName($p->name),
            'sku'         => $p->sku,
            'barcode'     => $p->barcode,
            'description' => $this->safeTransName($p->description),
            'uom'         => $this->rows[$i]['uom_text'],
            'price'       => (float) $this->rows[$i]['unit_price'],
        ];

        $this->recalcTotals();
    }

    public function rowUnitChanged(int $i): void
    {
        $pid = $this->rows[$i]['product_id'] ?? null;
        $uid = $this->rows[$i]['unit_id'] ?? null;
        if (!$pid || !$uid) return;

        $price = $this->priceForProductUnit($pid, $uid);
        $this->rows[$i]['unit_price'] = (float) $price;
        $this->rows[$i]['uom_text']   = $this->getUnitName((int) $uid);

        if (!empty($this->rows[$i]['preview'])) {
            $this->rows[$i]['preview']['uom']   = $this->rows[$i]['uom_text'];
            $this->rows[$i]['preview']['price'] = (float) $price;
        }

        $this->recalcTotals();
    }

    /* ======================== Validation ======================== */

    protected function rules(): array
    {
        return [
            'pos_date'      => 'required|date',
            'warehouse_id'  => 'required|exists:warehouses,id',
            'customer_id'   => 'nullable|exists:customers,id',

            'discount'      => 'nullable|numeric|min:0',
            'tax'           => 'nullable|numeric|min:0',

            'rows'                 => 'required|array|min:1',
            'rows.*.category_id'   => 'nullable|integer',
            'rows.*.product_id'    => 'required|exists:products,id',
            'rows.*.unit_id'       => 'nullable|exists:unit,id',
            'rows.*.qty'           => 'required|numeric|min:0.0001',
            'rows.*.unit_price'    => 'nullable|numeric|min:0',
            'rows.*.expiry_date'   => 'nullable|date',
        ];
    }

    protected $messages = [
        'pos_date.required'           => 'تاريخ البيع مطلوب',
        'warehouse_id.required'       => 'المخزن مطلوب',
        'rows.required'               => 'تفاصيل الفاتورة مطلوبة',
        'rows.*.product_id.required'  => 'يرجى اختيار الصنف',
        'rows.*.qty.min'              => 'الكمية لابد أن تكون أكبر من صفر',
        'rows.*.unit_price.min'       => 'السعر لا يمكن أن يكون سالب',
    ];

    /* ======================== Totals & Save ======================== */

    private function recalcTotals(): void
    {
        $subtotal = 0.0;
        foreach ($this->rows as $r) {
            $q  = (float) ($r['qty'] ?? 0);
            $up = (float) ($r['unit_price'] ?? 0);
            $subtotal += $q * $up;
        }
        $this->subtotal = round($subtotal, 4);
        $this->grand    = round($this->subtotal - (float) $this->discount + (float) $this->tax, 4);
    }

    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            $this->recalcTotals();

            if ($this->pos_id) {
                $pos = Pos::lockForUpdate()->findOrFail($this->pos_id);
            } else {
                $pos = new Pos();
                $next = (int)((Pos::max('id') ?? 0) + 1);
                $pos->pos_no = 'SO-' . now()->format('Ymd') . '-' . str_pad((string)$next, 4, '0', STR_PAD_LEFT);
                $pos->status = 'draft';
            }

            $pos->pos_date     = $this->pos_date;
            $pos->warehouse_id = $this->warehouse_id;
            $pos->customer_id  = $this->customer_id;
            $pos->notes        = ['ar' => $this->notes_ar, 'en' => $this->notes_en];
            $pos->subtotal     = $this->subtotal;
            $pos->discount     = $this->discount;
            $pos->tax          = $this->tax;
            $pos->grand_total  = $this->grand;
            $pos->user_id      = auth()->id();

            $pos->save();

            $pos->lines()->delete();

            foreach ($this->rows as $r) {
                if (empty($r['has_expiry'])) $r['expiry_date'] = null;

                $line = new PosLine([
                    'product_id'   => $r['product_id'],
                    'unit_id'      => $r['unit_id'],
                    'warehouse_id' => $this->warehouse_id,
                    'qty'          => (float) $r['qty'],
                    'unit_price'   => (float) $r['unit_price'],
                    'uom_text'     => $r['uom_text'],
                    'expiry_date'  => $r['expiry_date'],
                    'line_total'   => round((float)$r['qty'] * (float)$r['unit_price'], 4),
                    'notes'        => null,
                    'code'         => null,
                ]);
                $pos->lines()->save($line);
            }

            $this->pos_id = $pos->id;
        });

        session()->flash('success', __('pos.saved_ok'));
        return redirect()->route('pos.index');
    }

    public function noop() {}

    public function render()
    {
        $warehouses = Warehouse::orderBy('name')->get(['id','name']);
        $customers  = Customer::orderBy('name')->get(['id','name']);
        $categories = Category::orderBy('name')->get(['id','name']);

        $products = Product::orderBy('name')
            ->get(['id','name','category_id','unit_id','sku','barcode','description','units_matrix']);

        $catalogProducts = $products->when($this->activeCategoryId, function ($q) {
            return $q->where('category_id', (int)$this->activeCategoryId);
        })->map(function ($p) {
            $p->min_price = $this->minPriceFromMatrix($p->units_matrix);
            return $p;
        });

        return view('livewire.pos.manage', [
            'warehouses'      => $warehouses,
            'customers'       => $customers,
            'categories'      => $categories,
            'products'        => $products,
            'catalogProducts' => $catalogProducts,
        ]);
    }
}
