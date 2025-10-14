<?php

namespace App\Http\Livewire\inventory\settings;

use Livewire\Component;
use App\models\inventory\setting;

class Index extends Component
{
    public $negative_stock_policy = 'block'; // block | warn
    public $expiry_alert_days = 30;
    public $transaction_sequences = ['pattern' => 'INV-TRX-{YYYY}-{####}'];

    protected $rules = [
        'negative_stock_policy'       => 'required|in:block,warn',
        'expiry_alert_days'           => 'required|integer|min:0|max:3650',
        'transaction_sequences'       => 'array',
        'transaction_sequences.pattern' => 'required|string|max:100',
    ];

    protected $messages = [
        'negative_stock_policy.in' => 'سياسة السالب غير صحيحة',
        'expiry_alert_days.min'    => 'عدد الأيام لا يمكن أن يكون سالبًا',
    ];

    public function mount()
    {
        $neg = setting::firstOrCreate(['key' => 'negative_stock_policy'], ['value' => ['policy' => 'block']]);
        $exp = setting::firstOrCreate(['key' => 'expiry_alert_days'], ['value' => ['days' => 30]]);
        $seq = setting::firstOrCreate(['key' => 'transaction_sequences'], ['value' => ['pattern' => 'INV-TRX-{YYYY}-{####}']]);

        $this->negative_stock_policy = $neg->value['policy'] ?? 'block';
        $this->expiry_alert_days     = (int)($exp->value['days'] ?? 30);
        $this->transaction_sequences = ['pattern' => $seq->value['pattern'] ?? 'INV-TRX-{YYYY}-{####}'];
    }

    public function save()
    {
        $this->validate();

        setting::updateOrCreate(['key' => 'negative_stock_policy'], ['value' => ['policy' => $this->negative_stock_policy]]);
        setting::updateOrCreate(['key' => 'expiry_alert_days'], ['value' => ['days' => (int)$this->expiry_alert_days]]);
        setting::updateOrCreate(['key' => 'transaction_sequences'], ['value' => ['pattern' => $this->transaction_sequences['pattern']]]);

        session()->flash('success', trans('pos.saved_success'));
    }

    public function render()
    {
        return view('livewire.inventory.settings.index');
    }
}
