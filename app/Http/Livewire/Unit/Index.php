<?php

namespace App\Http\Livewire\unit;

use Livewire\Component;
use Livewire\WithPagination;
use App\models\unit\unit;

class index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['deleteConfirmed' => 'delete', 'statusToggled' => '$refresh'];

    // فلاتر
    public $search = '';
    public $filter_level = '';
    public $filter_status = '';

    // حذف
    public $delete_id;

    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterLevel() { $this->resetPage(); }
    public function updatingFilterStatus() { $this->resetPage(); }

    public function confirmDelete($id)
    {
        $this->delete_id = $id;
        // سيتم استدعاء confirmDelete من الواجهة عبر SweetAlert -> ثم emit('deleteConfirmed', $id)
    }

    public function delete($id)
    {
        $rec = unit::findOrFail($id);
        $rec->delete();
        session()->flash('success', __('pos.msg_deleted'));
        $this->reset('delete_id');
    }

    public function toggleStatus($id)
    {
        $rec = unit::findOrFail($id);
        $rec->status = $rec->status === 'active' ? 'inactive' : 'active';
        $rec->save();
        session()->flash('success', __('pos.msg_status_toggled'));
        $this->emit('statusToggled');
    }

    public function resetFilters()
    {
        $this->reset(['search','filter_level','filter_status']);
    }

    public function render()
    {
        $units = unit::query()
            ->search($this->search)
            ->level($this->filter_level)
            ->status($this->filter_status)
            ->orderByDesc('id')
            ->paginate(10);

        return view('livewire.unit.index', compact('units'));
    }
}
