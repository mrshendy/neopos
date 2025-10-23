<?php

namespace App\Http\Livewire\FinanceHandovers;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\models\finance\finance_handover as Handover;   // عدّل لو اسم موديلك مختلف
use App\models\finance\finance_settings as Settings;   // عدّل لو اسم موديلك مختلف
use App\User;
use Carbon\Carbon;

class Manage extends Component
{
    public $handover_id;

    // من خزنة -> إلى خزنة
    public $from_finance_settings_id;
    public $to_finance_settings_id;

    // تاريخ فقط (افتراضي اليوم)
    public $handover_date;

    // مبالغ
    public $amount_expected = 0; // داخلي/اختياري
    public $amount_counted  = 0;

    // حالة + رقم + ملاحظات
    public $status = 'draft';
    public $doc_no;
    public $notes = ['ar' => '', 'en' => ''];

    // توافق قديم لو عمود finance_settings_id موجود
    public $finance_settings_id;

    // القوائم
    public $cashboxes; // Collection
    public $users;     // Collection

    protected $rules = [
        'from_finance_settings_id' => 'required|integer|different:to_finance_settings_id',
        'to_finance_settings_id'   => 'required|integer',
        'handover_date'            => 'required|date',

        'amount_expected' => 'nullable|numeric|min:0',
        'amount_counted'  => 'required|numeric|min:0',

        'status' => 'required|in:draft,submitted,received,rejected',
        'doc_no' => 'nullable|string|max:50',

        'notes.ar' => 'nullable|string|max:1000',
        'notes.en' => 'nullable|string|max:1000',

        'delivered_by_user_id' => 'nullable|integer|exists:users,id',
        'received_by_user_id'  => 'nullable|integer|exists:users,id',
    ];

    protected $messages = [
        'from_finance_settings_id.required' => 'الخزينة المرسِلة مطلوبة.',
        'to_finance_settings_id.required'   => 'الخزينة المستلِمة مطلوبة.',
        'from_finance_settings_id.different'=> 'لا يجب أن تكون الخزنة المرسِلة هي نفسها المستلمة.',
        'handover_date.required'            => 'تاريخ التسليم مطلوب.',
        'amount_counted.required'           => 'المبلغ المحصّل مطلوب.',
        'status.required'                   => 'حالة التسليم مطلوبة.',
    ];

    /** الفرق (معلوماتي) */
    public function getDifferenceProperty()
    {
        return (float)($this->amount_counted - ($this->amount_expected ?? 0));
    }

    /** رصيد خزنة "من" الحالي محسوب من الإيصالات غير الملغاة والتحويلات الداخل/الخارج */
    public function getFromBalanceProperty()
    {
        $cbId = (int) $this->from_finance_settings_id;
        if (!$cbId) return 0.0;

        // إيصالات نشطة: amount_total - return_amount
        $sumReceipts = (float) DB::table('finance_receipts')
            ->whereNull('deleted_at')
            ->where('finance_settings_id', $cbId)
            ->where('status', '!=', 'canceled')
            ->selectRaw('COALESCE(SUM(amount_total - COALESCE(return_amount,0)),0) as s')
            ->value('s');

        // تحويلات داخلة
        $sumIn = (float) DB::table('finance_handovers')
            ->whereNull('deleted_at')
            ->where('target_finance_settings_id', $cbId)
            ->whereIn('status', ['submitted','received'])
            ->selectRaw('COALESCE(SUM(amount_counted),0) as s')
            ->value('s');

        // تحويلات خارجة
        $sumOut = (float) DB::table('finance_handovers')
            ->whereNull('deleted_at')
            ->where('source_finance_settings_id', $cbId)
            ->whereIn('status', ['submitted','received'])
            ->selectRaw('COALESCE(SUM(amount_counted),0) as s')
            ->value('s');

        return round($sumReceipts + $sumIn - $sumOut, 2);
    }

    public function mount($id = null)
    {
        // كل الخزائن (id, name, currency_id)
        $this->cashboxes = Settings::query()
            ->select('id', 'name', 'currency_id')
            ->orderBy('id')
            ->get();

        // المستخدمون
        $this->users = User::orderBy('name')->get();

        // تاريخ اليوم افتراضي
        $this->handover_date = now()->toDateString();

        if ($id) {
            $this->handover_id = $id;
            $row = Handover::findOrFail($id);

            $this->from_finance_settings_id = $row->source_finance_settings_id;
            $this->to_finance_settings_id   = $row->target_finance_settings_id;
            $this->handover_date            = optional($row->handover_date)?->format('Y-m-d') ?? $this->handover_date;

            $this->amount_expected = (float) ($row->amount_expected ?? 0);
            $this->amount_counted  = (float) $row->amount_counted;

            $this->status = $row->status;
            $this->doc_no = $row->doc_no;
            $this->notes  = $row->getTranslations('notes') ?: $this->notes;

            $this->delivered_by_user_id = $row->delivered_by_user_id;
            $this->received_by_user_id  = $row->received_by_user_id;

            $this->finance_settings_id  = $row->finance_settings_id ?? $this->to_finance_settings_id;
        } else {
            $this->finance_settings_id = $this->to_finance_settings_id;
        }
    }

    /** عند تغيير "إلى خزنة" نخلي متغير التوافق يساويها */
    public function updatedToFinanceSettingsId($value = null)
    {
        $this->finance_settings_id = $this->to_finance_settings_id;
    }

    /** زر التبديل */
    public function swapCashboxes()
    {
        [$this->from_finance_settings_id, $this->to_finance_settings_id] =
            [$this->to_finance_settings_id, $this->from_finance_settings_id];

        $this->updatedToFinanceSettingsId();
    }

    public function save()
    {
        $this->validate();

        $payload = [
            'source_finance_settings_id' => $this->from_finance_settings_id,
            'target_finance_settings_id' => $this->to_finance_settings_id,
            'handover_date'              => $this->handover_date ? Carbon::parse($this->handover_date)->startOfDay() : null,

            'amount_expected' => $this->amount_expected ?: 0,
            'amount_counted'  => $this->amount_counted,

            'status' => $this->status,
            'doc_no' => $this->doc_no,
            'notes'  => $this->notes,

            // توافق قديم لو العمود موجود
            'finance_settings_id' => $this->to_finance_settings_id,

            'delivered_by_user_id' => $this->delivered_by_user_id,
            'received_by_user_id'  => $this->received_by_user_id,
        ];

        if ($this->handover_id) {
            $row = Handover::findOrFail($this->handover_id);
            $row->update($payload);
        } else {
            $payload['created_by'] = Auth::id();
            $row = Handover::create($payload);
            $this->handover_id = $row->id;
        }

        session()->flash('success', __('pos.msg_saved') ?? 'تم الحفظ بنجاح.');
        return redirect()->route('finance.handovers');
    }

    public function render()
    {
        return view('livewire.finance-handovers.manage', [
            'cashboxes'            => $this->cashboxes,
            'users'                => $this->users,
            'difference'           => $this->difference,
            'fromBalance'          => $this->fromBalance,
            // تمرير صريح لتفادي أي Undefined في Blade
            'from_finance_settings_id' => $this->from_finance_settings_id,
            'to_finance_settings_id'   => $this->to_finance_settings_id,
            'status'               => $this->status,
        ]);
    }
}
