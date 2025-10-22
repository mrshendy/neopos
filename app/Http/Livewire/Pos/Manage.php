<?php

namespace App\Http\Livewire\Pos;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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
    public $delivery_date; // UI فقط إن لم تكن محفوظة
    public $warehouse_id;
    public $customer_id;
    public $notes_ar = '';
    public $notes_en = '';

    /** @var array<int, array> */
    public $rows = [];

    // Totals (عرض فقط)
    public $subtotal = 0.0;
    public $discount = 0.0;
    public $tax      = 0.0;
    public $grand    = 0.0;

    protected $listeners = ['deleteConfirmed' => 'removeRow'];

    public function mount($id = null): void
    {
        $this->pos_date = now()->toDateString();
        $this->delivery_date = null;

        if ($id) {
            $this->pos_id = (int) $id;
            $pos = Pos::with('lines')->findOrFail($id);

            $t = (array) ($pos->getTranslations('notes') ?? []);
            $this->notes_ar = $t['ar'] ?? '';
            $this->notes_en = $t['en'] ?? '';

            $this->pos_date      = $pos->pos_date;
            $this->delivery_date = $pos->delivery_date ?? null;
            $this->warehouse_id  = $pos->warehouse_id;
            $this->customer_id   = $pos->customer_id ?: null;

            $this->rows = $pos->lines->map(function ($l) {
                return [
                    'category_id' => null, // للواجهة فقط
                    'product_id'  => $l->product_id,
                    'unit_id'     => $l->unit_id,
                    'qty'         => (float) $l->qty,
                    'unit_price'  => (float) $l->unit_price,
                    'onhand'      => 0, // عرض فقط (اربطه بالمخزون لاحقًا)
                    'uom_text'    => $l->uom_text ?? null,
                    'has_expiry'  => !empty($l->expiry_date),
                    'expiry_date' => $l->expiry_date,
                    'batch_no'    => $l->batch_no,
                    'preview'     => $this->buildProductPreview($l->product_id),
                ];
            })->toArray();
        } else {
            $this->rows = [$this->blankRow()];
        }

        $this->recalcTotals();
    }

    public function blankRow(): array
    {
        return [
            'category_id' => null,
            'product_id'  => null,
            'unit_id'     => null,
            'qty'         => 1,
            'unit_price'  => 0,
            'onhand'      => 0,
            'uom_text'    => null,
            'has_expiry'  => false,
            'expiry_date' => null,
            'batch_no'    => null,
            'preview'     => null, // بطاقة تفاصيل المنتج
        ];
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
        // أي تغيير في صفوف البنود أو الخصم/الضريبة يعيد الحساب
        if (str_starts_with($field, 'rows.') || in_array($field, ['discount', 'tax'])) {
            $this->recalcTotals();
        }
    }

    public function rowCategoryChanged(int $i): void
    {
        // عند تغيير القسم صفّر المنتج والوحدة والمعاينة
        $this->rows[$i]['product_id'] = null;
        $this->rows[$i]['unit_id']    = null;
        $this->rows[$i]['uom_text']   = null;
        $this->rows[$i]['unit_price'] = 0;
        $this->rows[$i]['preview']    = null;
        $this->rows[$i]['onhand']     = 0;
        $this->recalcTotals();
    }

    public function rowProductChanged(int $i): void
    {
        $pid = $this->rows[$i]['product_id'] ?? null;
        if ($pid) {
            $p = Product::find($pid);
            if ($p) {
                $this->rows[$i]['unit_id'] = $p->unit_id ?? null;

                // اسم الوحدة من جدول الوحدات (يدعم JSON)
                $uom = null;
                if ($p->unit_id) {
                    $u = Unit::find($p->unit_id);
                    if ($u) {
                        $uname = $u->name;
                        if (is_string($uname) && str_starts_with(trim($uname), '{')) {
                            $a = json_decode($uname, true) ?: [];
                            $uname = $a[app()->getLocale()] ?? $a['ar'] ?? $u->name;
                        }
                        $uom = $uname;
                    }
                }
                $this->rows[$i]['uom_text'] = $uom;

                // السعر الافتراضي من المنتج (نلتقط أول حقل موجود)
                $price = $this->guessProductPrice($p);
                $this->rows[$i]['unit_price'] = (float) $price;

                // بطاقة المعاينة
                $this->rows[$i]['preview'] = $this->buildProductPreview($p->id);

                // رصيد المخزون (اربط لاحقًا بوظيفتك)
                $this->rows[$i]['onhand']   = 0;
            }
        }
        $this->recalcTotals();
    }

    private function guessProductPrice(Product $p): float
    {
        foreach (['price','selling_price','sale_price','unit_price'] as $col) {
            if (isset($p->{$col}) && is_numeric($p->{$col})) {
                return (float) $p->{$col};
            }
        }
        return 0.0;
    }

    private function buildProductPreview($productId): ?array
    {
        if (!$productId) return null;
        $p = Product::find($productId);
        if (!$p) return null;

        // اسم آمن (يدعم JSON)
        $pname = $p->name;
        if (is_string($pname) && str_starts_with(trim($pname), '{')) {
            $a = json_decode($pname, true) ?: [];
            $pname = $a[app()->getLocale()] ?? $a['ar'] ?? $p->name;
        }

        $uom = null;
        if ($p->unit_id) {
            $u = Unit::find($p->unit_id);
            if ($u) {
                $uname = $u->name;
                if (is_string($uname) && str_starts_with(trim($uname), '{')) {
                    $a = json_decode($uname, true) ?: [];
                    $uname = $a[app()->getLocale()] ?? $a['ar'] ?? $u->name;
                }
                $uom = $uname;
            }
        }

        return [
            'name'        => $pname,
            'sku'         => $p->sku ?? null,
            'barcode'     => $p->barcode ?? null,
            'uom'         => $uom,
            'price'       => $this->guessProductPrice($p),
            'description' => $p->description ?? null,
            'category_id' => $p->category_id ?? null,
        ];
    }

    protected function rules(): array
    {
        // مرونة أسماء الجداول (مفرد/جمع) من الموديلات
        $warehouseTable = (new Warehouse)->getTable();
        $customerTable  = (new Customer)->getTable();
        $unitTable      = (new Unit)->getTable();
        $productTable   = (new Product)->getTable();

        return [
            'pos_date'      => 'required|date',
            'warehouse_id'  => ['required', Rule::exists($warehouseTable, 'id')],
            'customer_id'   => ['nullable', Rule::exists($customerTable, 'id')],

            'discount' => 'nullable|numeric|min:0',
            'tax'      => 'nullable|numeric|min:0',

            'rows'                 => 'required|array|min:1',
            'rows.*.category_id'   => 'nullable|integer',
            'rows.*.product_id'    => ['required', Rule::exists($productTable, 'id')],
            'rows.*.unit_id'       => ['nullable', Rule::exists($unitTable, 'id')],
            'rows.*.qty'           => 'required|numeric|min:0.0001',
            'rows.*.unit_price'    => 'required|numeric|min:0',
            'rows.*.expiry_date'   => 'nullable|date',
            'rows.*.batch_no'      => 'nullable|string|max:80',
        ];
    }

    protected $messages = [
        'pos_date.required'          => 'تاريخ البيع مطلوب',
        'warehouse_id.required'      => 'المخزن مطلوب',
        'rows.required'              => 'تفاصيل الفاتورة مطلوبة',
        'rows.*.product_id.required' => 'يرجى اختيار الصنف',
        'rows.*.qty.min'             => 'الكمية لابد أن تكون أكبر من صفر',
        'rows.*.unit_price.min'      => 'السعر لا يمكن أن يكون سالب',
    ];

    private function recalcTotals(): void
    {
        $subtotal = 0.0;
        foreach ($this->rows as $r) {
            $q  = (float) ($r['qty'] ?? 0);
            $up = (float) ($r['unit_price'] ?? 0);
            $subtotal += $q * $up;
        }
        $this->subtotal = round($subtotal, 4);
        $this->grand    = round($this->subtotal - (float)$this->discount + (float)$this->tax, 4);
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
                $next = (int) ((Pos::max('id') ?? 0) + 1);
                $pos->pos_no  = 'SO-'.now()->format('Ymd').'-'.str_pad((string)$next, 4, '0', STR_PAD_LEFT);
                $pos->status  = 'draft';
            }

            $customerId = $this->customer_id ?: null;

            $pos->pos_date      = $this->pos_date;
            // إن كان عندك delivery_date عمودًا بجدول pos فعّل السطر:
            // $pos->delivery_date = $this->delivery_date;
            $pos->warehouse_id  = $this->warehouse_id;
            $pos->customer_id   = $customerId;
            $pos->notes         = ['ar' => $this->notes_ar, 'en' => $this->notes_en];
            $pos->subtotal      = $this->subtotal;
            $pos->discount      = $this->discount;
            $pos->tax           = $this->tax;
            $pos->grand_total   = $this->grand;
            $pos->user_id       = auth()->id();
            $pos->save();

            // إعادة بناء البنود
            $pos->lines()->delete();

            foreach ($this->rows as $r) {
                if (empty($r['has_expiry'])) {
                    $r['expiry_date'] = null;
                }

                $line = new PosLine([
                    'product_id'   => $r['product_id'],
                    'unit_id'      => $r['unit_id'] ?: null,
                    'warehouse_id' => $this->warehouse_id,
                    'code'         => null, // لا نعتمد على code من products
                    'uom_text'     => $r['uom_text'],
                    'qty'          => (float) $r['qty'],
                    'unit_price'   => (float) $r['unit_price'],
                    'line_total'   => round((float)$r['qty'] * (float)$r['unit_price'], 4),
                    'expiry_date'  => $r['expiry_date'],
                    'batch_no'     => $r['batch_no'],
                    'notes'        => null,
                ]);
                $pos->lines()->save($line);
            }

            $this->pos_id = $pos->id;
        });

        session()->flash('success', __('pos.saved_ok'));
        return redirect()->route('pos.index');
    }

    public function render()
    {
        return view('livewire.pos.manage', [
            'warehouses' => Warehouse::orderBy('name')->get(['id','name']),
            'customers'  => Customer::orderBy('name')->get(['id','name']),
            'categories' => Category::orderBy('name')->get(['id','name']),
            'products'   => Product::orderBy('name')->get(['id','name','category_id','unit_id','price','selling_price','sale_price','unit_price','sku','barcode','description']),
            'units'      => Unit::orderBy('name')->get(['id','name']),
        ]);
    }
}
