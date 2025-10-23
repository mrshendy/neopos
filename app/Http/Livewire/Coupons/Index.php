<?php

namespace App\Http\Livewire\coupons;

use Livewire\Component;
use Livewire\WithPagination;
use App\models\offers\coupons as Coupon;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['deleteConfirmed' => 'delete'];

    public $search=''; public $status=''; public $type='';
    public $date_from=null; public $date_to=null;
    public $toDeleteId;

    public function updating($f){ if(in_array($f,['search','status','type','date_from','date_to'])) $this->resetPage(); }

    public function confirmDelete($id)
    {
        $this->toDeleteId = $id;
        $this->dispatchBrowserEvent('confirm-delete', ['id'=>$id]);
    }

    public function delete($id)
    {
        if($c = Coupon::find($id)){
            $c->delete();
            session()->flash('success', trans('pos.msg_deleted_ok'));
        }
    }

    public function toggleStatus($id)
    {
        $c = Coupon::findOrFail($id);
        // لا نعيد "expired" إلى active — تبديل بين active/paused فقط
        if ($c->status === 'active') $c->status = 'paused';
        elseif ($c->status === 'paused') $c->status = 'active';
        $c->save();
        session()->flash('success', trans('pos.msg_status_changed'));
    }

    public function render()
    {
        $q = Coupon::query()
            ->when($this->search, fn($qq)=>$qq
                ->where('code','like',"%{$this->search}%")
                ->orWhere('name->'.app()->getLocale(),'like',"%{$this->search}%"))
            ->when($this->status, fn($qq)=>$qq->where('status',$this->status))
            ->when($this->type, fn($qq)=>$qq->where('type',$this->type))
            ->when($this->date_from, fn($qq)=>$qq->whereDate('start_at','>=',$this->date_from))
            ->when($this->date_to, fn($qq)=>$qq->whereDate('end_at','<=',$this->date_to))
            ->latest('id');

        return view('livewire.coupons.index', ['coupons'=>$q->paginate(10)]);
    }
}
