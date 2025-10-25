<?php

namespace App\Http\Livewire\Customer;

use Livewire\Component;
use Livewire\WithPagination;
use App\models\customer\customer as Customer;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = '';
    public ?string $date_from = null;
    public ?string $date_to = null;
    public int $perPage = 10;

    protected $listeners = ['deleteConfirmed' => 'delete'];

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatus() { $this->resetPage(); }
    public function updatingDateFrom() { $this->resetPage(); }
    public function updatingDateTo() { $this->resetPage(); }
    public function updatingPerPage() { $this->resetPage(); }

    public function toggle(int $id)
    {
        $c = Customer::find($id);
        if (!$c) return;

        $c->status = $c->status === 'active' ? 'inactive' : 'active';
        $c->save();

        session()->flash('success', __('pos.status_changed') ?? 'تم تغيير الحالة');
    }

    public function delete(int $id)
    {
        $c = Customer::find($id);
        if (!$c) { session()->flash('error', __('pos.not_found') ?? 'غير موجود'); return; }

        $c->delete(); // Soft delete
        session()->flash('success', __('pos.deleted_ok') ?? 'تم الحذف بنجاح');
        $this->resetPage();
    }

    public function render()
    {
        $q = Customer::query();

        // بحث
        $s = trim($this->search);
        if ($s !== '') {
            $q->where(function ($w) use ($s) {
                $w->where('code','like',"%{$s}%")
                  ->orWhere('phone','like',"%{$s}%")
                  ->orWhere('mobile','like',"%{$s}%")
                  ->orWhere('email','like',"%{$s}%")
                  ->orWhere('name->ar','like',"%{$s}%")
                  ->orWhere('name->en','like',"%{$s}%");
            });
        }

        // حالة
        if ($this->status !== '') {
            $q->where('status', $this->status);
        }

        // تاريخ
        if ($this->date_from) {
            $q->whereDate('created_at','>=',$this->date_from);
        }
        if ($this->date_to) {
            $q->whereDate('created_at','<=',$this->date_to);
        }

        $q->orderBy('created_at','desc');

        return view('livewire.customer.index', [
            'customers' => $q->paginate($this->perPage),
        ]);
    }
}
