<?php

namespace App\Http\Livewire\FinanceSettings;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\models\finance\finance_settings as Settings;
use App\models\finance\finance_settings_user_limit as UserLimit;
use App\User;

class Manage extends Component
{
    public $settings_id;

    // أساسية / مترجمة
    public $name = ['ar' => '', 'en' => ''];
    public $notes = ['ar' => '', 'en' => ''];

    // إعدادات الخزينة
    public $cashbox_type = 'sub'; // main | sub
    public $currency_id;

    // مفاتيح التحكم
    public $is_available = true;
    public $allow_negative_stock = false;

    // سياسات الارتجاع
    public $return_window_days = 14;
    public $require_return_approval = false;
    public $approval_over_amount; // nullable

    // ترقيم الارتجاع
    public $receipt_prefix = 'RET';
    public $next_return_number = 1;

    // حدود المستخدمين (صفوف ديناميكية)
    public $userLimits = []; // كل صف: ['id'?, 'user_id', 'daily_count_limit', 'daily_amount_limit', 'require_supervisor', 'active']

    // لعرض أسماء المستخدمين في الدروب منيو
    public $users = [];

    protected $rules = [
        'name.ar' => 'required|string|min:2|max:255',
        'name.en' => 'required|string|min:2|max:255',

        'cashbox_type' => 'required|in:main,sub',
        'currency_id'  => 'nullable|integer',

        'is_available'         => 'boolean',
        'allow_negative_stock' => 'boolean',

        'return_window_days'      => 'required|integer|min:0|max:365',
        'require_return_approval' => 'boolean',
        'approval_over_amount'    => 'nullable|numeric|min:0',

        'receipt_prefix'     => 'required|string|min:1|max:12',
        'next_return_number' => 'required|integer|min:1',

        'notes.ar' => 'nullable|string|max:1000',
        'notes.en' => 'nullable|string|max:1000',

        'userLimits.*.user_id'            => 'nullable|integer|exists:users,id',
        'userLimits.*.daily_count_limit'  => 'nullable|integer|min:0',
        'userLimits.*.daily_amount_limit' => 'nullable|numeric|min:0',
        'userLimits.*.require_supervisor' => 'boolean',
        'userLimits.*.active'             => 'boolean',
    ];

    protected $messages = [
        'name.ar.required' => 'الاسم بالعربية مطلوب.',
        'name.en.required' => 'الاسم بالإنجليزية مطلوب.',

        'cashbox_type.required' => 'نوع الخزينة مطلوب.',
        'cashbox_type.in'       => 'نوع الخزينة يجب أن يكون رئيسية أو فرعية.',

        'return_window_days.required' => 'مهلة الارتجاع مطلوبة.',
        'receipt_prefix.required'     => 'بادئة ترقيم الارتجاع مطلوبة.',
        'next_return_number.required' => 'أول رقم تسلسلي للارتجاع مطلوب.',

        'userLimits.*.user_id.exists' => 'المستخدم غير موجود.',
    ];

    public function mount($id = null)
    {
        // تحميل المستخدمين للدروب منيو
        $this->users = User::orderBy('name')->get(['id', 'name']);

        if ($id) {
            $this->settings_id = $id;

            $row = Settings::with('userLimits')->findOrFail($id);

            // مترجمات
            $this->name  = $row->getTranslations('name');
            $this->notes = $row->getTranslations('notes') ?: $this->notes;

            // إعدادات
            $this->cashbox_type         = $row->cashbox_type ?? 'sub';
            $this->currency_id          = $row->currency_id;
            $this->is_available         = (bool) $row->is_available;
            $this->allow_negative_stock = (bool) $row->allow_negative_stock;

            // ارتجاع
            $this->return_window_days      = (int) $row->return_window_days;
            $this->require_return_approval = (bool) $row->require_return_approval;
            $this->approval_over_amount    = $row->approval_over_amount;

            // ترقيم
            $this->receipt_prefix     = $row->receipt_prefix;
            $this->next_return_number = (int) $row->next_return_number;

            // حدود المستخدمين
            $this->userLimits = $row->userLimits->map(function ($u) {
                return [
                    'id'                   => $u->id,
                    'user_id'              => $u->user_id,
                    'daily_count_limit'    => $u->daily_count_limit,
                    'daily_amount_limit'   => $u->daily_amount_limit,
                    'require_supervisor'   => (bool) $u->require_supervisor,
                    'active'               => (bool) $u->active,
                ];
            })->toArray();
        }
    }

    public function addUserLimit()
    {
        $this->userLimits[] = [
            'user_id'            => null,
            'daily_count_limit'  => null,
            'daily_amount_limit' => null,
            'require_supervisor' => false,
            'active'             => true,
        ];
    }

    public function removeUserLimit($index)
    {
        unset($this->userLimits[$index]);
        $this->userLimits = array_values($this->userLimits);
    }

    public function save()
    {
        $this->validate();

        $payload = [
            'name'                   => $this->name,   // Spatie Translatable
            'cashbox_type'           => $this->cashbox_type,
            'currency_id'            => $this->currency_id,
            'is_available'           => (bool) $this->is_available,
            'allow_negative_stock'   => (bool) $this->allow_negative_stock,
            'return_window_days'     => (int) $this->return_window_days,
            'require_return_approval'=> (bool) $this->require_return_approval,
            'approval_over_amount'   => $this->approval_over_amount,
            'receipt_prefix'         => $this->receipt_prefix,
            'next_return_number'     => (int) $this->next_return_number,
            'notes'                  => $this->notes,  // Spatie Translatable
            'updated_by'             => Auth::id(),
        ];

        if ($this->settings_id) {
            $row = Settings::findOrFail($this->settings_id);
            $row->update($payload);
        } else {
            $payload['created_by'] = Auth::id();
            $row = Settings::create($payload);
            $this->settings_id = $row->id;
        }

        // مزامنة حدود المستخدمين
        $existing = UserLimit::where('finance_settings_id', $this->settings_id)->get()->keyBy('id');

        foreach ($this->userLimits as $item) {
            // تجاهل الصفوف الفارغة تمامًا
            $isBlank = empty($item['user_id'])
                && empty($item['daily_count_limit'])
                && empty($item['daily_amount_limit']);

            if ($isBlank) {
                // لو كان له id في الداتا بيز وتركناه فارغ نعتبره حذف
                if (!empty($item['id']) && $existing->has($item['id'])) {
                    $existing[$item['id']]->delete();
                    $existing->forget($item['id']);
                }
                continue;
            }

            if (!empty($item['id']) && $existing->has($item['id'])) {
                $existing[$item['id']]->update([
                    'user_id'             => $item['user_id'],
                    'daily_count_limit'   => $item['daily_count_limit'],
                    'daily_amount_limit'  => $item['daily_amount_limit'],
                    'require_supervisor'  => (bool) ($item['require_supervisor'] ?? false),
                    'active'              => (bool) ($item['active'] ?? true),
                ]);
                $existing->forget($item['id']);
            } else {
                UserLimit::create([
                    'finance_settings_id' => $this->settings_id,
                    'user_id'             => $item['user_id'],
                    'daily_count_limit'   => $item['daily_count_limit'],
                    'daily_amount_limit'  => $item['daily_amount_limit'],
                    'require_supervisor'  => (bool) ($item['require_supervisor'] ?? false),
                    'active'              => (bool) ($item['active'] ?? true),
                ]);
            }
        }

        // أي عناصر متبقية لم تُستخدم تعتبر محذوفة
        foreach ($existing as $left) {
            $left->delete();
        }

        session()->flash('success', __('pos.msg_saved_finset'));
        return redirect()->route('finance_settings.index');
    }

    public function render()
    {
        return view('livewire.finance-settings.manage', [
            'users' => $this->users, // لعرض الأسماء في الدروب منيو
        ]);
    }
}
