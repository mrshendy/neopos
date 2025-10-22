<?php

namespace App\Http\Livewire\FinanceReceipts;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\models\finance\finance_receipt as Receipt;
use App\models\finance\finance_settings as Settings;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['deleteConfirmed' => 'delete', 'cancelConfirmed' => 'cancel'];

    // فلاتر
    public $q = '';
    public $cashbox_id = 'all';
    public $status = 'all';    // all | active | canceled
    public $method = 'all';    // all | cash | bank | pos | transfer
    public $date_from, $date_to;
    public $min_amount, $max_amount;
    public $trashed = 'active'; // active | with | only
    public $perPage = 10;

    // فرز
    public $sortField = 'receipt_date';
    public $sortDirection = 'desc';

    // إجماليات
    public $total_cash = 0.00;    // إجمالي النقدي (method=cash & status=active)
    public $total_return = 0.00;  // إجمالي المرتجع (return_amount & status=active)
    public $total_net = 0.00;     // إجمالي الصافي (amount_total - return_amount) للـ active فقط

    public function mount($showCanceled = false)
    {
        if ($showCanceled) $this->status = 'canceled';
    }

    public function updatingQ(){ $this->resetPage(); }
    public function updated($name){ 
        if (in_array($name, ['cashbox_id','status','method','trashed','perPage','date_from','date_to','min_amount','max_amount'])) $this->resetPage();
    }

    public function setSort($field){
        if($this->sortField === $field) $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        else { $this->sortField = $field; $this->sortDirection = 'asc'; }
        $this->resetPage();
    }

    public function clearFilters(){
        $this->q=''; $this->cashbox_id='all'; $this->status='all'; $this->method='all';
        $this->date_from = $this->date_to = null;
        $this->min_amount = $this->max_amount = null;
        $this->trashed='active'; $this->perPage=10;
        $this->sortField='receipt_date'; $this->sortDirection='desc';
        $this->resetPage();
    }

    public function delete($id){
        $row = Receipt::findOrFail($id);
        $row->delete();
        session()->flash('success', __('pos.msg_deleted_success'));
        $this->resetPage();
    }

    // إلغاء إيصال (مع سبب)
    public function cancel($payload)
    {
        $id = $payload['id'] ?? null;
        $reason = $payload['reason'] ?? null;
        if(!$id){ return; }

        $row = Receipt::findOrFail($id);
        $row->status = 'canceled';
        $row->cancel_reason = ['ar'=>$reason, 'en'=>$reason]; // ممكن تفريق اللغتين لاحقًا
        $row->canceled_by = Auth::id();
        $row->canceled_at = now();
        $row->save();

        session()->flash('success', __('pos.msg_updated_success'));
    }

    public function showCanceledOnly()
    {
        $this->status = 'canceled';
        $this->resetPage();
    }

    protected function baseQuery()
    {
        $q = Receipt::query()->with('cashbox');

        if($this->trashed==='with') $q->withTrashed();
        elseif($this->trashed==='only') $q->onlyTrashed();

        if(trim($this->q) !== ''){
            $s = '%'.trim($this->q).'%';
            $q->where(function($w) use ($s){
                $w->where('doc_no','like',$s)
                  ->orWhere('reference','like',$s)
                  ->orWhere('notes->ar','like',$s)
                  ->orWhere('notes->en','like',$s);
            });
        }

        if($this->cashbox_id!=='all'){ $q->where('finance_settings_id', $this->cashbox_id); }
        if(in_array($this->status, ['active','canceled'])){ $q->where('status', $this->status); }
        if(in_array($this->method, ['cash','bank','pos','transfer'])){ $q->where('method', $this->method); }

        if($this->date_from){ $q->whereDate('receipt_date','>=',$this->date_from); }
        if($this->date_to){ $q->whereDate('receipt_date','<=',$this->date_to); }

        if($this->min_amount !== null && $this->min_amount!==''){ $q->where('amount_total','>=',$this->min_amount); }
        if($this->max_amount !== null && $this->max_amount!==''){ $q->where('amount_total','<=',$this->max_amount); }

        return $q;
    }

    protected function computeTotals($filtered)
    {
        // إجمالي النقدي (active + method=cash)
        $this->total_cash = (clone $filtered)
            ->where('status','active')->where('method','cash')->sum('amount_total');

        // إجمالي المرتجع (active فقط)
        $this->total_return = (clone $filtered)
            ->where('status','active')->sum('return_amount');

        // إجمالي الصافي (active فقط)
        $this->total_net = (clone $filtered)
            ->where('status','active')
            ->selectRaw('COALESCE(SUM(amount_total - COALESCE(return_amount,0)),0) as net')
            ->value('net');
    }

    public function getRowsProperty()
    {
        $q = $this->baseQuery()->orderBy($this->sortField, $this->sortDirection);

        // احسب الإجماليات على نفس الفلاتر (قبل الباجيناشن)
        $this->computeTotals($this->baseQuery());

        return $q->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.finance-receipts.index', [
            'rows'      => $this->rows,
            'cashboxes' => Settings::orderBy('id')->get(),
        ]);
    }
}
