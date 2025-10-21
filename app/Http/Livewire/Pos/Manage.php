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

    public $sale_date;
    public $delivery_date;
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
        $this->sale_date = now()->toDateString();
        $this->delivery_date = null;

        if ($id) {
            $this->pos_id = (int) $id;
            $pos = Pos::with('lines')->findOrFail($id);

            $t = (array) ($pos->getTranslations('notes') ?? []);
            $this->notes_ar = $t['ar'] ?? '';
            $this->notes_en = $t['en'] ?? '';

            $this->sale_date     = $pos->sale_date;
            $this->delivery_date = $pos->delivery_date;
            $this->warehouse_id  = $pos->warehouse_id;
            $this->customer_id   = $pos->customer_id;

            $this->rows = $pos->lines->map(function ($l) {
                return [
                    'category_id' => $l->category_id,
                    'product_id'  => $l->product_id,
                    'unit_id'     => $l->unit_id,
                    'qty'         => (float) $l->qty,
                    'unit_price'  => (float) $l->unit_price,
                    'onhand'      => 0, // للعرض فقط
                    'uom_text'    => $l->uom_text ?? null,
                    'has_expiry'  => !empty($l->expiry_date),
                    'expiry_date' => $l->expiry_date,
                    'batch_no'    => $l->batch_no,
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
            'qty'         => 0,
            'unit_price'  => 0,
            'onhand'      => 0,
            'uom_text'    => null,
            'has_expiry'  => false,
            'expiry_date' => null,
            'batch_no'    => null,
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
        if (str_starts_with($field, 'rows.')) {
            $this->recalcTotals();
        }
    }

    public function rowCategoryChanged(int $i): void
    {
        $this->rows[$i]['product_id'] = null;
        $this->rows[$i]['unit_id']    = null;
        $this->rows[$i]['uom_text']   = null;
        $this->rows[$i]['onhand']     = 0;
    }

    public function rowProductChanged(int $i): void
    {
        $pid = $this->rows[$i]['product_id'] ?? null;
        if ($pid) {
            $p = Product::find($pid);
            if ($p) {
                $this->rows[$i]['unit_id'] = $p->unit_id ?? null;

                // حساب اسم الوحدة من جدول الوحدات (مع دعم JSON name)
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
                $this->rows[$i]['onhand']   = 0; // لو عندك دالة رصيد مخزني، استبدله هنا
            }
        }
        $this->recalcTotals();
    }

    protected function rules(): array
    {
        return [
            'sale_date'     => 'required|date',
            'delivery_date' => 'nullable|date',
            'warehouse_id'  => 'required|exists:warehouses,id',
            'customer_id'   => 'nullable|exists:customers,id',
            'rows'          => 'required|array|min:1',

            'rows.*.category_id' => 'nullable|integer',
            'rows.*.product_id'  => 'required|exists:products,id',
            'rows.*.unit_id'     => 'nullable|exists:units,id',
            'rows.*.qty'         => 'required|numeric|min:0.0001',
            'rows.*.unit_price'  => 'required|numeric|min:0',
            'rows.*.expiry_date' => 'nullable|date',
            'rows.*.batch_no'    => 'nullable|string|max:80',
        ];
    }

    protected $messages = [
        'sale_date.required'         => 'تاريخ البيع مطلوب',
        'warehouse_id.required'      => 'المخزن مطلوب',
        'rows.required'              => 'تفاصيل الفاتورة مطلوبة',
        'rows.*.product_id.required' => 'يرجى اختيار الصنف',
        'rows.*.qty.min'             => 'الكمية لابد أن تكون أكبر من صفر',
        'rows.*.unit_price.min'      => 'السعر لا يمكن أن يكون سالب',
    ];

    private function recalcTotals(): void
    {
        $subtotal = 0;
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
                $pos->sale_no = 'SO-'.now()->format('Ymd').'-'.str_pad((string)$next, 4, '0', STR_PAD_LEFT);
                $pos->status  = 'draft';
            }

            $pos->sale_date     = $this->sale_date;
            $pos->delivery_date = $this->delivery_date;
            $pos->warehouse_id  = $this->warehouse_id;
            $pos->customer_id   = $this->customer_id;
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
                    'category_id' => $r['category_id'],
                    'product_id'  => $r['product_id'],
                    'unit_id'     => $r['unit_id'],
                    'qty'         => (float) $r['qty'],
                    'unit_price'  => (float) $r['unit_price'],
                    'uom_text'    => $r['uom_text'], // ✅ العمود الصحيح في pos_lines
                    'expiry_date' => $r['expiry_date'],
                    'batch_no'    => $r['batch_no'],
                    'line_total'  => (float)$r['qty'] * (float)$r['unit_price'],
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
            // ✅ لا نطلب uom من products لأنه غير موجود عندك
            'products'   => Product::orderBy('name')->get(['id','name','category_id','unit_id']),
            'units'      => Unit::orderBy('name')->get(['id','name']),
        ]);
    }
}
