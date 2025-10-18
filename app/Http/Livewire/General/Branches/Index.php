<?php

namespace App\Http\Livewire\general\branches;

use Livewire\Component;
use Livewire\WithPagination;
use App\models\general\branch;

class index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $statusOptions = ['active' => 'نشط', 'inactive' => 'متوقف'];
    public $status = '';

    protected $queryString = ['search', 'status', 'page'];

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatus() { $this->resetPage(); }

    public function render()
    {
        $branches = branch::withCount('warehouses')
            ->when($this->search, function ($q) {
                $s = "%{$this->search}%";
                $q->where('name->ar', 'like', $s)
                  ->orWhere('name->en', 'like', $s)
                  ->orWhere('address', 'like', $s);
            })
            ->when($this->status !== '', fn($q) => $q->where('status', $this->status))
            ->orderByDesc('id')
            ->paginate(10);

        return view('general.branches.index', [
            'branches' => $branches,
            'statusOptions' => $this->statusOptions,
        ])->layout('layouts.master');
    }
}
