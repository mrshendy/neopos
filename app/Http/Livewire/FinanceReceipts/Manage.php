<?php

namespace App\Http\Livewire\FinanceReceipts;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\models\finance\finance_receipt as Receipt;
use App\models\finance\finance_settings as Settings;

class Manage extends Component
{
    public $receipt_id;

    public $finance_settings_id;
    public $receipt_date;
    public $doc_no;
    public $reference;

    public $method = 'cash'; // cash/bank/pos/transfer
    public $amount_total = 0;
    public $return_amount = 0;

    public $status = 'active'; // active/canceled
    public $notes = ['ar'=>'','en'=>''];

    public $currency_id;
    public $customer_id;

    protected $rules = [
        'finance_settings_id' => 'required|integer|exists:finance_settings,id',
        'receipt_date'        => 'required|date',
        'doc_no'              => 'nullable|string|max:50',
        'reference'           => 'nullable|string|max:100',
        'method'              => 'required|in:cash,bank,pos,transfer',
        'amount_total'        => 'required|numeric|min:0',
        'return_amount'       => 'nullable|numeric|min:0',
        'status'              => 'required|in:active,canceled',
        'notes.ar'            => 'nullable|string|max:1000',
        'notes.en'            => 'nullable|string|max:1000',
        'currency_id'         => 'nullable|integer',
        'customer_id'         => 'nullable|integer',
    ];

    protected $messages = [
        'finance_settings_id.required' => 'الخزينة مطلوبة.',
        'receipt_date.required'        => 'تاريخ الإيصال مطلوب.',
        'amount_total.required'        => 'المبلغ مطلوب.',
        'method.required'              => 'طريقة الدفع مطلوبة.',
    ];

    public function mount($id=null)
    {
        $this->receipt_date = now()->format('Y-m-d\TH:i');

        if($id){
            $this->receipt_id = $id;
            $row = Receipt::findOrFail($id);

            $this->finance_settings_id = $row->finance_settings_id;
            $this->receipt_date        = optional($row->receipt_date)->format('Y-m-d\TH:i');
            $this->doc_no              = $row->doc_no;
            $this->reference           = $row->reference;
            $this->method              = $row->method;
            $this->amount_total        = $row->amount_total;
            $this->return_amount       = $row->return_amount;
            $this->status              = $row->status;
            $this->notes               = $row->getTranslations('notes') ?: $this->notes;
            $this->currency_id         = $row->currency_id;
            $this->customer_id         = $row->customer_id;
        }
    }

    public function save()
    {
        $this->validate();

        $payload = [
            'finance_settings_id' => $this->finance_settings_id,
            'receipt_date'        => $this->receipt_date,
            'doc_no'              => $this->doc_no,
            'reference'           => $this->reference,
            'method'              => $this->method,
            'amount_total'        => $this->amount_total,
            'return_amount'       => $this->return_amount ?: 0,
            'status'              => $this->status,
            'notes'               => $this->notes,
            'currency_id'         => $this->currency_id,
            'customer_id'         => $this->customer_id,
            'updated_by'          => Auth::id(),
        ];

        if($this->receipt_id){
            $row = Receipt::findOrFail($this->receipt_id);
            $row->update($payload);
        }else{
            $payload['created_by'] = Auth::id();
            $row = Receipt::create($payload);
            $this->receipt_id = $row->id;
        }

        session()->flash('success', __('pos.msg_updated_success'));
        return redirect()->route('finance.receipts');
    }

    public function render()
    {
        return view('livewire.finance-receipts.manage', [
            'cashboxes' => Settings::orderBy('id')->get(),
        ]);
    }
}
