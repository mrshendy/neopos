<?php

namespace App\Http\Livewire\Pos;

use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\models\pos\pos as Pos;
use App\models\pos\posLine as PosLine;
use App\models\product\product as Product;
use App\models\unit\unit as Unit;
use App\models\inventory\warehouse as Warehouse;
use App\models\customer\customer as Customer;
use App\models\product\category as Category;

class Manage extends Component
{
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

    public float $discount = 0.0;
    public float $tax      = 0.0;
    public float $subtotal = 0.0;
    public float $grand    = 0.0;

    /** @var array<int, array> */
    public array $rows = [];

    public $products;
    public $categories;
    public $customers;
    public $warehouses;

    public ?int $pos_id = null;

    protected $listeners = [];

    protected array $rules = [
        'warehouse_id' => 'required|integer',
        'customer_id'  => 'nullable|integer',
        'pos_date'     => 'required|date',
        'discount'     => 'nullable|numeric|min:0',
        'tax'          => 'nullable|numeric|min:0',
        'rows'         => 'array|min:1',
        'rows.*.product_id' => 'required|integer',
        'rows.*.qty'        => 'required|numeric|min:0.01',
        'rows.*.unit_price' => 'required|numeric|min:0',
    ];

    public function mount(?int $posId = null): void
    {
        $this->pos_id   = $posId;
        $this->pos_date = Carbon::today()->toDateString();

        $this->products   = Product::query()->with('unit')->latest('id')->limit(500)->get();
        $this->categories = Category::query()->orderBy('id')->get();
        $this->warehouses = Warehouse::query()->orderBy('id')->get();
        $this->customers  = Customer::query()->latest('id')->limit(500)->get();

        if (blank($this->warehouse_id) && $this->warehouses->count() === 1) {
            $this->warehouse_id = (int)$this->warehouses->first()->id;
        }

        if ($this->pos_id) {
            $pos = Pos::with('lines.product')->find($this->pos_id);
            if ($pos) {
                $this->warehouse_id = (int)($pos->warehouse_id ?? 0) ?: null;
                $this->customer_id  = (int)($pos->customer_id  ?? 0) ?: null;
                $this->pos_date     = (string)$pos->pos_date;
                $this->notes_ar     = (string)($pos->notes ?? '');

                $this->discount     = (float)$pos->discount;
                $this->tax          = (float)$pos->tax;

                $this->rows = [];
                foreach ($pos->lines as $line) {
                    $p = $line->product;
                    $this->rows[] = [
                        'product_id'   => (int)$line->product_id,
                        'qty'          => (float)$line->qty,
                        'unit_id'      => null,
                        'unit_price'   => (float)$line->unit_price,
                        'unit_options' => [],
                        'preview'      => [
                            'name'  => $this->localize($p->name ?? $line->uom_text ?? __('pos.unit')),
                            'uom'   => $line->uom_text ?? ($p?->unit?->name ?? __('pos.unit')),
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
            'products'        => $this->products,
            'categories'      => $this->categories,
            'warehouses'      => $this->warehouses,
            'customers'       => $this->customers,
            'catalogProducts' => null,
        ]);
    }

    /* ---------------- Helpers ---------------- */

    private function localize($raw): string
    {
        if (is_array($raw)) {
            return (string)($raw[app()->getLocale()] ?? ($raw['ar'] ?? (reset($raw) ?: '')));
        }
        if (is_string($raw)) {
            $trim = trim($raw);
            if ($trim !== '' && (Str::startsWith($trim, '{') || Str::startsWith($trim, '['))) {
                $arr = json_decode($trim, true);
                if (is_array($arr)) {
                    return (string)($arr[app()->getLocale()] ?? ($arr['ar'] ?? $raw));
                }
            }
            return $raw;
        }
        return (string)$raw;
    }

    private function productThumbUrl(?Product $product): ?string
    {
        if (!$product) return null;
        $path = $product->image_path ?: null;
        if (!$path) return null;

        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->url($path);
        }
        return asset($path);
    }

    /**
     * تحميل وحدات البيع من units_matrix (سواء Array cast أو String JSON)
     * return: ['options'=>[key=>label], 'map'=>[key=>['price'=>..,'uom'=>..]], 'defaultUnitId'=>key]
     */
    private function loadUnitOptions(Product $product): array
    {
        $options = [];
        $map     = [];
        $defaultUnitId = null;

        // ✅ التعامل الآمن مع Array أو String
        $matrix = [];
        $raw = $product->units_matrix;

        if (is_array($raw)) {
            $matrix = $raw;
        } elseif (is_string($raw) && trim($raw) !== '') {
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) $matrix = $decoded;
        }

        foreach (['minor','middle','major'] as $key) {
            if (!isset($matrix[$key])) continue;
            $label = $matrix[$key]['label'] ?? ucfirst($key);
            $price = (float)($matrix[$key]['price'] ?? 0);
            $options[$key] = $label;
            $map[$key]     = ['price' => $price, 'uom' => $label];
        }

        $saleKey = $product->sale_unit_key;
        if ($saleKey && isset($options[$saleKey])) {
            $defaultUnitId = $saleKey;
        } else {
            $defaultUnitId = array_key_first($options);
        }

        if (empty($options)) {
            // fallback لو مفيش مصفوفة
            $baseName = $this->localize($product->unit->name ?? __('pos.unit'));
            $options  = ['base' => $baseName];
            $map      = ['base' => ['price' => 0.0, 'uom' => $baseName]];
            $defaultUnitId = 'base';
        }

        return compact('options','map','defaultUnitId');
    }

    /* -------------- فلاتر واختيارات -------------- */

    public function selectCategory(?int $categoryId): void
    {
        $this->activeCategoryId = $categoryId;
    }

    public function selectCustomerBySearch(): void
    {
        $name = trim($this->customerSearch);
        if ($name === '') return;
        $match = $this->customers->first(fn($c) => $this->localize($c->name) === $name);
        if ($match) $this->customer_id = (int)$match->id;
    }

    public function selectWarehouseBySearch(): void
    {
        $name = trim($this->warehouseSearch);
        if ($name === '') return;
        $match = $this->warehouses->first(fn($w) => $this->localize($w->name) === $name);
        if ($match) $this->warehouse_id = (int)$match->id;
    }

    public function selectCategoryBySearch(): void
    {
        $name = trim($this->categorySearch);
        if ($name === '') return;
        $match = $this->categories->first(fn($cat) => $this->localize($cat->name) === $name);
        $this->activeCategoryId = $match ? (int)$match->id : null;
    }

    /* -------------- السلة والوحدات -------------- */

    public function addByBarcode(): void
    {
        $code = trim($this->barcode);
        if ($code === '') return;

        $product = $this->products->first(fn($p) => (string)($p->barcode ?? '') === $code)
            ?? Product::where('barcode', $code)->first();

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
        $product = $this->products->firstWhere('id', $productId) ?? Product::with('unit')->find($productId);
        if (!$product) return;

        foreach ($this->rows as $i => $r) {
            if ((int)$r['product_id'] === (int)$productId) {
                $this->rows[$i]['qty'] = (float)$this->rows[$i]['qty'] + 1;
                $this->recomputeTotals();
                return;
            }
        }

        $units  = $this->loadUnitOptions($product);
        $pname  = $this->localize($product->name);
        $unitId = $units['defaultUnitId'];
        $info   = $units['map'][$unitId];

        $this->rows[] = [
            'product_id'   => (int)$product->id,
            'qty'          => 1,
            'unit_id'      => $unitId, // minor/middle/major/base
            'unit_price'   => (float)$info['price'],
            'unit_options' => $units['options'],
            'preview'      => [
                'name'  => $pname,
                'uom'   => $info['uom'],
                'image' => $this->productThumbUrl($product),
            ],
        ];

        $this->recomputeTotals();
    }

    public function rowUnitChanged(int $index): void
    {
        if (!array_key_exists($index, $this->rows)) return;

        $row = $this->rows[$index];
        $product = $this->products->firstWhere('id', $row['product_id']) ?? Product::with('unit')->find($row['product_id']);
        if (!$product) return;

        $units  = $this->loadUnitOptions($product);
        $unitId = $row['unit_id'] ?? $units['defaultUnitId'];

        if (!isset($units['map'][$unitId])) {
            $unitId = $units['defaultUnitId'];
        }

        $info = $units['map'][$unitId];
        $this->rows[$index]['unit_id']        = $unitId;
        $this->rows[$index]['unit_price']     = (float)$info['price'];
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

    /* -------------- الإجماليات -------------- */

    private function recomputeTotals(): void
    {
        $sub = 0.0;
        foreach ($this->rows as $r) {
            $sub += (float)($r['qty'] ?? 0) * (float)($r['unit_price'] ?? 0);
        }
        $this->subtotal = round($sub, 2);
        $this->grand    = round(max(0, $this->subtotal - (float)$this->discount + (float)$this->tax), 2);
    }

    public function updatedDiscount(): void { $this->recomputeTotals(); }
    public function updatedTax(): void      { $this->recomputeTotals(); }
    public function updatedRows(): void     { $this->recomputeTotals(); }

    /* -------------- الحفظ -------------- */

    public function save()
    {
        $this->validate();

        if (empty($this->rows)) {
            $this->addError('rows', __('السلة فارغة'));
            return;
        }

        DB::transaction(function () {
            $pos = Pos::updateOrCreate(
                ['id' => $this->pos_id],
                [
                    'pos_no'       => null,
                    'pos_date'     => $this->pos_date,
                    'status'       => 'draft',
                    'warehouse_id' => $this->warehouse_id,
                    'customer_id'  => $this->customer_id,
                    'user_id'      => auth()->id(),
                    'subtotal'     => $this->subtotal,
                    'discount'     => $this->discount,
                    'tax'          => $this->tax,
                    'grand_total'  => $this->grand,
                    'notes'        => $this->notes_ar,
                ]
            );

            $this->pos_id = (int)$pos->id;

            if (empty($pos->pos_no)) {
                $pos->pos_no = sprintf('POS-%s-%05d', date('Ymd'), $pos->id);
                $pos->save();
            }

            PosLine::where('pos_id', $pos->id)->delete();

            foreach ($this->rows as $r) {
                $product = $this->products->firstWhere('id', $r['product_id']) ?? Product::find($r['product_id']);

                $qty   = (float)($r['qty'] ?? 0);
                $price = (float)($r['unit_price'] ?? 0);
                $total = $qty * $price;

                $uomText = $r['preview']['uom'] ?? ($r['uom_text'] ?? null);
                $code    = $product?->sku ?: ($product?->barcode ?: null);

                PosLine::create([
                    'pos_id'       => $pos->id,
                    'product_id'   => (int)$r['product_id'],
                    'unit_id'      => null,
                    'code'         => $code,
                    'uom_text'     => $uomText,
                    'qty'          => $qty,
                    'unit_price'   => $price,
                    'line_total'   => $total,
                    'expiry_date'  => null,
                    'batch_no'     => null,
                    'notes'        => null,
                    'warehouse_id' => $this->warehouse_id,
                ]);
            }
        });

        session()->flash('success', __('تم حفظ/تحديث الفاتورة بنجاح'));
    }
}
