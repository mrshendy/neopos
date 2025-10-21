<?php

namespace App\Http\Livewire\finance;

use Livewire\Component;
use App\models\finance\finance as FinanceModel;
use Illuminate\Support\Facades\Auth;

class Manage extends Component
{
    public $finance_id;

    public $name = ['ar' => '', 'en' => ''];
    public $branch_id;
    public $currency_id;
    public $receipt_prefix = 'CBX';
    public $next_number = 1;
    public $allow_negative = false;
    public $status = 'active';
    public $notes = ['ar' => '', 'en' => ''];

    protected $listeners = ['deleteConfirmed' => 'delete'];

    protected $rules = [
        'name.ar'        => 'required|string|min:2|max:255',
        'name.en'        => 'required|string|min:2|max:255',
        'branch_id'      => 'nullable|integer',
        'currency_id'    => 'nullable|integer',
        'receipt_prefix' => 'required|string|min:1|max:10',
        'next_number'    => 'required|integer|min:1',
        'allow_negative' => 'boolean',
        'status'         => 'required|in:active,inactive',
        'notes.ar'       => 'nullable|string|max:1000',
        'notes.en'       => 'nullable|string|max:1000',
    ];

    protected $messages = [
        'name.ar.required' => 'الاسم بالعربية مطلوب.',
        'name.en.required' => 'الاسم بالإنجليزية مطلوب.',
        'receipt_prefix.required' => 'بادئة الترقيم مطلوبة.',
        'next_number.required' => 'أول رقم متسلسل مطلوب.',
        'next_number.min' => 'أقل قيمة للترقيم هي 1.',
        'status.in' => 'قيمة الحالة غير صحيحة.',
    ];

    public function mount($id = null)
    {
        if ($id) {
            $this->finance_id = $id;
            $row = FinanceModel::findOrFail($id);

            $this->name         = $row->getTranslations('name');
            $this->branch_id    = $row->branch_id;
            $this->currency_id  = $row->currency_id;
            $this->receipt_prefix = $row->receipt_prefix;
            $this->next_number  = $row->next_number;
            $this->allow_negative = (bool)$row->allow_negative;
            $this->status       = $row->status;
            $this->notes        = $row->getTranslations('notes') ?: $this->notes;
        }
    }

    public function save()
    {
        $this->validate();

        $payload = [
            'name'           => $this->name,
            'branch_id'      => $this->branch_id,
            'currency_id'    => $this->currency_id,
            'receipt_prefix' => $this->receipt_prefix,
            'next_number'    => $this->next_number,
            'allow_negative' => $this->allow_negative,
            'status'         => $this->status,
            'notes'          => $this->notes,
            'updated_by'     => Auth::id(),
        ];

        if ($this->finance_id) {
            $row = FinanceModel::findOrFail($this->finance_id);
            $row->update($payload);
            session()->flash('success', __('pos.msg_updated_success'));
            return redirect()->route('finance.index');
        } else {
            $payload['created_by'] = Auth::id();
            $row = FinanceModel::create($payload);
            session()->flash('success', __('pos.msg_created_success'));
            return redirect()->route('finance.index');
        }
    }

    public function render()
    {
        return view('livewire.finance.manage');
    }
}
