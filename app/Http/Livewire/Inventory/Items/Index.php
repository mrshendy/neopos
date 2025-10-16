<?php

namespace App\Http\Livewire\inventory\items;

use Livewire\Component;
use Livewire\WithPagination;
use App\models\inventory\item;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['deleteConfirmed' => 'delete'];

    // فلاتر وشروط
    protected $queryString = ['page', 'search', 'status', 'track_batch', 'track_serial'];
    public $search = '';
    public $status = '';
    public $track_batch = '';
    public $track_serial = '';

    public function updatingSearch()       { $this->resetPage(); }
    public function updatingStatus()       { $this->resetPage(); }
    public function updatingTrackBatch()   { $this->resetPage(); }
    public function updatingTrackSerial()  { $this->resetPage(); }

    public function render()
    {
        $q = item::query();

        if ($this->search) {
            $q->where(function ($qq) {
                $qq->where('sku', 'like', "%{$this->search}%")
                   ->orWhere('name->ar', 'like', "%{$this->search}%")
                   ->orWhere('name->en', 'like', "%{$this->search}%");
            });
        }

        if ($this->status !== '')       $q->where('status', $this->status);
        if ($this->track_batch !== '')  $q->where('track_batch', (bool)$this->track_batch);
        if ($this->track_serial !== '') $q->where('track_serial', (bool)$this->track_serial);

        $items = $q->orderByDesc('id')->paginate(10);

        return view('livewire.inventory.manage', compact('items'));
    }

    public function toggleStatus($id)
    {
        $it = item::findOrFail($id);
        $it->status = $it->status === 'active' ? 'inactive' : 'active';
        $it->save();

        session()->flash('success', trans('pos.inventory_item_status_changed'));
    }

    public function confirmDelete($id)
    {
        $this->dispatchBrowserEvent('confirm-delete', ['id' => $id]);
    }

    public function delete($id)
    {
        $it = item::findOrFail($id);
        $it->delete();
        session()->flash('success', trans('pos.inventory_deleted_success'));
    }
}
