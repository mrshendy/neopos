<?php

namespace App\Http\Livewire\Inventory\Transactions;

use Livewire\Component;
use Livewire\WithPagination;
use App\models\inventory\stock_transaction as StockTransaction;
use App\models\inventory\warehouse        as Warehouse;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public string $search = '';
    public string $type   = '';
    public string $status = '';
    public ?string $date_from = null;
    public ?string $date_to   = null;
    public $warehouse_id = '';
    public int $perPage = 10;

    protected $listeners = ['deleteConfirmed' => 'delete'];

    public function updating($field){ if (in_array($field, ['search','type','status','date_from','date_to','warehouse_id','perPage'])) $this->resetPage(); }

    public function changeStatus($id, $newStatus)
    {
        $allowed = ['draft','posted','cancelled'];
        if (!in_array($newStatus, $allowed, true)) { session()->flash('error','حالة غير مسموحة.'); return; }
        $trx = StockTransaction::find($id);
        if (!$trx) { session()->flash('error','العنصر غير موجود.'); return; }
        $trx->status = $newStatus;
        $trx->save();
        session()->flash('success','تم تحديث الحالة.');
    }

    public function delete($id)
    {
        $trx = StockTransaction::find($id);
        if (!$trx) { session()->flash('error','العنصر غير موجود.'); return; }
        $trx->delete();
        session()->flash('success','تم الحذف.');
        $this->resetPage();
    }

    public function render()
    {
        $q = StockTransaction::with(['warehouseFrom','warehouseTo','user'])
            ->when($this->search, function ($qq) {
                $s = '%'.trim($this->search).'%';
                $qq->where(function($w) use($s){
                    $w->where('trx_no','like',$s)->orWhere('type','like',$s)->orWhere('notes','like',$s);
                });
            })
            ->when($this->type !== '', fn($qq) => $qq->where('type',$this->type))
            ->when($this->status !== '', fn($qq) => $qq->where('status',$this->status))
            ->when($this->warehouse_id !== '', function($qq){
                $wid = (int)$this->warehouse_id;
                $qq->where(function($w) use($wid){
                    $w->where('warehouse_from_id',$wid)->orWhere('warehouse_to_id',$wid);
                });
            })
            ->when($this->date_from, fn($qq)=> $qq->whereDate('trx_date','>=',$this->date_from))
            ->when($this->date_to,   fn($qq)=> $qq->whereDate('trx_date','<=',$this->date_to))
            ->orderByDesc('trx_date')->orderByDesc('id');

        return view('livewire.inventory.transactions.index', [
            'rows'       => $q->paginate($this->perPage)->withQueryString(),
            'warehouses' => Warehouse::orderBy('name')->get(['id','name']),
        ]);
    }
}
