<?php

namespace App\Http\Livewire\offers;

use Livewire\Component;
use Livewire\WithPagination;
use App\models\offers\offers as Offer;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['deleteConfirmed' => 'delete'];

    public $search = '';
    public $status = '';
    public $type = '';
    public $stackable = '';
    public $date_from = null;
    public $date_to = null;

    public $toDeleteId;

    public function updating($field)
    {
        if (in_array($field, ['search','status','type','stackable','date_from','date_to'])) {
            $this->resetPage();
        }
    }

    public function confirmDelete($id)
    {
        $this->toDeleteId = $id;
        $this->dispatchBrowserEvent('confirm-delete', ['id'=>$id]);
    }

    public function delete($id)
    {
        if ($off = Offer::find($id)) {
            $off->delete();
            session()->flash('success', trans('pos.msg_deleted_ok'));
        }
    }

    public function toggleStatus($id)
    {
        $offer = Offer::findOrFail($id);
        $offer->status = $offer->status === 'active' ? 'paused' : 'active';
        $offer->save();
        session()->flash('success', trans('pos.msg_status_changed'));
    }

    public function render()
    {
        $q = Offer::query()
            ->when($this->search, fn($qq)=>$qq
                ->where('code','like',"%{$this->search}%")
                ->orWhere('name->'.app()->getLocale(),'like',"%{$this->search}%"))
            ->when($this->status, fn($qq)=>$qq->where('status',$this->status))
            ->when($this->type, fn($qq)=>$qq->where('type',$this->type))
            ->when($this->stackable !== '', fn($qq)=>$qq->where('is_stackable', (bool)$this->stackable))
            ->when($this->date_from, fn($qq)=>$qq->whereDate('start_at','>=',$this->date_from))
            ->when($this->date_to, fn($qq)=>$qq->whereDate('end_at','<=',$this->date_to))
            ->latest('id');

        return view('livewire.offers.index', [
            'offers' => $q->paginate(10)
        ]);
    }
}
