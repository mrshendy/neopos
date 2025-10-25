<?php

namespace App\Http\Livewire\Pos;

use App\models\currencies\currencies as Currency;
use App\models\customer\customer as Customer;
use App\models\finance\finance_movement as FinanceMovement;
use App\models\finance\finance_settings as FinanceSettings;
use App\models\inventory\warehouse as Warehouse;
use App\models\offers\coupons as Coupon;
use App\models\pos\Pos as Pos;
use App\models\pos\PosLine as PosLine;
use App\models\product\category as Category;
use App\models\product\product as Product;
use App\models\unit\unit as Unit;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class Manage extends Component
{
    // filters / picks
    public string $barcode = '';

    public string $filterProductText = '';

    public ?int $activeCategoryId = null;

    public ?int $warehouse_id = null;

    public ?int $customer_id = null;

    public string $customerSearch = '';

    public string $warehouseSearch = '';

    public string $categorySearch = '';

    // header
    public string $pos_date = '';

    public string $notes_ar = '';

    // totals
    public float $discount = 0.0;

    public float $tax = 0.0;

    public float $subtotal = 0.0;

    public float $grand = 0.0;

    // coupon
    public string $coupon_code = '';

    public float $coupon_value = 0.0;

    public bool $coupon_applied = false;

    public string $coupon_note = '';

    public string $coupon_error = '';

    // payments
    public string $payment_method = 'cash'; // cash|card|instapay|wallet

    public ?int $currency_id = null;

    public ?int $finance_settings_id = null;

    public float $amount_paid = 0.0;

    public float $change_due = 0.0;

    /** cart rows */
    public array $rows = [];

    // lookups
    public $products;

    public $categories;

    public $customers;

    public $warehouses;

    public $currencies;

    public $finSettings;

    public ?int $pos_id = null;

    protected array $rules = [
        'warehouse_id' => 'required|integer',
        'customer_id' => 'nullable|integer',
        'pos_date' => 'required|date',
        'discount' => 'nullable|numeric|min:0',
        'tax' => 'nullable|numeric|min:0',
        'rows' => 'array|min:1',
        'rows.*.product_id' => 'required|integer',
        'rows.*.qty' => 'required|numeric|min:0.01',
        'rows.*.unit_price' => 'required|numeric|min:0',
        'payment_method' => 'required|in:cash,card,instapay,wallet',
        'currency_id' => 'required|integer',
        'finance_settings_id' => 'required|integer',
        'amount_paid' => 'nullable|numeric|min:0',
    ];

    public function mount(?int $posId = null): void
    {
        $this->pos_id = $posId;
        $this->pos_date = Carbon::today()->toDateString();

        $this->products = Product::query()->with('unit')->latest('id')->limit(500)->get();
        $this->categories = Category::query()->orderBy('id')->get();
        $this->warehouses = Warehouse::query()->orderBy('id')->get();
        $this->customers = Customer::query()->latest('id')->limit(500)->get();
        $this->currencies = Currency::query()->orderBy('id')->get();
        $this->finSettings = FinanceSettings::query()->where('is_available', 1)->orderBy('id')->get();

        // defaults
        if (blank($this->warehouse_id) && $this->warehouses->count() === 1) {
            $this->warehouse_id = (int) $this->warehouses->first()->id;
        }
        if (blank($this->finance_settings_id) && $this->finSettings->count() >= 1) {
            $this->finance_settings_id = (int) $this->finSettings->first()->id;
        }
        if (blank($this->currency_id) && $this->currencies->count() >= 1) {
            // اختَر الافتراضية إن وُجدت
            $def = $this->currencies->firstWhere('is_default', 1) ?: $this->currencies->first();
            $this->currency_id = (int) $def->id;
        }

        if ($this->pos_id) {
            $pos = Pos::with('lines.product')->find($this->pos_id);
            if ($pos) {
                $this->warehouse_id = (int) ($pos->warehouse_id ?? 0) ?: null;
                $this->customer_id = (int) ($pos->customer_id ?? 0) ?: null;
                $this->pos_date = (string) $pos->pos_date;
                $this->notes_ar = (string) ($pos->notes ?? '');
                $this->discount = (float) $pos->discount;
                $this->tax = (float) $pos->tax;
                $this->rows = [];
                foreach ($pos->lines as $line) {
                    $p = $line->product;
                    $this->rows[] = [
                        'product_id' => (int) $line->product_id,
                        'qty' => (float) $line->qty,
                        'unit_id' => null,
                        'unit_price' => (float) $line->unit_price,
                        'unit_options' => [],
                        'preview' => [
                            'name' => $this->localize($p->name ?? $line->uom_text ?? __('pos.unit')),
                            'uom' => $line->uom_text ?? ($p?->unit?->name ?? __('pos.unit')),
                            'image' => $this->productThumbUrl($p),
                        ],
                    ];
                }
            }
        }

        $this->recomputeTotals();
    }

    public function render()
    {
        return view('livewire.pos.manage', [
            'products' => $this->products,
            'categories' => $this->categories,
            'warehouses' => $this->warehouses,
            'customers' => $this->customers,
            'currencies' => $this->currencies,
            'finSettings' => $this->finSettings,
            'catalogProducts' => null,
        ]);
    }

    /* ---------- helpers ---------- */

    private function localize($raw): string
    {
        if (is_array($raw)) {
            return (string) ($raw[app()->getLocale()] ?? ($raw['ar'] ?? (reset($raw) ?: '')));
        }
        if (is_string($raw)) {
            $trim = trim($raw);
            if ($trim !== '' && (Str::startsWith($trim, '{') || Str::startsWith($trim, '['))) {
                $arr = json_decode($trim, true);
                if (is_array($arr)) {
                    return (string) ($arr[app()->getLocale()] ?? ($arr['ar'] ?? $raw));
                }
            }

            return $raw;
        }

        return (string) $raw;
    }

    private function productThumbUrl(?Product $product): ?string
    {
        if (! $product) {
            return null;
        }
        $path = $product->image_path ?: null;
        if (! $path) {
            return null;
        }

        // الصور محفوظة في public/attachments
        return asset('attachments/'.ltrim($path, '/'));
    }

    /** قراءة مصفوفة وحدات المنتج بأمان */
    private function loadUnitOptions(Product $product): array
    {
        $options = [];   // [key => unit_name]
        $map = [];   // [key => ['price'=>..., 'uom'=>unit_name]]
        $defaultUnitId = null;

        // اقرأ المصفوفة بأمان (Array أو JSON String)
        $matrix = [];
        $raw = $product->units_matrix;
        if (is_array($raw)) {
            $matrix = $raw;
        } elseif (is_string($raw) && trim($raw) !== '') {
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) {
                $matrix = $decoded;
            }
        }

        // ابنِ الخيارات من الوحدات الحقيقية
        foreach (['minor', 'middle', 'major'] as $key) {
            if (! isset($matrix[$key])) {
                continue;
            }

            $unitId = $matrix[$key]['unit_id'] ?? null;
            $price = (float) ($matrix[$key]['price'] ?? 0);

            // اسم الوحدة الحقيقي من جدول units؛ إن لم يوجد نرجع للـ label المدوَّن
            $unitName = null;
            if ($unitId) {
                $unitObj = Unit::find($unitId);
                if ($unitObj) {
                    $unitName = is_array($unitObj->name)
                        ? ($unitObj->name[app()->getLocale()] ?? ($unitObj->name['ar'] ?? (reset($unitObj->name) ?: '')))
                        : (string) $unitObj->name;
                }
            }
            // fallback لاستخدام label فقط لو لم نجد الوحدة
            if (! $unitName) {
                $lbl = $matrix[$key]['label'] ?? ucfirst($key);
                $unitName = is_array($lbl)
                    ? ($lbl[app()->getLocale()] ?? ($lbl['ar'] ?? (reset($lbl) ?: ucfirst($key))))
                    : (string) $lbl;
            }

            $options[$key] = $unitName;
            $map[$key] = ['price' => $price, 'uom' => $unitName, 'unit_id' => $unitId];
        }

        // حدِّد الافتراضي:
        // 1) sale_unit_key إن متاح وموجود
        if ($product->sale_unit_key && isset($options[$product->sale_unit_key])) {
            $defaultUnitId = $product->sale_unit_key;
        } else {
            // 2) المفتاح الذي يطابق unit_id المسجَّل على المنتج
            $prodUnitId = $product->unit_id ?: null;
            if ($prodUnitId) {
                foreach ($map as $key => $info) {
                    if (! empty($info['unit_id']) && (int) $info['unit_id'] === (int) $prodUnitId) {
                        $defaultUnitId = $key;
                        break;
                    }
                }
            }
            // 3) أول مفتاح موجود
            if (! $defaultUnitId) {
                $defaultUnitId = array_key_first($options);
            }
        }

        // لو ما فيش أي مصفوفة، اعرض الوحدة المسجّلة على المنتج فقط
        if (empty($options)) {
            $baseName = $this->localize($product->unit->name ?? 'وحدة');
            $options = ['base' => $baseName];
            $map = ['base' => ['price' => 0.0, 'uom' => $baseName, 'unit_id' => $product->unit_id]];
            $defaultUnitId = 'base';
        }

        return [
            'options' => $options,
            'map' => $map,
            'defaultUnitId' => $defaultUnitId,
        ];
    }

    /* ---------- picks ---------- */

    public function selectCategory(?int $categoryId): void
    {
        $this->activeCategoryId = $categoryId;
    }

    public function selectCustomerBySearch(): void
    {
        $name = trim($this->customerSearch);
        if ($name === '') {
            return;
        }
        $match = $this->customers->first(fn ($c) => $this->localize($c->name) === $name);
        if ($match) {
            $this->customer_id = (int) $match->id;
        }
    }

    public function selectWarehouseBySearch(): void
    {
        $name = trim($this->warehouseSearch);
        if ($name === '') {
            return;
        }
        $match = $this->warehouses->first(fn ($w) => $this->localize($w->name) === $name);
        if ($match) {
            $this->warehouse_id = (int) $match->id;
        }
    }

    public function selectCategoryBySearch(): void
    {
        $name = trim($this->categorySearch);
        if ($name === '') {
            return;
        }
        $match = $this->categories->first(fn ($cat) => $this->localize($cat->name) === $name);
        $this->activeCategoryId = $match ? (int) $match->id : null;
    }

    /* ---------- cart ---------- */

    public function addByBarcode(): void
    {
        $code = trim($this->barcode);
        if ($code === '') {
            return;
        }
        $product = $this->products->first(fn ($p) => (string) ($p->barcode ?? '') === $code) ?? Product::where('barcode', $code)->first();
        if (! $product) {
            session()->flash('success', __('لم يتم العثور على منتج بهذا الباركود'));
            $this->barcode = '';

            return;
        }
        $this->addProductToCart($product->id);
        $this->barcode = '';
    }

    public function addProductToCart(int $productId): void
    {
        $product = $this->products->firstWhere('id', $productId) ?? Product::with('unit')->find($productId);
        if (! $product) {
            return;
        }

        foreach ($this->rows as $i => $r) {
            if ((int) $r['product_id'] === (int) $productId) {
                $this->rows[$i]['qty'] = (float) $this->rows[$i]['qty'] + 1;
                $this->recomputeTotals();

                return;
            }
        }

        $units = $this->loadUnitOptions($product);
        $pname = $this->localize($product->name);
        $unitId = $units['defaultUnitId'];
        $info = $units['map'][$unitId];

        $this->rows[] = [
            'product_id' => (int) $product->id,
            'qty' => 1,
            'unit_id' => $unitId,
            'unit_price' => (float) $info['price'],
            'unit_options' => $units['options'],
            'preview' => ['name' => $pname, 'uom' => $info['uom'], 'image' => $this->productThumbUrl($product)],
        ];

        $this->recomputeTotals();
    }

    public function rowUnitChanged(int $index): void
    {
        if (! array_key_exists($index, $this->rows)) {
            return;
        }
        $row = $this->rows[$index];
        $product = $this->products->firstWhere('id', $row['product_id']) ?? Product::with('unit')->find($row['product_id']);
        if (! $product) {
            return;
        }

        $units = $this->loadUnitOptions($product);
        $unitId = $row['unit_id'] ?? $units['defaultUnitId'];
        if (! isset($units['map'][$unitId])) {
            $unitId = $units['defaultUnitId'];
        }

        $info = $units['map'][$unitId];
        $this->rows[$index]['unit_id'] = $unitId;
        $this->rows[$index]['unit_price'] = (float) $info['price'];
        $this->rows[$index]['preview']['uom'] = $info['uom'];
        $this->recomputeTotals();
    }

    public function incQty(int $index): void
    {
        if (! isset($this->rows[$index])) {
            return;
        } $this->rows[$index]['qty'] = max(0.01, ((float) $this->rows[$index]['qty']) + 1);
        $this->recomputeTotals();
    }

    public function decQty(int $index): void
    {
        if (! isset($this->rows[$index])) {
            return;
        } $this->rows[$index]['qty'] = max(0.01, ((float) $this->rows[$index]['qty']) - 1);
        $this->recomputeTotals();
    }

    public function removeRow(int $index): void
    {
        if (! isset($this->rows[$index])) {
            return;
        } unset($this->rows[$index]);
        $this->rows = array_values($this->rows);
        $this->recomputeTotals();
    }

    public function clearCart(): void
    {
        $this->rows = [];
        $this->coupon_applied = false;
        $this->coupon_value = 0.0;
        $this->coupon_note = '';
        $this->coupon_error = '';
        $this->recomputeTotals();
    }

    /* ---------- totals & coupon ---------- */

    private function recomputeTotals(): void
    {
        $sub = 0.0;
        foreach ($this->rows as $r) {
            $sub += (float) ($r['qty'] ?? 0) * (float) ($r['unit_price'] ?? 0);
        }
        $this->subtotal = round($sub, 2);

        // grand = subtotal - discount - coupon + tax
        $gross = $this->subtotal - (float) $this->discount - (float) $this->coupon_value + (float) $this->tax;
        $this->grand = round(max(0, $gross), 2);

        // change due
        $this->change_due = round(max(0, (float) $this->amount_paid - $this->grand), 2);
    }

    public function updatedDiscount()
    {
        $this->recomputeTotals();
    }

    public function updatedTax()
    {
        $this->recomputeTotals();
    }

    public function updatedAmountPaid()
    {
        $this->recomputeTotals();
    }

    public function updatedRows()
    {
        $this->recomputeTotals();
    }

    public function applyCoupon(): void
    {
        $this->coupon_error = '';
        $this->coupon_applied = false;
        $this->coupon_value = 0;
        $this->coupon_note = '';
        $code = trim($this->coupon_code);
        if ($code === '') {
            $this->coupon_error = __('يرجى إدخال كود الكوبون');

            return;
        }

        $now = Carbon::now();
        $coupon = Coupon::where('code', $code)
            ->where('status', 'active')
            ->where(function ($q) use ($now) {
                $q->whereNull('start_at')->orWhere('start_at', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('end_at')->orWhere('end_at', '>=', $now);
            })
            ->first();

        if (! $coupon) {
            $this->coupon_error = __('الكوبون غير صالح');

            return;
        }

        // حساب قيمة الكوبون
        $value = 0.0;
        if ($coupon->type === 'percentage') {
            $value = round(($this->subtotal * (float) $coupon->discount_value) / 100, 2);
        } else { // fixed
            $value = round((float) $coupon->discount_value, 2);
        }
        $value = min($value, $this->subtotal); // لا يتخطى الإجمالي

        $this->coupon_value = $value;
        $this->coupon_applied = true;
        $this->coupon_note = $coupon->name ? (is_array($coupon->name) ? ($coupon->name[app()->getLocale()] ?? ($coupon->name['ar'] ?? '')) : $coupon->name) : $coupon->code;
        $this->recomputeTotals();
    }

    /* ---------- save ---------- */

    public function save()
    {
        $this->validate();
        if (empty($this->rows)) {
            $this->addError('rows', __('السلة فارغة'));

            return;
        }

        DB::transaction(function () {
            $isUpdate = ! empty($this->pos_id);
            $pos = $isUpdate ? Pos::lockForUpdate()->find($this->pos_id) : null;

            if ($pos) {
                $pos->update([
                    'pos_date' => $this->pos_date,
                    'status' => $pos->status ?? 'draft',
                    'warehouse_id' => $this->warehouse_id,
                    'customer_id' => $this->customer_id,
                    'user_id' => auth()->id(),
                    'subtotal' => $this->subtotal,
                    'discount' => $this->discount + $this->coupon_value, // التخفيضات الكلية
                    'tax' => $this->tax,
                    'grand_total' => $this->grand,
                    'notes' => $this->notes_ar,
                ]);
            } else {
                $pos = Pos::create([
                    'pos_no' => $this->generatePosNo(),
                    'pos_date' => $this->pos_date,
                    'status' => 'draft',
                    'warehouse_id' => $this->warehouse_id,
                    'customer_id' => $this->customer_id,
                    'user_id' => auth()->id(),
                    'subtotal' => $this->subtotal,
                    'discount' => $this->discount + $this->coupon_value,
                    'tax' => $this->tax,
                    'grand_total' => $this->grand,
                    'notes' => $this->notes_ar,
                ]);
            }

            $this->pos_id = (int) $pos->id;

            // lines
            PosLine::where('pos_id', $pos->id)->delete();
            foreach ($this->rows as $r) {
                $product = $this->products->firstWhere('id', $r['product_id']) ?? Product::find($r['product_id']);
                $qty = (float) ($r['qty'] ?? 0);
                $price = (float) ($r['unit_price'] ?? 0);
                $uomText = $r['preview']['uom'] ?? ($r['uom_text'] ?? null);
                $code = $product?->sku ?: ($product?->barcode ?: null);

                PosLine::create([
                    'pos_id' => $pos->id,
                    'product_id' => (int) $r['product_id'],
                    'unit_id' => null,
                    'code' => $code,
                    'uom_text' => $uomText,
                    'qty' => $qty,
                    'unit_price' => $price,
                    'line_total' => $qty * $price,
                    'expiry_date' => null,
                    'batch_no' => null,
                    'notes' => null,
                    'warehouse_id' => $this->warehouse_id,
                ]);
            }

            // -------- حركة الخزينة (توريد) --------
            FinanceMovement::create([
                'finance_settings_id' => $this->finance_settings_id,
                'movement_date' => $this->pos_date,
                'direction' => 'in', // توريد
                'amount' => $this->grand,
                'currency_id' => $this->currency_id,
                'method' => $this->payment_method === 'card' ? 'bank' :
                                         ($this->payment_method === 'cash' ? 'cash' : 'pos'),
                'doc_no' => $pos->pos_no,
                'reference' => 'POS#'.$pos->id,
                'status' => 'posted',
                'notes' => 'POS sale',
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);
        });

        session()->flash('success', __('تم حفظ/تحديث الفاتورة بنجاح'));
    }

    private function generatePosNo(): string
    {
        $date = date('Ymd');
        $nextId = (int) (Pos::max('id') + 1);
        $seq = str_pad((string) ($nextId % 100000), 5, '0', STR_PAD_LEFT);

        return "POS-{$date}-{$seq}";
    }
}
