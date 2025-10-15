<?php

namespace App\Http\Livewire\Inventory\Units;

use Livewire\Component;
use Livewire\WithPagination;
use App\models\product\unit;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';

    protected $listeners = ['deleteConfirmed' => 'delete'];

    public function updatingSearch(){ $this->resetPage(); }

    public function delete($id)
    {
        if ($u = unit::find($id)) {
            $u->delete();
            session()->flash('success','تم الحذف بنجاح.');
        }
    }

    public function render()
    {
        $q = trim($this->search);

        $majors = unit::majors()
            ->when($q, function($qq) use ($q){
                $qq->where('code','like',"%$q%")
                   ->orWhere('name->ar','like',"%$q%")
                   ->orWhere('name->en','like',"%$q%");
            })
            ->with(['minors' => function($q2){
                $q2->orderByDesc('is_default_minor')->orderBy('code');
            }])
            ->orderBy('code')
            ->paginate(10);

        return view('livewire.inventory.units.index', compact('majors'));
    }
}
