<?php

namespace App\Http\Livewire\Customer;

use Livewire\Component;
use Livewire\WithPagination;
use App\models\customer\customer;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $account_status = '';
    public $type = '';
    public $perPage = 10;

    protected $updatesQueryString = ['search', 'account_status', 'type', 'page'];

    public function updatingSearch()        { $this->resetPage(); }
    public function updatingAccountStatus() { $this->resetPage(); }
    public function updatingType()          { $this->resetPage(); }
    public function updatedPerPage()        { $this->resetPage(); }

    public function clearFilters()
    {
        $this->reset(['search','account_status','type']);
        $this->perPage = 10;
        $this->resetPage();
    }

    public function getRowsQueryProperty()
    {
        return customer::query()
            ->when($this->search, function ($q) {
                $term = trim($this->search);
                $q->where('code','like',"%{$term}%")
                  ->orWhere('legal_name->ar','like',"%{$term}%")
                  ->orWhere('legal_name->en','like',"%{$term}%")
                  ->orWhere('phone','like',"%{$term}%")
                  ->orWhere('tax_number','like',"%{$term}%");
            })
            ->when($this->account_status, fn($q) => $q->where('account_status', $this->account_status))
            ->when($this->type, fn($q) => $q->where('type', $this->type))
            ->orderByDesc('id');
    }

    public function getRowsProperty()
    {
        return $this->rowsQuery->paginate($this->perPage);
    }

    public function render()
    {
        return view('customers.index', [
            'customers' => $this->rows,
        ]);
    }
}
