<?php

namespace App\Http\Livewire\inventory\warehouses;

use Livewire\Component;
use Livewire\WithPagination;
use App\models\inventory\warehouse;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['deleteConfirmed' => 'delete'];

    protected $queryString = ['page', 'search', 'status'];
    public $search = '';
    public $status = '';

    public function updatingSearch(){ $this->resetPage(); }
    public function updatingStatus(){ $this->resetPage(); }

    public function render()
    {
        $q = warehouse::query();

        if ($this->search) {
            $q->where(function ($qq) {
                $qq->where('code','like',"%{$this->search}%")
                   ->orWhere('name->ar','like',"%{$this->search}%")
                   ->orWhere('name->en','like',"%{$this->search}%");
            });
        }

        if ($this->status !== '') $q->where('status', $this->status);

        $warehouses = $q->orderByDesc('id')->paginate(10);

        return view('livewire.inventory.warehouses.index', compact('warehouses'));
    }

    public function toggleStatus($id)
    {
        $w = warehouse::findOrFail($id);
        $w->status = $w->status === 'active' ? 'inactive' : 'active';
        $w->save();

        session()->flash('success', trans('pos.saved_success'));
    }

    public function confirmDelete($id)
    {
        $this->dispatchBrowserEvent('confirm-delete', ['id' => $id]);
    }

    public function delete($id)
    {
        $w = warehouse::findOrFail($id);
        $w->delete();
        session()->flash('success', trans('pos.deleted_success'));
    }
}
