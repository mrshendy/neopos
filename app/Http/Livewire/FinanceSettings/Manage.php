<?php

namespace App\Http\Livewire\FinanceSettings;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\models\finance\finance_settings as Settings;
use App\models\finance\finance_settings_user_limit as UserLimit;

class Manage extends Component
{
    public $settings_id;

    public $name = ['ar'=>'','en'=>''];
    public $branch_id;
    public $warehouse_id;
    public $currency_id;

    public $is_available = true;
    public $allow_negative_stock = false;

    public $return_window_days = 14;
    public $require_return_approval = false;
    public $approval_over_amount;

    public $receipt_prefix = 'RET';
    public $next_return_number = 1;

    public $notes = ['ar'=>'','en'=>''];

    // حدود المستخدمين (تكرارية)
    public $userLimits = [
        // ['user_id'=>null,'daily_count_limit'=>null,'daily_amount_limit'=>null,'require_supervisor'=>false,'active'=>true],
    ];

    protected $rules = [
        'name.ar' => 'required|string|min:2|max:255',
        'name.en' => 'required|string|min:2|max:255',

        'branch_id'   => 'nullable|integer',
        'warehouse_id'=> 'nullable|integer',
        'currency_id' => 'nullable|integer',

        'is_available'          => 'boolean',
        'allow_negative_stock'  => 'boolean',

        'return_window_days'    => 'required|integer|min:0|max:365',
        'require_return_approval' => 'boolean',
        'approval_over_amount'  => 'nullable|numeric|min:0',

        'receipt_prefix'        => 'required|string|min:1|max:12',
        'next_return_number'    => 'required|integer|min:1',

        'notes.ar'              => 'nullable|string|max:1000',
        'notes.en'              => 'nullable|string|max:1000',

        'userLimits.*.user_id'            => 'nullable|integer',
        'userLimits.*.daily_count_limit'  => 'nullable|integer|min:0',
        'userLimits.*.daily_amount_limit' => 'nullable|numeric|min:0',
        'userLimits.*.require_supervisor' => 'boolean',
        'userLimits.*.active'             => 'boolean',
    ];

    protected $messages = [
        'name.ar.required' => 'الاسم بالعربية مطلوب.',
        'name.en.required' => 'الاسم بالإنجليزية مطلوب.',
        'return_window_days.required' => 'مهلة الارتجاع مطلوبة.',
        'receipt_prefix.required' => 'بادئة ترقيم الارتجاع مطلوبة.',
        'next_return_number.required' => 'أول رقم تسلسلي للارتجاع مطلوب.',
    ];

    public function mount($id = null)
    {
        if ($id) {
            $this->settings_id = $id;
            $row = Settings::with('userLimits')->findOrFail($id);

            $this->name          = $row->getTranslations('name');
            $this->branch_id     = $row->branch_id;
            $this->warehouse_id  = $row->warehouse_id;
            $this->currency_id   = $row->currency_id;

            $this->is_available        = (bool)$row->is_available;
            $this->allow_negative_stock= (bool)$row->allow_negative_stock;

            $this->return_window_days      = $row->return_window_days;
            $this->require_return_approval = (bool)$row->require_return_approval;
            $this->approval_over_amount    = $row->approval_over_amount;

            $this->receipt_prefix      = $row->receipt_prefix;
            $this->next_return_number  = $row->next_return_number;

            $this->notes = $row->getTranslations('notes') ?: $this->notes;

            $this->userLimits = $row->userLimits->map(function($u){
                return [
                    'id' => $u->id,
                    'user_id' => $u->user_id,
                    'daily_count_limit' => $u->daily_count_limit,
                    'daily_amount_limit'=> $u->daily_amount_limit,
                    'require_supervisor'=> (bool)$u->require_supervisor,
                    'active' => (bool)$u->active,
                ];
            })->toArray();
        }
    }

    public function addUserLimit()
    {
        $this->userLimits[] = ['user_id'=>null,'daily_count_limit'=>null,'daily_amount_limit'=>null,'require_supervisor'=>false,'active'=>true];
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
            'name' => $this->name,
            'branch_id' => $this->branch_id,
            'warehouse_id' => $this->warehouse_id,
            'currency_id' => $this->currency_id,

            'is_available' => $this->is_available,
            'allow_negative_stock' => $this->allow_negative_stock,

            'return_window_days' => $this->return_window_days,
            'require_return_approval' => $this->require_return_approval,
            'approval_over_amount' => $this->approval_over_amount,

            'receipt_prefix' => $this->receipt_prefix,
            'next_return_number' => $this->next_return_number,

            'notes' => $this->notes,
            'updated_by' => Auth::id(),
        ];

        if ($this->settings_id) {
            $row = Settings::findOrFail($this->settings_id);
            $row->update($payload);
        } else {
            $payload['created_by'] = Auth::id();
            $row = Settings::create($payload);
            $this->settings_id = $row->id;
        }

        // Sync user limits (بسيطة)
        $existing = UserLimit::where('finance_settings_id', $this->settings_id)->get()->keyBy('id');

        foreach ($this->userLimits as $item) {
            if (!empty($item['id']) && $existing->has($item['id'])) {
                $existing[$item['id']]->update([
                    'user_id' => $item['user_id'],
                    'daily_count_limit' => $item['daily_count_limit'],
                    'daily_amount_limit'=> $item['daily_amount_limit'],
                    'require_supervisor'=> (bool)$item['require_supervisor'],
                    'active' => (bool)$item['active'],
                ]);
                $existing->forget($item['id']);
            } else {
                UserLimit::create([
                    'finance_settings_id' => $this->settings_id,
                    'user_id' => $item['user_id'],
                    'daily_count_limit' => $item['daily_count_limit'],
                    'daily_amount_limit'=> $item['daily_amount_limit'],
                    'require_supervisor'=> (bool)$item['require_supervisor'],
                    'active' => (bool)$item['active'],
                ]);
            }
        }
        // أي عناصر متبقية تُحذف (تمت إزالتها من الواجهة)
        foreach ($existing as $left) { $left->delete(); }

        session()->flash('success', __('pos.msg_saved_finset'));
        return redirect()->route('finance_settings.index');
    }

    public function render()
    {
        return view('livewire.finance-settings.manage');
    }
}
