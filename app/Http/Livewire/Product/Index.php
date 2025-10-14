<?php

namespace App\Http\Livewire\Product;

use Livewire\Component;
use Livewire\WithPagination;
use App\models\product\product;
use App\models\product\category;
use App\models\product\unit;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['deleteConfirmed' => 'delete'];

    public $search = '';
    public $status = '';
    public $category_id = '';
    public $unit_id = '';

    public function updating($field){ if(in_array($field,['search','status','category_id','unit_id'])) $this->resetPage(); }

    public function delete($id){
        $row = product::findOrFail($id);
        $row->delete();
        session()->flash('success', __('pos.msg_deleted_ok'));
    }

    public function toggleStatus($id){
        $row = product::findOrFail($id);
        $row->status = $row->status === 'active' ? 'inactive' : 'active';
        $row->save();
        session()->flash('success', __('pos.msg_status_changed'));
    }

    public function render(){
        $query = product::query()
            ->when($this->search, fn($q)=>$q->where(function($qq){
                $qq->where('sku','like',"%{$this->search}%")
                   ->orWhere('barcode','like',"%{$this->search}%")
                   ->orWhere('name->ar','like',"%{$this->search}%")
                   ->orWhere('name->en','like',"%{$this->search}%");
            }))
            ->when($this->status, fn($q)=>$q->where('status',$this->status))
            ->when($this->category_id, fn($q)=>$q->where('category_id',$this->category_id))
            ->when($this->unit_id, fn($q)=>$q->where('unit_id',$this->unit_id))
            ->orderByDesc('id');

        return view('livewire.product.index',[
            'rows' => $query->paginate(10),
            'categories' => category::orderBy('id','desc')->get(['id','name']),
            'units' => unit::orderBy('id','desc')->get(['id','name']),
        ]);
    }
}
