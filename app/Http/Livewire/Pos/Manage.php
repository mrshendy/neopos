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

class Manage extends Component
{
    public $pos_id = null;

    public $pos_date;
    public $delivery_date; // UI فقط (غير محفوظة)
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
            $this->customer_id   = $pos->customer_id ?? '';

            $this->rows = $pos->lines->map(function ($l) {
                return [
                    'product_id'  => $l->product_id,
                    'unit_id'     => $l->unit_id,
                    'qty'         => (float) $l->qty,
                    'unit_price'  => (float) $l->unit_price,
                    'onhand'      => 0,
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
        if (str_starts_with($field, 'rows.') || in_array($field, ['discount', 'tax'])) {
            $this->recalcTotals();
        }
    }

    public function rowProductChanged(int $i): void
    {
        $pid = $this->rows[$i]['product_id'] ?? null;
        if ($pid) {
            $p = Product::find($pid);
            if ($p) {
                $this->rows[$i]['unit_id'] = $p->unit_id ?? null;

                // استنتاج اسم الوحدة (يدعم JSON)
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
                $this->rows[$i]['onhand']   = 0; // اربطها بالمخزون لاحقًا
            }
        }
        $this->recalcTotals();
    }

    protected function rules(): array
    {
        return [
            'pos_date'      => 'required|date',
            // delivery_date غير محفوظة حاليًا
            'warehouse_id'  => 'required|exists:warehouses,id',
            'customer_id'    => 'nullable|exists:customer,id',

            'discount' => 'nullable|numeric|min:0',
            'tax'      => 'nullable|numeric|min:0',

            'rows'                 => 'required|array|min:1',
            'rows.*.product_id'    => 'required|exists:products,id',
            'rows.*.unit_id'       => 'nullable|exists:unit,id', // عدّل للاسم الصحيح عندك لو مفرد
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
                $pos->pos_no = 'SO-'.now()->format('Ymd').'-'.str_pad((string)$next, 4, '0', STR_PAD_LEFT);
                $pos->status  = 'draft';
            }

            // ✅ تأكيد سلامة customer_id مع FK: لو غير موجود في customers نحوله NULL
            $customerId = $this->customer_id ?: null;
            if ($customerId && !Customer::query()->whereKey($customerId)->exists()) {
                $customerId = null;
            }

            $pos->pos_date      = $this->pos_date;
            // لا نحفظ delivery_date الآن
            $pos->warehouse_id  = $this->warehouse_id;
            $pos->customer_id   = $customerId;    // إمّا id صحيح أو NULL
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

                // لو unit_id غير موجود بالفعل خلّيه NULL لتفادي أي FK مماثل
                $unitId = $r['unit_id'] ?: null;
                if ($unitId && !Unit::query()->whereKey($unitId)->exists()) {
                    $unitId = null;
                }

                $line = new PosLine([
                    'product_id'   => $r['product_id'],
                    'unit_id'      => $unitId,
                    'warehouse_id' => $this->warehouse_id,
                    'code'         => null,
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
            'customers'  => Customer::orderBy('name')->get(['id','name']), // الـ Model لازم يشير لجدول customers
            'products'   => Product::orderBy('name')->get(['id','name','unit_id']),
            'units'      => Unit::orderBy('name')->get(['id','name']),
        ]);
    }
}
