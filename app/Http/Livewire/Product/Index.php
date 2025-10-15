<?php

namespace App\Http\Livewire\product;

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

    // فلاتر
    public $search = '';
    public $status = '';
    public $category_id = '';
    public $unit_id = '';

    // اختيار الطباعة
    public $selected = []; // IDs
    public $qty = [];      // [id => int]

    protected $queryString = ['page','search','status','category_id','unit_id'];

    public function updating($field)
    {
        if (in_array($field, ['search','status','category_id','unit_id'])) {
            $this->resetPage();
        }
    }

    public function clearFilters()
    {
        $this->reset(['search','status','category_id','unit_id']);
        $this->resetPage();
    }

    public function delete($id)
    {
        $row = product::findOrFail($id);
        $row->delete();
        session()->flash('success', __('pos.deleted_success') ?? 'تم الحذف بنجاح');
    }

    public function toggleStatus($id)
    {
        $row = product::findOrFail($id);
        $row->status = $row->status === 'active' ? 'inactive' : 'active';
        $row->save();
        session()->flash('success', __('pos.status_changed') ?? 'تم تغيير الحالة');
    }

    protected function baseQuery()
    {
        return product::query()
            ->when($this->search, function($q){
                $s = "%{$this->search}%";
                $q->where(function($qq) use ($s){
                    $qq->where('sku','like',$s)
                       ->orWhere('barcode','like',$s)
                       ->orWhere('name->ar','like',$s)
                       ->orWhere('name->en','like',$s);
                });
            })
            ->when($this->status !== '', fn($q)=>$q->where('status',$this->status))
            ->when($this->category_id, fn($q)=>$q->where('category_id',$this->category_id))
            ->when($this->unit_id, fn($q)=>$q->where('unit_id',$this->unit_id))
            ->orderByDesc('id');
    }

    public function render()
    {
        $rows = $this->baseQuery()->paginate(10);

        // كمية افتراضية = 1
        foreach ($rows as $r) {
            if (!isset($this->qty[$r->id]) || (int)$this->qty[$r->id] < 1) {
                $this->qty[$r->id] = 1;
            }
        }

        return view('livewire.product.index', [
            'rows'       => $rows,
            'categories' => category::orderBy('name->'.app()->getLocale())->get(['id','name']),
            'units'      => unit::orderBy('name->'.app()->getLocale())->get(['id','name']),
        ]);
    }
}
