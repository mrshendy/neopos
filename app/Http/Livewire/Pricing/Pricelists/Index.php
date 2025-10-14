<?php

namespace App\Http\Livewire\Pricing\Pricelists;

use Livewire\Component;
use Livewire\WithPagination;
use App\models\pricing\price_list;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['deleteConfirmed' => 'delete'];

    public $search = '';
    public $status = '';

    public function updating($field)
    {
        if (in_array($field, ['search','status'])) $this->resetPage();
    }

    public function delete($id)
    {
        $row = price_list::findOrFail($id);
        $row->delete();
        session()->flash('success', __('pos.msg_deleted_ok'));
    }

    public function toggleStatus($id)
    {
        $row = price_list::findOrFail($id);
        $row->status = $row->status === 'active' ? 'inactive' : 'active';
        $row->save();
        session()->flash('success', __('pos.msg_status_changed'));
    }

    public function render()
    {
        $q = price_list::query()
            ->when($this->search, function($x){
                $x->where('name->ar','like',"%{$this->search}%")
                  ->orWhere('name->en','like',"%{$this->search}%");
            })
            ->when($this->status, fn($x)=>$x->where('status',$this->status))
            ->orderByDesc('id');

        return view('livewire.pricing.pricelists.index', [
            'rows'=>$q->paginate(10)
        ]);
    }
}
