<?php

namespace App\Http\Livewire\inventory\transactions;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\models\inventory\{
    stock_transaction, stock_transaction_line, warehouse, item, setting
};

class Create extends Component
{
    public $type = 'adjustment';
    public $trx_date;
    public $warehouse_from_id = '';
    public $warehouse_to_id = '';
    public $notes = '';

    public $lines = [
        ['item_id' => '', 'batch_id' => '', 'serial_id' => '', 'qty' => 1, 'uom' => 'unit', 'reason' => ''],
    ];

    protected $rules = [
        'type'                 => 'required|in:sales_issue,sales_return,adjustment,transfer,purchase_receive',
        'trx_date'             => 'required|date',
        'warehouse_from_id'    => 'nullable|exists:warehouses,id',
        'warehouse_to_id'      => 'nullable|exists:warehouses,id',
        'lines.*.item_id'      => 'required|exists:items,id',
        'lines.*.qty'          => 'required|numeric|min:0.000001',
        'lines.*.uom'          => 'required|string|max:50',
        'lines.*.reason'       => 'nullable|string|max:255',
        'notes'                => 'nullable|string|max:2000',
    ];

    protected $messages = [
        'type.required'              => 'نوع الحركة مطلوب',
        'trx_date.required'          => 'تاريخ الحركة مطلوب',
        'lines.*.item_id.required'   => 'اختر الصنف',
        'lines.*.qty.min'            => 'الكمية يجب أن تكون أكبر من صفر',
        'lines.*.uom.required'       => 'وحدة القياس مطلوبة للبند',
    ];

    public function mount()
    {
        $this->trx_date = Carbon::now()->format('Y-m-d\TH:i');
    }

    public function addLine()
    {
        $this->lines[] = ['item_id' => '', 'batch_id' => '', 'serial_id' => '', 'qty' => 1, 'uom' => 'unit', 'reason' => ''];
    }

    public function removeLine($index)
    {
        unset($this->lines[$index]);
        $this->lines = array_values($this->lines);
    }

    protected function nextTrxNo(): string
    {
        $year = date('Y');
        $prefix = 'INV-TRX-' . $year . '-';
        $last = stock_transaction::where('trx_no', 'like', $prefix . '%')->orderByDesc('id')->first();
        $seq = 1;
        if ($last) {
            $num = (int) Str::after($last->trx_no, $prefix);
            $seq = $num + 1;
        }
        return $prefix . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }

    public function save()
    {
        $this->validate();

        // مثال بسيط لسياسة السالب (للتوسع لاحقًا)
        $negativePolicy = optional(setting::where('key', 'negative_stock_policy')->first())->value['policy'] ?? 'block';

        DB::transaction(function () {
            $trx = stock_transaction::create([
                'trx_no'            => $this->nextTrxNo(),
                'type'              => $this->type,
                'trx_date'          => $this->trx_date,
                'warehouse_from_id' => $this->warehouse_from_id ?: null,
                'warehouse_to_id'   => $this->warehouse_to_id ?: null,
                'user_id'           => auth()->id(),
                'ref_type'          => null,
                'ref_id'            => null,
                'notes'             => $this->notes ?: null,
                'status'            => 'draft',
            ]);

            foreach ($this->lines as $ln) {
                stock_transaction_line::create([
                    'stock_transaction_id' => $trx->id,
                    'item_id'   => $ln['item_id'],
                    'batch_id'  => $ln['batch_id'] ?: null,
                    'serial_id' => $ln['serial_id'] ?: null,
                    'qty'       => $ln['qty'],
                    'uom'       => $ln['uom'],
                    'reason'    => $ln['reason'] ?: null,
                ]);
            }
        });

        session()->flash('success', trans('pos.saved_success'));
        return redirect()->route('inventory.transactions.index');
    }

    public function render()
    {
        return view('livewire.inventory.transactions.create', [
            'warehouses' => warehouse::orderByDesc('id')->get(),
            'items'      => item::orderByDesc('id')->get(),
        ]);
    }
}
