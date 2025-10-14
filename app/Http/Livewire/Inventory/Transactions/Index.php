<?php

namespace App\Http\Livewire\inventory\transactions;

use Livewire\Component;
use Livewire\WithPagination;
use App\models\inventory\stock_transaction;
use App\models\inventory\warehouse;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['deleteConfirmed' => 'delete'];

    public $search = '';     // trx_no
    public $type = '';       // sales_issue, sales_return, adjustment, transfer, purchase_receive
    public $status = '';     // draft, posted, cancelled
    public $warehouse_id = '';
    public $date_from = '';
    public $date_to = '';

    protected $queryString = ['page','search','type','status','warehouse_id','date_from','date_to'];

    public function updating($prop)
    {
        if (in_array($prop, ['search','type','status','warehouse_id','date_from','date_to'])) {
            $this->resetPage();
        }
    }

    public function render()
    {
        $q = stock_transaction::query();

        if ($this->search)        $q->where('trx_no', 'like', "%{$this->search}%");
        if ($this->type !== '')   $q->where('type', $this->type);
        if ($this->status !== '') $q->where('status', $this->status);

        if ($this->warehouse_id !== '') {
            $q->where(function($qr){
                $qr->where('warehouse_from_id', $this->warehouse_id)
                   ->orWhere('warehouse_to_id', $this->warehouse_id);
            });
        }

        if ($this->date_from) $q->whereDate('trx_date', '>=', $this->date_from);
        if ($this->date_to)   $q->whereDate('trx_date', '<=', $this->date_to);

        $trxs = $q->orderByDesc('trx_date')->paginate(12);
        $warehouses = warehouse::orderByDesc('id')->get(['id','name']);

        return view('livewire.inventory.transactions.index', compact('trxs','warehouses'));
    }

    public function post($id)
    {
        $t = stock_transaction::findOrFail($id);
        if ($t->status !== 'draft') {
            session()->flash('error', __('pos.cannot_post_non_draft'));
            return;
        }
        $t->status = 'posted';
        $t->save();

        session()->flash('success', __('pos.posted_success'));
    }

    public function cancel($id)
    {
        $t = stock_transaction::findOrFail($id);
        if ($t->status === 'posted') {
            session()->flash('error', __('pos.cannot_cancel_posted'));
            return;
        }
        $t->status = 'cancelled';
        $t->save();

        session()->flash('success', __('pos.cancelled_success'));
    }

    public function confirmDelete($id)
    {
        $this->dispatchBrowserEvent('confirm-delete', ['id' => $id]);
    }

    public function delete($id)
    {
        $t = stock_transaction::findOrFail($id);
        if ($t->status === 'posted') {
            session()->flash('error', __('pos.cannot_delete_posted'));
            return;
        }
        $t->delete();
        session()->flash('success', __('pos.deleted_success'));
    }
}
