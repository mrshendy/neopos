<?php

namespace App\Http\Livewire\General\Branches;

use Livewire\Component;
use Livewire\WithPagination;
use App\models\General\Branch;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $status = ''; // '' الكل، 1 فعّال، 0 غير فعّال
    public $perPage = 10;

    protected $queryString = ['search', 'status', 'page'];

    protected $listeners = ['confirmDelete' => 'delete'];

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatus() { $this->resetPage(); }

    public function delete($id)
    {
        $branch = Branch::findOrFail($id);
        $branch->delete();
        session()->flash('success', 'تم حذف الفرع بنجاح');
        $this->resetPage();
    }

    public function render()
    {
        $q = Branch::query()
            ->when(strlen($this->search), function ($q) {
                $s = "%{$this->search}%";
                $q->where(function ($qq) use ($s) {
                    $qq->where('name', 'like', $s)
                       ->orWhere('address', 'like', $s);
                });
            })
            ->when($this->status !== '' && $this->status !== null, function ($q) {
                $q->where('status', (int)$this->status);
            })
            ->orderByDesc('id');

        return view('livewire.general.branches.index', [
            'rows' => $q->paginate($this->perPage),
        ]);
    }
}
