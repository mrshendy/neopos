<?php

namespace App\Http\Livewire\Customer;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\customer\customer;


class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['deleteConfirmed' => 'delete'];

    // فلاتر
    public $search = '';
    public $type = '';
    public $channel = '';
    public $account_status = '';

    protected $queryString = [
        'page', 'search', 'type', 'channel', 'account_status'
    ];

    public function updating($field)
    {
        if (in_array($field, ['search','type','channel','account_status'])) {
            $this->resetPage();
        }
    }

    public function delete($id)
    {
        $row = Customer::findOrFail($id);
        $row->delete();
        session()->flash('success', __('pos.deleted_success') ?? 'تم الحذف بنجاح');
    }

    protected function baseQuery()
    {
        return Customer::query()
            ->when($this->search, function ($q) {
                $s = "%{$this->search}%";
                $q->where(function ($qq) use ($s) {
                    $qq->where('code', 'like', $s)
                       ->orWhere('phone', 'like', $s)
                       ->orWhere('tax_number', 'like', $s)
                       ->orWhere('legal_name->ar', 'like', $s)
                       ->orWhere('legal_name->en', 'like', $s);
                });
            })
            ->when($this->type !== '', fn($q) => $q->where('type', $this->type))
            ->when($this->channel !== '', fn($q) => $q->where('channel', $this->channel))
            ->when($this->account_status !== '', fn($q) => $q->where('account_status', $this->account_status))
            ->orderByDesc('id');
    }

    public function render()
    {
        $rows = $this->baseQuery()->paginate(12);

        return view('customer.index', [
            'rows' => $rows,
        ]);
    }
}
