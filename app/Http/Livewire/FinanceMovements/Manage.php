<?php

namespace App\Http\Livewire\FinanceMovements;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\models\finance\finance_movement as Movement;
use App\models\finance\finance_settings as Settings;

class Manage extends Component
{
    public $movement_id;

    public $finance_settings_id;
    public $movement_date;
    public $direction = 'in'; // in | out
    public $amount;
    public $currency_id;
    public $method = 'cash';  // cash/bank/pos/transfer

    public $doc_no;
    public $reference;
    public $status = 'draft'; // draft/posted/void

    public $notes = ['ar'=>'','en'=>''];

    protected $rules = [
        'finance_settings_id' => 'required|integer|exists:finance_settings,id',
        'movement_date'       => 'required|date',
        'direction'           => 'required|in:in,out',
        'amount'              => 'required|numeric|min:0.01',
        'currency_id'         => 'nullable|integer',
        'method'              => 'required|in:cash,bank,pos,transfer',
        'doc_no'              => 'nullable|string|max:50',
        'reference'           => 'nullable|string|max:100',
        'status'              => 'required|in:draft,posted,void',
        'notes.ar'            => 'nullable|string|max:1000',
        'notes.en'            => 'nullable|string|max:1000',
    ];

    protected $messages = [
        'finance_settings_id.required' => 'الخزينة مطلوبة.',
        'movement_date.required'       => 'تاريخ الحركة مطلوب.',
        'direction.required'           => 'نوع الحركة مطلوب.',
        'amount.required'              => 'المبلغ مطلوب.',
        'method.required'              => 'طريقة الدفع مطلوبة.',
    ];

    public function mount($id = null)
    {
        $this->movement_date = now()->toDateString();
        if ($id) {
            $this->movement_id = $id;
            $row = Movement::findOrFail($id);

            $this->finance_settings_id = $row->finance_settings_id;
            $this->movement_date       = optional($row->movement_date)->toDateString();
            $this->direction           = $row->direction;
            $this->amount              = $row->amount;
            $this->currency_id         = $row->currency_id;
            $this->method              = $row->method;
            $this->doc_no              = $row->doc_no;
            $this->reference           = $row->reference;
            $this->status              = $row->status;
            $this->notes               = $row->getTranslations('notes') ?: $this->notes;
        }
    }

    public function save()
    {
        $this->validate();

        $payload = [
            'finance_settings_id' => $this->finance_settings_id,
            'movement_date'       => $this->movement_date,
            'direction'           => $this->direction,
            'amount'              => $this->amount,
            'currency_id'         => $this->currency_id,
            'method'              => $this->method,
            'doc_no'              => $this->doc_no,
            'reference'           => $this->reference,
            'status'              => $this->status,
            'notes'               => $this->notes,
            'updated_by'          => Auth::id(),
        ];

        if ($this->movement_id) {
            $row = Movement::findOrFail($this->movement_id);
            $row->update($payload);
        } else {
            $payload['created_by'] = Auth::id();
            $row = Movement::create($payload);
            $this->movement_id = $row->id;
        }

        session()->flash('success', __('pos.msg_updated_success'));
        return redirect()->route('finance.movements');
    }

    public function render()
    {
        return view('livewire.finance-movements.manage', [
            'cashboxes' => Settings::orderBy('id')->get(),
        ]);
    }
}
