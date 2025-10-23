<?php

namespace App\Http\Livewire\FinanceMovements;

use Livewire\Component;
use Livewire\WithPagination;
use App\models\finance\finance_movement as Movement;
use App\models\finance\finance_settings as Settings;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['deleteConfirmed' => 'delete'];

    // فلاتر
    public $q = '';
    public $cashbox_id = 'all';
    public $direction = 'all'; // all | in | out
    public $status = 'all';    // all | draft | posted | void
    public $date_from = null;
    public $date_to   = null;
    public $min_amount = null;
    public $max_amount = null;
    public $trashed = 'active'; // active | with | only
    public $perPage = 10;

    // فرز
    public $sortField = 'movement_date';
    public $sortDirection = 'desc';

    public function updatingQ(){ $this->resetPage(); }
    public function updated($name){ if (in_array($name, ['cashbox_id','direction','status','trashed','perPage','date_from','date_to','min_amount','max_amount'])) $this->resetPage(); }

    public function setSort($field){
        if($this->sortField === $field){
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        }else{
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function clearFilters(){
        $this->q = '';
        $this->cashbox_id = 'all';
        $this->direction = 'all';
        $this->status = 'all';
        $this->date_from = $this->date_to = null;
        $this->min_amount = $this->max_amount = null;
        $this->trashed = 'active';
        $this->perPage = 10;
        $this->sortField = 'movement_date';
        $this->sortDirection = 'desc';
        $this->resetPage();
    }

    public function delete($id){
        $row = Movement::findOrFail($id);
        $row->delete();
        session()->flash('success', __('pos.msg_deleted_success'));
        $this->resetPage();
    }

    public function restore($id){
        $row = Movement::onlyTrashed()->findOrFail($id);
        $row->restore();
        session()->flash('success', __('pos.msg_updated_success'));
    }

    public function togglePost($id){
        $row = Movement::withTrashed()->findOrFail($id);
        if($row->status === 'draft'){ $row->status = 'posted'; }
        elseif($row->status === 'posted'){ $row->status = 'draft'; }
        $row->save();
        session()->flash('success', __('pos.msg_status_toggled'));
    }

    public function void($id){
        $row = Movement::withTrashed()->findOrFail($id);
        $row->status = 'void';
        $row->save();
        session()->flash('success', __('pos.msg_updated_success'));
    }

    public function getRowsProperty(){
        $locale = app()->getLocale();

        $q = Movement::query()->with(['cashbox']);

        // trashed
        if($this->trashed === 'with') $q->withTrashed();
        elseif($this->trashed === 'only') $q->onlyTrashed();

        // search
        if(trim($this->q) !== ''){
            $s = '%'.trim($this->q).'%';
            $q->where(function($w) use ($s){
                $w->where('doc_no', 'like', $s)
                  ->orWhere('reference', 'like', $s)
                  ->orWhere('notes->ar', 'like', $s)
                  ->orWhere('notes->en', 'like', $s);
            });
        }

        // cashbox filter
        if($this->cashbox_id !== 'all'){
            $q->where('finance_settings_id', $this->cashbox_id);
        }

        // direction
        if(in_array($this->direction, ['in','out'])){
            $q->where('direction', $this->direction);
        }

        // status
        if(in_array($this->status, ['draft','posted','void'])){
            $q->where('status', $this->status);
        }

        // date range
        if($this->date_from){ $q->whereDate('movement_date', '>=', $this->date_from); }
        if($this->date_to){ $q->whereDate('movement_date', '<=', $this->date_to); }

        // amount range
        if($this->min_amount !== null && $this->min_amount !== ''){ $q->where('amount', '>=', $this->min_amount); }
        if($this->max_amount !== null && $this->max_amount !== ''){ $q->where('amount', '<=', $this->max_amount); }

        $q->orderBy($this->sortField, $this->sortDirection);

        return $q->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.finance-movements.index', [
            'rows'     => $this->rows,
            'cashboxes'=> Settings::orderBy('id')->get(),
        ]);
    }
}
