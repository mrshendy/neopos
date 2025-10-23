<?php
// app/Http/Livewire/FinanceHandovers/Index.php

namespace App\Http\Livewire\FinanceHandovers;

use Livewire\Component;
use Livewire\WithPagination;
use App\models\finance\finance_handover as Handover;
use App\models\finance\finance_settings as Settings;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['deleteConfirmed' => 'delete'];

    // فلاتر
    public $q = '';
    public $cashbox_id = 'all';
    public $status = 'all'; // draft|submitted|received|rejected|all
    public $date_from, $date_to;
    public $trashed = 'active'; // active|with|only
    public $perPage = 10;

    // فرز
    public $sortField = 'handover_date';
    public $sortDirection = 'desc';

    public function updatingQ(){ $this->resetPage(); }
    public function updated($name){ if (in_array($name, ['cashbox_id','status','trashed','perPage','date_from','date_to'])) $this->resetPage(); }

    public function setSort($field){
        if($this->sortField === $field){ $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc'; }
        else { $this->sortField = $field; $this->sortDirection = 'asc'; }
        $this->resetPage();
    }

    public function clearFilters(){
        $this->q=''; $this->cashbox_id='all'; $this->status='all';
        $this->date_from = $this->date_to = null;
        $this->trashed='active'; $this->perPage=10;
        $this->sortField='handover_date'; $this->sortDirection='desc';
        $this->resetPage();
    }

    public function delete($id){
        $row = Handover::findOrFail($id);
        $row->delete();
        session()->flash('success', __('pos.msg_deleted_success'));
        $this->resetPage();
    }

    public function restore($id){
        $row = Handover::onlyTrashed()->findOrFail($id);
        $row->restore();
        session()->flash('success', __('pos.msg_updated_success'));
    }

    public function setStatus($id, $status){
        $row = Handover::withTrashed()->findOrFail($id);
        if(!in_array($status, ['draft','submitted','received','rejected'])) return;
        $row->status = $status;
        if($status==='received' && !$row->received_at){ $row->received_at = now(); }
        $row->save();
        session()->flash('success', __('pos.msg_status_toggled'));
    }

    public function getRowsProperty(){
        $q = Handover::query()->with('cashbox');

        if($this->trashed==='with') $q->withTrashed();
        elseif($this->trashed==='only') $q->onlyTrashed();

        if(trim($this->q) !== ''){
            $s = '%'.trim($this->q).'%';
            $q->where(function($w) use ($s){
                $w->where('doc_no','like',$s)
                  ->orWhere('notes->ar','like',$s)
                  ->orWhere('notes->en','like',$s);
            });
        }

        if($this->cashbox_id!=='all'){ $q->where('finance_settings_id', $this->cashbox_id); }
        if(in_array($this->status, ['draft','submitted','received','rejected'])){ $q->where('status', $this->status); }

        if($this->date_from){ $q->whereDate('handover_date','>=',$this->date_from); }
        if($this->date_to){ $q->whereDate('handover_date','<=',$this->date_to); }

        $q->orderBy($this->sortField,$this->sortDirection);

        return $q->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.finance-handovers.index', [
            'rows'      => $this->rows,
            'cashboxes' => Settings::orderBy('id')->get(),
        ]);
    }
}
