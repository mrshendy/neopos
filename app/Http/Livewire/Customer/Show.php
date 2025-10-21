<?php

namespace App\Http\Livewire\Customer;

use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use App\Models\customer\customer as Customer;

class Show extends Component
{
    public $customerId;
    public $row;

    public $stats = [
        'sales_count' => 0,
        'sales_total' => 0.0,
        'last_sale_at' => null,
    ];

    public $invoices = [];

    protected $listeners = [
        'deleteConfirmed' => 'delete',
    ];

    public function mount($customerId)
    {
        $this->customerId = (int) $customerId;
        $this->row = Customer::findOrFail($this->customerId);

        $this->loadStats();
    }

    /** عرض قيمة متوافقة مع الترجمة سواء كانت JSON أو Spatie أو نص عادي */
    public function tr($value, $fallback = '—')
    {
        if (is_null($value) || $value === '') return $fallback;

        // لو الموديل قابل للترجمة عبر spatie
        if (method_exists($this->row, 'getTranslation') && is_string($value)) {
            // في حالة الترجمة محفوظة كسلاسل JSON بالنموذج، سنتعامل معها بالفرع التالي
        }

        // JSON string؟ ({"ar":"...","en":"..."})
        if (is_string($value) && Str::startsWith(trim($value), '{')) {
            $a = json_decode($value, true) ?: [];
            return $a[app()->getLocale()] ?? $a['ar'] ?? $a['en'] ?? $fallback;
        }

        // لو السمة نفسها translatable عبر spatie
        if (is_string($value) === false && is_object($this->row) && method_exists($this->row, 'getTranslation')) {
            // تم تمرير اسم الحقل بدل القيمة
            if (is_string($value)) {
                return $this->row->getTranslation($value, app()->getLocale()) ?: $fallback;
            }
        }

        return $value;
    }

    /** تحميل إحصائيات وفواتير العميل إن وُجد جدول pos */
    private function loadStats(): void
    {
        $posModel = '\\App\\Models\\pos\\pos';

        if (class_exists($posModel) && Schema::hasTable('pos')) {
            /** @var \Illuminate\Database\Eloquent\Model $posModel */
            $posQ = $posModel::query()->where('customer_id', $this->row->id);

            $this->stats['sales_count'] = (int) $posQ->count();
            $this->stats['sales_total'] = (float) $posQ->sum('grand_total');

            $last = (clone $posQ)->orderByDesc('sale_date')->first();
            $this->stats['last_sale_at'] = $last ? ($last->sale_date ?? $last->created_at) : null;

            $this->invoices = (clone $posQ)
                ->orderByDesc('sale_date')
                ->limit(10)
                ->get(['id','sale_no','sale_date','status','grand_total']);
        }
    }

    public function toggleStatus()
    {
        $this->row->status = ($this->row->status === 'active') ? 'inactive' : 'active';
        $this->row->save();

        session()->flash('success', __('pos.status_changed'));
        $this->dispatchBrowserEvent('toast', ['type' => 'success', 'text' => __('pos.status_changed')]);
    }

    public function delete()
    {
        $this->row->delete();
        session()->flash('success', __('pos.deleted_ok'));
        return redirect()->route('customers.index');
    }

    public function render()
    {
        return view('livewire.customer.show');
    }
}
