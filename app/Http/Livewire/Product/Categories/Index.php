<?php

namespace App\Http\Livewire\Product\Categories;

use Livewire\Component;
use Livewire\WithPagination;
use App\models\product\category;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['deleteConfirmed' => 'delete'];

    public $search = '';
    public $status = '';

    public function updating($field)
    {
        if (in_array($field, ['search', 'status'])) $this->resetPage();
    }

    public function toggleStatus($id)
    {
        $row = category::findOrFail($id);
        $row->status = $row->status === 'active' ? 'inactive' : 'active';
        $row->save();
        session()->flash('success', __('pos.msg_status_changed'));
    }

    public function delete($id)
    {
        $row = category::findOrFail($id);
        $row->delete();
        session()->flash('success', __('pos.msg_deleted_ok'));
    }

    public function render()
    {
        $rows = category::query()
            ->when($this->search, fn($q) =>
                $q->where('name->ar', 'like', "%{$this->search}%")
                  ->orWhere('name->en', 'like', "%{$this->search}%")
            )
            ->when($this->status, fn($q) => $q->where('status', $this->status))
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.product.categories.index', compact('rows'));
    }
}
