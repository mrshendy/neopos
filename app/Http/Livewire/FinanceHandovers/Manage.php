<?php

namespace App\Http\Livewire\FinanceHandovers;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\models\finance\finance_handover as Handover;
use App\models\finance\finance_settings as Settings;
use App\User;

class Manage extends Component
{
    public $handover_id;

    public $finance_settings_id;
    public $handover_date;
    public $doc_no;

    public $amount_expected = 0;
    public $amount_counted  = 0;
    public $difference      = 0;

    public $status = 'draft'; // draft|submitted|received|rejected

    public $delivered_by; // من جدول users
    public $received_by;  // من جدول users (اختياري إلا عند received)

    public $notes = ['ar' => '', 'en' => ''];

    /** لعرض الأسماء في القوائم */
    public $users = [];

    protected $rules = [
        'finance_settings_id' => 'required|integer|exists:finance_settings,id',
        'handover_date'       => 'required|date',
        'doc_no'              => 'nullable|string|max:50',
        'amount_expected'     => 'required|numeric|min:0',
        'amount_counted'      => 'required|numeric|min:0',
        'status'              => 'required|in:draft,submitted,received,rejected',
        'delivered_by'        => 'required|integer|exists:users,id',
        'received_by'         => 'nullable|integer|exists:users,id',
        'notes.ar'            => 'nullable|string|max:1000',
        'notes.en'            => 'nullable|string|max:1000',
    ];

    protected $messages = [
        'finance_settings_id.required' => 'الخزينة مطلوبة.',
        'handover_date.required'       => 'تاريخ التسليم مطلوب.',
        'amount_expected.required'     => 'المبلغ المتوقع مطلوب.',
        'amount_counted.required'      => 'المبلغ المُحصَّل مطلوب.',
        'delivered_by.required'        => 'المستخدم الذي قام بالتسليم مطلوب.',
        'delivered_by.exists'          => 'مستخدم التسليم غير موجود.',
        'received_by.exists'           => 'مستخدم الاستلام غير موجود.',
    ];

    public function mount($id = null)
    {
        // تحميل المستخدمين (لو العدد كبير ممكن تستبدلها بسيرش لاحقًا)
        $this->users = User::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        $this->handover_date = now()->format('Y-m-d\TH:i');
        $this->delivered_by  = Auth::id();

        if ($id) {
            $this->handover_id = $id;
            $row = Handover::findOrFail($id);

            $this->finance_settings_id = $row->finance_settings_id;
            $this->handover_date       = optional($row->handover_date)->format('Y-m-d\TH:i');
            $this->doc_no              = $row->doc_no;
            $this->amount_expected     = $row->amount_expected;
            $this->amount_counted      = $row->amount_counted;
            $this->difference          = $row->difference;
            $this->status              = $row->status;
            $this->delivered_by        = $row->delivered_by ?: $this->delivered_by;
            $this->received_by         = $row->received_by;
            $this->notes               = $row->getTranslations('notes') ?: $this->notes;
        }
    }

    public function updated($name)
    {
        if (in_array($name, ['amount_expected', 'amount_counted'])) {
            $this->difference = (float) $this->amount_counted - (float) $this->amount_expected;
        }
    }

    public function save()
    {
        // حساب الفرق قبل التحقق
        $this->difference = (float) $this->amount_counted - (float) $this->amount_expected;

        // شرط إضافي: لو الحالة received لازم received_by
        if ($this->status === 'received' && empty($this->received_by)) {
            $this->addError('received_by', 'تحديد المستخدم الذي استلم مطلوب عند حالة الاستلام.');
            return;
        }

        $this->validate();

        $payload = [
            'finance_settings_id' => $this->finance_settings_id,
            'handover_date'       => $this->handover_date,
            'doc_no'              => $this->doc_no,
            'amount_expected'     => $this->amount_expected,
            'amount_counted'      => $this->amount_counted,
            'difference'          => $this->difference,
            'status'              => $this->status,
            'delivered_by'        => $this->delivered_by,
            'received_by'         => $this->received_by,
            'notes'               => $this->notes,
            'updated_by'          => Auth::id(),
        ];

        // عند الاستلام سجّل وقت الاستلام إن لم يكن مسجلًا
        if ($this->status === 'received') {
            $payload['received_at'] = now();
        }

        if ($this->handover_id) {
            $row = Handover::findOrFail($this->handover_id);
            $row->update($payload);
        } else {
            $payload['created_by'] = Auth::id();
            $row = Handover::create($payload);
            $this->handover_id = $row->id;
        }

        session()->flash('success', __('pos.msg_updated_success'));
        return redirect()->route('finance.handovers');
    }

    public function render()
    {
        return view('livewire.finance-handovers.manage', [
            'cashboxes' => Settings::orderBy('id')->get(),
            'users'     => $this->users,
        ]);
    }
}
