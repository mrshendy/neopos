<?php

namespace App\Http\Livewire\Pos;

use Livewire\Component;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\models\pos\pos as Pos;
use App\models\pos\posLine as PosLine;
use App\models\product\product as Product;
use App\models\unit\unit as Unit;
use App\models\inventory\warehouse as Warehouse;
use App\models\customer\customer as Customer;
use App\models\product\category as Category;

class Manage extends Component
{
    // فلاتر وأساسيات
    public string $barcode = '';
    public string $filterProductText = '';
    public ?int $activeCategoryId = null;

    public ?int $warehouse_id = null;
    public ?int $customer_id  = null;

    public string $customerSearch = '';
    public string $warehouseSearch = '';
    public string $categorySearch  = '';

    public string $pos_date = '';
    public string $notes_ar = '';

    // الأرقام
    public float $discount = 0.0;
    public float $tax      = 0.0;
    public float $subtotal = 0.0;
    public float $grand    = 0.0;

    // السلة
    /** @var array<int, array> */
    public array $rows = [];

    // بيانات كتالوج
    public $products;
    public $categories;
    public $customers;
    public $warehouses;

    // لو كان تعديل
    public ?int $pos_id = null;

    protected $listeners = [];

    protected array $rules = [
        'warehouse_id' => 'required|integer',
        'pos_date'     => 'required|date',
        'discount'     => 'nullable|numeric|min:0',
        'tax'          => 'nullable|numeric|min:0',
        'rows'         => 'array|min:1',
        'rows.*.product_id' => 'required|integer',
        'rows.*.qty'        => 'required|numeric|min:0.01',
        'rows.*.unit_price' => 'required|numeric|min:0',
        'rows.*.unit_id'    => 'nullable|integer',
    ];

    public function mount(?int $posId = null): void
    {
        $this->pos_id   = $posId;
        $this->pos_date = Carbon::today()->toDateString();

        // تحميل بيانات أساسية
        $this->products   = Product::query()->latest('id')->limit(500)->get();
        $this->categories = Category::query()->orderBy('id')->get();
        $this->warehouses = Warehouse::query()->orderBy('id')->get();
        $this->customers  = Customer::query()->latest('id')->limit(500)->get();

        // تعيين مخزن افتراضي لو واحد فقط
        if (blank($this->warehouse_id) && $this->warehouses->count() === 1) {
            $this->warehouse_id = (int)$this->warehouses->first()->id;
        }

        // لو تعديل: حمّل الفاتورة + السطور إلى السلة
        if ($this->pos_id) {
            $pos = Pos::with(['lines.product', 'lines.unit'])->findOrFail($this->pos_id);

            $this->warehouse_id = $pos->warehouse_id ?: $this->warehouse_id;
            $this->customer_id  = $pos->customer_id ?: null;
            $this->pos_date     = $pos->pos_date ?: $this->pos_date;
            $this->notes_ar     = (string)($pos->notes ?? '');
            $this->discount     = (float)$pos->discount;
            $this->tax          = (float)$pos->tax;

            $rows = [];
            foreach ($pos->lines as $line) {
                $product = $line->product;
                if (!$product) continue;

                $units = $this->loadUnitOptions($product);
                $pname = $this->localize($product->name);

                $unitId = $line->unit_id ?? $units['defaultUnitId'];
                if (!isset($units['map'][$unitId])) {
                    $unitId = $units['defaultUnitId'];
                }
                $info = $units['map'][$unitId];

                $rows[] = [
                    'product_id'   => (int)$product->id,
                    'qty'          => (float)$line->qty,
                    'unit_id'      => $unitId,
                    'unit_price'   => (float)($line->unit_price ?? $line->price ?? $info['price']),
                    'unit_options' => $units['options'],
                    'preview'      => [
                        'name' => $pname,
                        'uom'  => $line->uom_text ?? $info['uom'],
                    ],
                ];
            }
            $this->rows = $rows;
        }

        $this->recomputeTotals();
    }

    public function render()
    {
        return view('livewire.pos.manage', [
            'products'        => $this->products,
            'categories'      => $this->categories,
            'warehouses'      => $this->warehouses,
            'customers'       => $this->customers,
            'catalogProducts' => null, // لو عندك مصدر كتالوج خارجي
        ]);
    }

    /* =========================
       عمليات مساعدة (أسماء/وحدات)
       ========================= */

    private function localize($raw): string
    {
        if (is_array($raw)) {
            return (string)($raw[app()->getLocale()] ?? ($raw['ar'] ?? (reset($raw) ?: '')));
        }
        if (is_string($raw)) {
            $trim = trim($raw);
            if (Str::startsWith($trim, '{') || Str::startsWith($trim, '[')) {
                $arr = json_decode($trim, true);
                if (is_array($arr)) {
                    return (string)($arr[app()->getLocale()] ?? ($arr['ar'] ?? ($raw)));
                }
            }
            return $raw;
        }
        return (string) $raw;
    }

    /**
     * تحميل خيارات وحدات المنتج + السعر الافتراضي لكل وحدة
     * تُعيد: ['options' => [unit_id => name], 'map' => [unit_id => ['price'=>..., 'uom'=>...]], 'default_unit_id' => ?]
     */
    private function loadUnitOptions(Product $product): array
    {
        $options = [];
        $map     = [];
        $defaultUnitId = null;

        // إن كانت هناك علاقة many-to-many مع وحدات وأسعار (pivot)
        if (method_exists($product, 'units')) {
            $units = $product->units()->withPivot(['price', 'label', 'factor'])->get();
            foreach ($units as $u) {
                $uName = $this->localize($u->name ?? $u->pivot->label ?? __('pos.unit'));
                $options[$u->id] = $uName;
                $map[$u->id] = [
                    'price' => (float)($u->pivot->price ?? $product->min_price ?? 0),
                    'uom'   => $uName,
                ];
                if ($defaultUnitId === null) $defaultUnitId = (int)$u->id;
            }
        }

        // في حال ما عندك جدول وحدات: اعتمد أقل سعر كوحدة وحيدة
        if (empty($options)) {
            $options = [0 => __('pos.unit')];
            $map     = [0 => ['price' => (float)($product->min_price ?? 0), 'uom' => __('pos.unit')]];
            $defaultUnitId = 0;
        }

        return compact('options', 'map', 'defaultUnitId');
    }

    /* =========================
       واجهة المستخدم: فلاتر واختيارات
       ========================= */

    public function selectCategory(?int $categoryId): void
    {
        $this->activeCategoryId = $categoryId;
    }

    public function selectCustomerBySearch(): void
    {
        $name = trim($this->customerSearch);
        if ($name === '') return;
        $match = $this->customers->first(function ($c) use ($name) {
            return $this->localize($c->name) === $name;
        });
        if ($match) $this->customer_id = (int)$match->id;
    }

    public function selectWarehouseBySearch(): void
    {
        $name = trim($this->warehouseSearch);
        if ($name === '') return;
        $match = $this->warehouses->first(function ($w) use ($name) {
            return $this->localize($w->name) === $name;
        });
        if ($match) $this->warehouse_id = (int)$match->id;
    }

    public function selectCategoryBySearch(): void
    {
        $name = trim($this->categorySearch);
        if ($name === '') return;
        $match = $this->categories->first(function ($cat) use ($name) {
            return $this->localize($cat->name) === $name;
        });
        $this->activeCategoryId = $match ? (int)$match->id : null;
    }

    /* =========================
       السلة + الباركود + الوحدات
       ========================= */

    public function addByBarcode(): void
    {
        $code = trim($this->barcode);
        if ($code === '') return;

        $product = $this->products->first(function ($p) use ($code) {
            return (string)($p->barcode ?? '') === $code;
        }) ?? Product::where('barcode', $code)->first();

        if (!$product) {
            session()->flash('success', __('لم يتم العثور على منتج بهذا الباركود'));
            $this->barcode = '';
            return;
        }

        $this->addProductToCart($product->id);
        $this->barcode = '';
    }

    public function addProductToCart(int $productId): void
    {
        $product = $this->products->firstWhere('id', $productId) ?? Product::find($productId);
        if (!$product) return;

        // لو موجود بالسلة زوّد الكمية
        foreach ($this->rows as $i => $r) {
            if ((int)$r['product_id'] === (int)$productId) {
                $this->rows[$i]['qty'] = (float)$this->rows[$i]['qty'] + 1;
                $this->recomputeTotals();
                return;
            }
        }

        // تحميل الوحدات
        $units = $this->loadUnitOptions($product);
        $pname = $this->localize($product->name);

        $unitId   = $units['defaultUnitId'];
        $unitInfo = $units['map'][$unitId];

        $this->rows[] = [
            'product_id'   => (int)$product->id,
            'qty'          => 1,
            'unit_id'      => $unitId,
            'unit_price'   => (float)$unitInfo['price'],
            'unit_options' => $units['options'],
            'preview'      => [
                'name' => $pname,
                'uom'  => $unitInfo['uom'],
            ],
        ];

        $this->recomputeTotals();
    }

    public function rowUnitChanged(int $index): void
    {
        if (!array_key_exists($index, $this->rows)) return;

        $row = $this->rows[$index];
        $product = $this->products->firstWhere('id', $row['product_id']) ?? Product::find($row['product_id']);
        if (!$product) return;

        $units = $this->loadUnitOptions($product);

        $unitId = (int)($row['unit_id'] ?? $units['defaultUnitId']);

        if (!isset($units['map'][$unitId])) {
            $unitId = $units['defaultUnitId'];
        }

        $info = $units['map'][$unitId];

        $this->rows[$index]['unit_id']    = $unitId;
        $this->rows[$index]['unit_price'] = (float)$info['price'];
        $this->rows[$index]['preview']['uom'] = $info['uom'];

        $this->recomputeTotals();
    }

    public function incQty(int $index): void
    {
        if (!isset($this->rows[$index])) return;
        $this->rows[$index]['qty'] = max(0.01, ((float)$this->rows[$index]['qty']) + 1);
        $this->recomputeTotals();
    }

    public function decQty(int $index): void
    {
        if (!isset($this->rows[$index])) return;
        $this->rows[$index]['qty'] = max(0.01, ((float)$this->rows[$index]['qty']) - 1);
        $this->recomputeTotals();
    }

    public function removeRow(int $index): void
    {
        if (!isset($this->rows[$index])) return;
        unset($this->rows[$index]);
        $this->rows = array_values($this->rows);
        $this->recomputeTotals();
    }

    public function clearCart(): void
    {
        $this->rows = [];
        $this->recomputeTotals();
    }

    /* =========================
       إجماليات
       ========================= */

    private function recomputeTotals(): void
    {
        $sub = 0.0;
        foreach ($this->rows as $r) {
            $qty   = (float)($r['qty'] ?? 0);
            $price = (float)($r['unit_price'] ?? 0);
            $sub  += $qty * $price;
        }
        $this->subtotal = round($sub, 2);
        $disc = (float)$this->discount;
        $tax  = (float)$this->tax;
        $this->grand = round(max(0, $this->subtotal - $disc + $tax), 2);
    }

    public function updatedDiscount(): void { $this->recomputeTotals(); }
    public function updatedTax(): void      { $this->recomputeTotals(); }
    public function updatedRows(): void     { $this->recomputeTotals(); }

    /* =========================
       حفظ في pos + pos_lines
       ========================= */

    private function nextPosNo(): string
    {
        $prefix = 'POS-' . Carbon::now()->format('Ymd') . '-';
        $last = Pos::withTrashed()
            ->where('pos_no', 'like', $prefix.'%')
            ->orderByDesc('id')
            ->value('pos_no');

        $seq = 1;
        if ($last && preg_match('/-(\d{4})$/', $last, $m)) {
            $seq = (int)$m[1] + 1;
        }
        return $prefix . str_pad((string)$seq, 4, '0', STR_PAD_LEFT);
    }

    public function save()
    {
        $this->validate();

        if (empty($this->rows)) {
            $this->addError('rows', __('السلة فارغة'));
            return;
        }

        DB::transaction(function () {
            // رأس الفاتورة
            $pos = $this->pos_id
                ? Pos::lockForUpdate()->findOrFail($this->pos_id)
                : new Pos();

            if (!$this->pos_id) {
                $pos->pos_no = $this->nextPosNo();
            }

            $pos->pos_date     = $this->pos_date;
            $pos->status       = $pos->status ?: 'draft'; // اتركها مسودة
            $pos->warehouse_id = $this->warehouse_id;
            $pos->customer_id  = $this->customer_id;
            $pos->user_id      = Auth::id();
            $pos->subtotal     = $this->subtotal;
            $pos->discount     = $this->discount;
            $pos->tax          = $this->tax;
            $pos->grand_total  = $this->grand;
            $pos->notes        = $this->notes_ar ?: null;
            $pos->save();

            $this->pos_id = $pos->id;

            // حذف السطور القديمة ثم إنشاء الجديدة
            PosLine::where('pos_id', $pos->id)->delete();

            foreach ($this->rows as $r) {
                $qty   = (float)($r['qty'] ?? 0);
                $price = (float)($r['unit_price'] ?? 0);
                $total = $qty * $price;

                $uomText = $r['preview']['uom'] ?? ($r['uom_text'] ?? null);

                PosLine::create([
                    'pos_id'       => $pos->id,
                    'product_id'   => (int)$r['product_id'],
                    'unit_id'      => $r['unit_id'] ?: null,
                    'warehouse_id' => $this->warehouse_id,
                    'code'         => null,
                    'uom_text'     => $uomText,
                    'qty'          => $qty,
                    'unit_price'   => $price,
                    'line_total'   => $total,
                    'expiry_date'  => null,
                    'batch_no'     => null,
                    'notes'        => null,
                ]);
            }
        });

        session()->flash('success', $this->pos_id ? __('تم حفظ الفاتورة وتحديث السطور بنجاح') : __('تم حفظ الفاتورة بنجاح'));
        $this->recomputeTotals();
    }
}
