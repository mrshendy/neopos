<?php

namespace App\Http\Livewire\supplier;

use Livewire\Component;
use Livewire\WithPagination;
use App\models\supplier\supplier;
use App\models\supplier\suppliercategory;
use App\models\governorate;
use App\models\city;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['deleteConfirmed' => 'delete'];

    public $search = '';
    public $filter_category = '';
    public $filter_governorate = '';
    public $filter_city = '';
    public $filter_status = '';

    public function updatingSearch(){ $this->resetPage(); }
    public function updatingFilterCategory(){ $this->resetPage(); }
    public function updatingFilterGovernorate(){ $this->filter_city = ''; $this->resetPage(); }
    public function updatingFilterStatus(){ $this->resetPage(); }

    public function toggleStatus($id){
        $s = supplier::findOrFail($id);
        $s->status = $s->status === 'active' ? 'inactive' : 'active';
        $s->save();
        session()->flash('success', __('pos.status_changed'));
    }

    public function delete($id){
        $s = supplier::findOrFail($id);
        $s->delete();
        session()->flash('success', __('pos.deleted_success'));
        $this->resetPage();
    }

    public function render(){
        $q = supplier::query()
            ->with(['category','governorate','city']) // ✅ تحميل العلاقات مسبقًا
            ->when($this->search, function($q){
                $q->where('code','like',"%{$this->search}%")
                  ->orWhere('name->ar','like',"%{$this->search}%")
                  ->orWhere('name->en','like',"%{$this->search}%");
            })
            ->when($this->filter_category, fn($q)=>$q->where('supplier_category_id',$this->filter_category))
            ->when($this->filter_governorate, fn($q)=>$q->where('governorate_id',$this->filter_governorate))
            ->when($this->filter_city, fn($q)=>$q->where('city_id',$this->filter_city))
            ->when($this->filter_status, fn($q)=>$q->where('status',$this->filter_status))
            ->latest();

        return view('livewire.supplier.index', [
            'suppliers'    => $q->paginate(10),
            'categories'   => suppliercategory::orderBy('id','desc')->get(),
            'governorates' => governorate::orderBy('id','asc')->get(),
            'cities'       => $this->filter_governorate
                                    ? city::where('governorate_id',$this->filter_governorate)->orderBy('id','asc')->get()
                                    : city::orderBy('id','asc')->limit(50)->get(),
        ]);
    }
}
