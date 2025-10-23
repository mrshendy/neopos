<?php

namespace App\Http\Livewire\Currencies;

use Livewire\Component;
use Livewire\WithPagination;
use App\models\currencies\currencies as Currency;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $onlyDefault = false;
    public $perPage = 10;

    protected $listeners = [
        'saved' => '$refresh',
        'deleteConfirmed' => 'delete',
        'statusToggled' => '$refresh',
        'setDefaultDone' => '$refresh',
    ];

    public $deleteId = null;

    public function updatingSearch(){ $this->resetPage(); }
    public function updatingStatus(){ $this->resetPage(); }
    public function updatingOnlyDefault(){ $this->resetPage(); }
    public function updatingPerPage(){ $this->resetPage(); }

    public function confirmDelete($id){
        $this->deleteId = (int)$id;
    }

    public function delete($id = null)
    {
        $id = $id ?: $this->deleteId;
        $row = Currency::find($id);
        if(!$row){
            session()->flash('error', __('currencies.msg_not_found'));
            return;
        }
        if($row->is_default){
            session()->flash('error', __('currencies.msg_cannot_delete_default'));
            return;
        }
        $row->delete();
        session()->flash('success', __('currencies.msg_deleted'));
        $this->deleteId = null;
    }

    public function toggleStatus($id)
    {
        $row = Currency::find($id);
        if(!$row){ session()->flash('error', __('currencies.msg_not_found')); return; }
        $row->status = $row->status === 'active' ? 'inactive' : 'active';
        $row->save();
        session()->flash('success', __('currencies.msg_status_changed'));
        $this->emit('statusToggled');
    }

    public function setDefault($id)
    {
        $row = Currency::find($id);
        if(!$row){ session()->flash('error', __('currencies.msg_not_found')); return; }

        \DB::transaction(function() use ($row){
            \DB::table('currencies')->update(['is_default' => false]);
            $row->is_default = true;
            $row->save();
        });

        session()->flash('success', __('currencies.msg_default_set'));
        $this->emit('setDefaultDone');
    }

    public function render()
    {
        $q = Currency::query()
            ->search($this->search)
            ->when($this->status !== '', fn($w) => $w->where('status',$this->status))
            ->when($this->onlyDefault, fn($w) => $w->where('is_default', true))
            ->orderByDesc('is_default')
            ->orderBy('code');

        $rows = $q->paginate($this->perPage);

        return view('livewire.currencies.index', compact('rows'));
    }
}
