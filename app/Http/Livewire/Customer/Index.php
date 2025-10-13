<?php

namespace App\Http\Livewire\Customer;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\customer\customer; // غيّرها لو اسم موديلك مختلف

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['deleteConfirmed' => 'delete']; // يشتغل مع SweetAlert

    // فلاتر
    public $search = '';
    public $code = '';
    public $phone = '';
    public $tax = '';
    public $channel = '';
    public $account_status = '';
    public $price_category_id;

    // Toggle status
    public function toggleStatus($id)
    {
        $row = Customer::find($id);
        if (!$row) {
            session()->flash('error', __('pos.err_not_found'));
            return;
        }

        $row->account_status = $row->account_status === 'active' ? 'inactive' : 'active';
        $row->save();

        session()->flash('success', __('pos.status_changed'));
    }

    // الحذف (بيشتغل لما SweetAlert يبعث deleteConfirmed مع id)
    public function delete($id)
    {
        $row = Customer::find($id);
        if (!$row) {
            session()->flash('error', __('pos.err_not_found'));
            return;
        }

        // لو عندك SoftDeletes استخدم $row->delete()
        $row->delete();

        // رجّع لصفحة أولى لو اتفضّت الصفحة الحالية
        if ($this->page > $this->paginator->lastPage()) {
            $this->resetPage();
        }

        session()->flash('success', __('pos.deleted_success'));
    }

    public function updating($field)
    {
        // مع أي تغيير فلتر ارجع لأول صفحة
        $this->resetPage();
    }

    public function getQueryProperty()
    {
        return Customer::query()
            ->with(['cityRel', 'area', 'priceCategory'])
            ->when($this->search, fn($q) => $q->where(function ($x) {
                $x->where('legal_name->ar', 'like', "%{$this->search}%")
                  ->orWhere('legal_name->en', 'like', "%{$this->search}%");
            }))
            ->when($this->code, fn($q) => $q->where('code', 'like', "%{$this->code}%"))
            ->when($this->phone, fn($q) => $q->where('phone', 'like', "%{$this->phone}%"))
            ->when($this->tax, fn($q) => $q->where('tax_number', 'like', "%{$this->tax}%"))
            ->when($this->channel, fn($q) => $q->where('channel', $this->channel))
            ->when($this->account_status, fn($q) => $q->where('account_status', $this->account_status))
            ->when($this->price_category_id, fn($q) => $q->where('price_category_id', $this->price_category_id))
            ->orderByDesc('id');
    }

    public function render()
    {
        $customers = $this->query->paginate(10);
        return view('livewire.customer.index', compact('customers'));
    }
}
