<?php

namespace App\Http\Livewire\Pos;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

use App\models\pos\pos as Pos;
use App\models\pos\posLine as PosLine;
use App\models\product\product as Product;
use App\models\unit\unit as Unit;
use App\models\inventory\warehouse as Warehouse;
use App\models\customer\customer as Customer;
use App\models\product\category as Category;

class Manage extends Component
{
    /** رأس الفاتورة */
    public $pos_id = null;
    public $pos_date;
    public $warehouse_id = null;
    public $customer_id  = null;
    public $notes_ar = '';
    public $filterProductText = '';
    public $activeCategoryId = null;

    /** السلة/السطور */
    public $rows = []; // كل عنصر: product_id, unit_id, qty, unit_price, unit_options[], preview[]

    /** المجاميع */
    public $subtotal = 0.0;
    public $discount = 0.0;
    public $tax      = 0.0;
    public $grand    = 0.0;

    /** كاش أسماء الوحدات */
    protected $unitNameCache = [];

    protected $listeners = [
        'deleteConfirmed' => 'removeRow',
    ];

    public function mount($id = null): void
    {
        $this->pos_date = now()->toDateString();

        if ($id) {
            // تحميل فاتورة موجودة + سطورها
            $this->pos_id = (int) $id;
            $pos = Pos::with('lines')->findOrFail($id);

            $trans = (array) ($pos->getTranslations('notes') ?? []);
            $this->notes_ar = $trans['ar'] ?? ($trans['en'] ?? '');

            $this->pos_date     = $pos->pos_date;
            $this->warehouse_id = $pos->warehouse_id;
            $this->customer_id  = $pos->customer_id;

            $this->rows = $pos->lines->map(function ($l) {
                $p = Product::find($l->product_id);
                [$opts, $prices, $uomById] = $this->unitOptionsForProduct($p);

                return [
                    'product_id'   => $l->product_id,
                    'unit_id'      => $l->unit_id,
                    'qty'          => (float) $l->qty,
                    'unit_price'   => (float) $l->unit_price,
                    'unit_options' => $opts,
                    'preview'      => [
                        'name'  => $this->safeTransName($p->name ?? null),
                        'sku'   => $p->sku ?? null,
                        'barcode' => $p->barcode ?? null,
                        'uom'   => $uomById[$l->unit_id] ?? $this->getUnitName($l->unit_id),
                    ],
                ];
            })->toArray();
        }

        $this->recalcTotals();
    }

    /* =========================
     *  Helpers (names / units)
     * ========================= */

    /** اسم آمن (قد يكون string أو JSON أو Array) */
    protected function safeTransName($raw): string
    {
        if (is_array($raw)) {
            return (string) ($raw[app()->getLocale()] ?? ($raw['ar'] ?? (reset($raw) ?: '')));
        }
        if (is_string($raw)) {
            $t = trim($raw);
            if ($t !== '' && ($t[0] === '{' || $t[0] === '[')) {
                $arr = json_decode($t, true);
                if (is_array($arr)) {
                    return (string) ($arr[app()->getLocale()] ?? ($arr['ar'] ?? (reset($arr) ?: $raw)));
                }
            }
            return $raw;
        }
        return '';
    }

    /** اسم الوحدة من كاش واحد */
    protected function getUnitName($unitId): ?string
    {
        if (!$unitId) return null;
        if (!isset($this->unitNameCache[$unitId])) {
            $u = Unit::find($unitId);
            $this->unitNameCache[$unitId] = $u ? $this->safeTransName($u->name) : null;
        }
        return $this->unitNameCache[$unitId];
    }

    /**
     * قراءة units_matrix وتكوين:
     *  - $options [unit_id => unit_name]
     *  - $prices  [unit_id => price]
     *  - $uomById [unit_id => unit_name]
     * تختار الوحدة الافتراضية من sale_unit_key إن توفرت
     */
    protected function unitOptionsForProduct(?Product $p): array
    {
        $options = [];
        $prices  = [];
        $uomById = [];

        if (!$p) return [$options, $prices, $uomById];

        $raw = $p->units_matrix ?? null;
        $matrix = null;

        if (is_array($raw)) {
            $matrix = $raw;
        } elseif (is_string($raw) && trim($raw) !== '') {
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) $matrix = $decoded;
        }

        // دعم شكلين شائعين:
        // 1) keyed (minor/middle/major)  2) list of rows
        if (is_array($matrix)) {
            if ($this->isAssoc($matrix)) {
                foreach ($matrix as $key => $row) {
                    $uid   = (int)($row['unit_id'] ?? 0);
                    if (!$uid) continue;
                    $uname = $this->getUnitName($uid);
                    $price = (float)($row['price'] ?? ($row['sale_price'] ?? 0));
                    if ($uname) {
                        $options[$uid] = $uname;
                        $prices[$uid]  = $price;
                        $uomById[$uid] = $uname;
                    }
                }
            } else {
                foreach ($matrix as $row) {
                    if (!is_array($row)) continue;
                    $uid = (int)($row['unit_id'] ?? 0);
                    if (!$uid) continue;
                    $uname = $this->getUnitName($uid);
                    $price = (float)($row['price'] ?? ($row['sale_price'] ?? 0));
                    if ($uname) {
                        $options[$uid] = $uname;
                        $prices[$uid]  = $price;
                        $uomById[$uid] = $uname;
                    }
                }
            }
        }

        // fallback: لو مفيش matrix خالص – استخدم وحدة المنتج الأساسية
        if (!$options && $p->unit_id) {
            $uname = $this->getUnitName($p->unit_id);
            if ($uname) {
                $options[$p->unit_id] = $uname;
                $prices[$p->unit_id]  = (float)($p->price ?? 0);
                $uomById[$p->unit_id] = $uname;
            }
        }

        return [$options, $prices, $uomById];
    }

    protected function isAssoc(array $arr): bool
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    /* =============
     *   UI Actions
     * ============= */

    public function selectCategory($catId): void
    {
        $this->activeCategoryId = (int) $catId ?: null;
    }

    public function addProductToCart(int $product_id): void
    {
        $p = Product::find($product_id);
        if (!$p) return;

        [$options, $prices, $uomById] = $this->unitOptionsForProduct($p);

        // اختيار وحدة افتراضيًا: sale_unit_key -> unit_id منصوص بالماتريكس
        $defaultUnitId = null;
        if (is_array($p->units_matrix)) {
            $m = $p->units_matrix;
        } else {
            $m = is_string($p->units_matrix) ? (json_decode($p->units_matrix, true) ?: []) : [];
        }
        if (is_array($m) && $this->isAssoc($m)) {
            $key = $p->sale_unit_key ?? null; // minor / middle / major
            if ($key && !empty($m[$key]['unit_id'])) {
                $defaultUnitId = (int) $m[$key]['unit_id'];
            }
        }
        if (!$defaultUnitId) {
            // أول وحدة متاحة
            $defaultUnitId = (int) array_key_first($options);
        }

        $unitPrice = (float) ($prices[$defaultUnitId] ?? 0);

        $this->rows[] = [
            'product_id'   => $p->id,
            'unit_id'      => $defaultUnitId,
            'qty'          => 1,
            'unit_price'   => $unitPrice,
            'unit_options' => $options,
            'preview'      => [
                'name'     => $this->safeTransName($p->name),
                'sku'      => $p->sku ?? null,
                'barcode'  => $p->barcode ?? null,
                'uom'      => $uomById[$defaultUnitId] ?? $this->getUnitName($defaultUnitId),
            ],
        ];

        $this->recalcTotals();
    }

    public function rowUnitChanged(int $i): void
    {
        $row = $this->rows[$i] ?? null;
        if (!$row) return;

        $p = Product::find($row['product_id']);
        if (!$p) return;

        [, $prices, $uomById] = $this->unitOptionsForProduct($p);
        $uid = (int) ($row['unit_id'] ?? 0);
        $this->rows[$i]['unit_price'] = (float) ($prices[$uid] ?? 0);
        $this->rows[$i]['preview']['uom'] = $uomById[$uid] ?? $this->getUnitName($uid);

        $this->recalcTotals();
    }

    public function incQty(int $i): void
    {
        if (!isset($this->rows[$i])) return;
        $this->rows[$i]['qty'] = (float) ($this->rows[$i]['qty'] ?? 0) + 1;
        $this->recalcTotals();
    }

    public function decQty(int $i): void
    {
        if (!isset($this->rows[$i])) return;
        $q = (float) ($this->rows[$i]['qty'] ?? 0);
        $q = max(0, $q - 1);
        $this->rows[$i]['qty'] = $q;
        $this->recalcTotals();
    }

    public function removeRow($idx): void
    {
        array_splice($this->rows, (int) $idx, 1);
        $this->recalcTotals();
    }

    public function clearCart(): void
    {
        $this->rows = [];
        $this->recalcTotals();
    }

    protected function rules(): array
    {
        return [
            'pos_date'     => 'required|date',
            'warehouse_id' => 'required|exists:warehouses,id',
            'customer_id'  => 'nullable|exists:customers,id',

            'discount' => 'nullable|numeric|min:0',
            'tax'      => 'nullable|numeric|min:0',

            'rows'              => 'required|array|min:1',
            'rows.*.product_id' => 'required|exists:products,id',
            'rows.*.unit_id'    => 'required|exists:unit,id',
            'rows.*.qty'        => 'required|numeric|min:0.0001',
            'rows.*.unit_price' => 'required|numeric|min:0',
        ];
    }

    protected $messages = [
        'pos_date.required'         => 'تاريخ الفاتورة مطلوب',
        'warehouse_id.required'     => 'المخزن مطلوب',
        'rows.required'             => 'السلة فارغة',
        'rows.*.product_id.required'=> 'يرجى اختيار منتج',
        'rows.*.unit_id.required'   => 'اختر وحدة',
        'rows.*.qty.min'            => 'الكمية يجب أن تكون أكبر من صفر',
    ];

    private function recalcTotals(): void
    {
        $sum = 0.0;
        foreach ($this->rows as $r) {
            $sum += (float) ($r['qty'] ?? 0) * (float) ($r['unit_price'] ?? 0);
        }
        $this->subtotal = round($sum, 4);
        $this->grand    = round($this->subtotal - (float)$this->discount + (float)$this->tax, 4);
    }

    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            if ($this->pos_id) {
                $pos = Pos::lockForUpdate()->findOrFail($this->pos_id);
            } else {
                $pos = new Pos();
                $next = (int) ((Pos::max('id') ?? 0) + 1);
                $pos->pos_no = 'SO-'.now()->format('Ymd').'-'.str_pad((string)$next, 4, '0', STR_PAD_LEFT);
                $pos->status = 'draft';
            }

            $pos->pos_date     = $this->pos_date;
            $pos->warehouse_id = $this->warehouse_id;
            $pos->customer_id  = $this->customer_id;
            $pos->notes        = ['ar' => $this->notes_ar];
            $pos->subtotal     = $this->subtotal;
            $pos->discount     = $this->discount;
            $pos->tax          = $this->tax;
            $pos->grand_total  = $this->grand;
            $pos->user_id      = auth()->id();

            $pos->save();

            // أعادة بناء السطور
            $pos->lines()->delete();

            foreach ($this->rows as $r) {
                $line = new PosLine([
                    'product_id'   => $r['product_id'],
                    'unit_id'      => $r['unit_id'],
                    'warehouse_id' => $this->warehouse_id,
                    'qty'          => (float) $r['qty'],
                    'unit_price'   => (float) $r['unit_price'],
                    'line_total'   => round((float)$r['qty'] * (float)$r['unit_price'], 4),
                    'uom_text'     => $r['preview']['uom'] ?? null,
                ]);
                $pos->lines()->save($line);
            }

            $this->pos_id = $pos->id;
        });

        session()->flash('success', __('pos.saved_ok'));
        // لا نعيد التوجيه هنا حتى لا نفقد تجربة الـ POS
    }

    /* =============
     *    Render
     * ============= */

    public function render()
    {
        $categories = Category::orderBy('name')->get(['id','name']);

        // نجلب منتجات بحد أدنى من الأعمدة
        $products = Product::select('id','name','sku','barcode','category_id','unit_id','units_matrix','sale_unit_key')
            ->when($this->activeCategoryId, fn($q) => $q->where('category_id', $this->activeCategoryId))
            ->get()
            ->map(function ($p) {
                // حساب أقل سعر لعرضه على كرت المنتج
                [$opts, $prices] = $this->unitOptionsForProduct($p);
                $minPrice = 0.0;
                if ($prices) {
                    $minPrice = (float) min($prices);
                }
                $p->min_price = $minPrice;
                // تنظيف الاسم
                $p->name = $this->safeTransName($p->name);
                return $p;
            });

        return view('livewire.pos.manage', [
            'categories' => $categories,
            'warehouses' => Warehouse::orderBy('name')->get(['id','name']),
            'customers'  => Customer::orderBy('name')->get(['id','name']),
            'products'   => $products,
            'catalogProducts' => $products, // لاستخدامه في الفيو
        ]);
    }
}
