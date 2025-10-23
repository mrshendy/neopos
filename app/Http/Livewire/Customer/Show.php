<?php

namespace App\Http\Livewire\Customer;

use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use App\models\customer\customer as Customer;

class Show extends Component
{
    public $customerId;   // لا تستخدم $id
    public $row;

    // إحصائيات ملخصة
    public $stats = [
        'sales_count'  => 0,
        'sales_total'  => 0.0,
        'last_sale_at' => null,
    ];

    // آخر فواتير للعرض
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

    /** ترجمة قيمة (تدعم JSON) */
    public function tr($value, $fallback = '—')
    {
        if ($value === null || $value === '') return $fallback;

        if (is_string($value) && Str::startsWith(trim($value), '{')) {
            $a = json_decode($value, true) ?: [];
            return $a[app()->getLocale()] ?? $a['ar'] ?? $a['en'] ?? $fallback;
        }
        return $value;
    }

    /** اختيار أول عمود موجود من قائمة */
    private function pickColumn(string $table, array $list): ?string
    {
        foreach ($list as $c) {
            if (Schema::hasColumn($table, $c)) return $c;
        }
        return null;
    }

    /** تحميل إحصائيات وفواتير العميل اعتمادًا على الأعمدة المتاحة */
    private function loadStats(): void
    {
        $posModel = '\\App\\models\\pos\\pos';
        if (!class_exists($posModel) || !Schema::hasTable('pos')) {
            $this->stats = ['sales_count'=>0,'sales_total'=>0,'last_sale_at'=>null];
            $this->invoices = [];
            return;
        }

        $table     = 'pos';
        $dateCol   = $this->pickColumn($table, ['sale_date','trx_date','pos_date','invoice_date','date','created_at']);
        $totalCol  = $this->pickColumn($table, ['grand_total','total','amount','net_total']);
        $noCol     = $this->pickColumn($table, ['sale_no','code','invoice_no','ref_no','number']);
        $statusCol = $this->pickColumn($table, ['status','state']);

        /** @var \Illuminate\Database\Eloquent\Model $posModel */
        $q = $posModel::query()->where('customer_id', $this->row->id);

        $this->stats['sales_count'] = (int) (clone $q)->count();
        $this->stats['sales_total'] = $totalCol ? (float) (clone $q)->sum($totalCol) : 0;

        $last = (clone $q)
            ->when($dateCol, fn($qq) => $qq->orderByDesc($dateCol), fn($qq) => $qq->orderByDesc('id'))
            ->first();

        $this->stats['last_sale_at'] = $last
            ? ($dateCol ? $last->{$dateCol} : $last->created_at)
            : null;

        $rows = (clone $q)
            ->when($dateCol, fn($qq) => $qq->orderByDesc($dateCol), fn($qq) => $qq->orderByDesc('id'))
            ->limit(10)->get();

        $this->invoices = $rows->map(function ($r) use ($noCol,$dateCol,$statusCol,$totalCol) {
            return [
                'id'          => $r->id,
                'sale_no'     => $noCol     ? ($r->{$noCol} ?? ('#'.$r->id)) : ('#'.$r->id),
                'date'        => $dateCol   ? ($r->{$dateCol} ?? null) : ($r->created_at ?? null),
                'status'      => $statusCol ? ($r->{$statusCol} ?? null) : null,
                'grand_total' => $totalCol  ? (float) ($r->{$totalCol} ?? 0) : 0,
            ];
        })->all();
    }

    public function toggleStatus(): void
    {
        $this->row->status = $this->row->status === 'active' ? 'inactive' : 'active';
        $this->row->save();
        session()->flash('success', __('pos.status_changed'));
        $this->dispatchBrowserEvent('toast', ['type'=>'success','text'=>__('pos.status_changed')]);
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
