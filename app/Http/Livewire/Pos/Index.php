<?php

namespace App\Http\Livewire\Pos;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\models\pos\pos as Pos;
use App\models\inventory\warehouse as Warehouse;
use App\models\customer\customer as Customer;

class Index extends Component
{
    use WithPagination;

    // Filters
    public $search = '';
    public $status = '';
    public $date_from = null;
    public $date_to   = null;
    public $warehouse_id = '';
    public $customer_id  = '';

    // Pagination
    public $perPage = 10;

    protected $queryString = [
        'search'       => ['except' => ''],
        'status'       => ['except' => ''],
        'date_from'    => ['except' => null],
        'date_to'      => ['except' => null],
        'warehouse_id' => ['except' => ''],
        'customer_id'  => ['except' => ''],
        'page'         => ['except' => 1],
        'perPage'      => ['except' => 10],
    ];

    protected $listeners = [
        'deleteConfirmed' => 'delete',
        'statusChange'    => 'setStatus',
    ];

    public function updatingSearch(){ $this->resetPage(); }
    public function updatedStatus(){ $this->resetPage(); }
    public function updatedDateFrom(){ $this->resetPage(); }
    public function updatedDateTo(){ $this->resetPage(); }
    public function updatedWarehouseId(){ $this->resetPage(); }
    public function updatedCustomerId(){ $this->resetPage(); }
    public function updatedPerPage(){ $this->resetPage(); }

    public function delete(int $id): void
    {
        DB::transaction(function() use ($id){
            $pos = Pos::with('lines')->findOrFail($id);
            // soft delete بنود ثم الهيدر (لو عندك softDeletes)
            $pos->lines()->delete();
            $pos->delete();
        });

        session()->flash('success', __('pos.deleted_ok'));
        $this->resetPage();
    }

    public function setStatus(int $id, string $status): void
    {
        $allowed = ['draft','approved','posted','cancelled'];
        if (!in_array($status, $allowed, true)) {
            session()->flash('error', __('pos.invalid_status'));
            return;
        }

        $pos = Pos::findOrFail($id);
        $pos->status = $status;
        $pos->save();

        session()->flash('success', __('pos.status_updated'));
    }

    public function render()
    {
        $q = Pos::query()
            ->with(['customer','warehouse'])
            ->withCount('lines');

        if ($this->search) {
            $s = trim($this->search);
            $q->where(function($qq) use ($s){
                $qq->where('pos_no','like',"%{$s}%")
                   ->orWhere('notes','like',"%{$s}%");
            });
        }

        if ($this->status) {
            $q->where('status', $this->status);
        }

        if ($this->warehouse_id) {
            $q->where('warehouse_id', $this->warehouse_id);
        }

        if ($this->customer_id) {
            $q->where('customer_id', $this->customer_id);
        }

        if ($this->date_from) {
            $q->whereDate('pos_date','>=',$this->date_from);
        }
        if ($this->date_to) {
            $q->whereDate('pos_date','<=',$this->date_to);
        }

        $rows = $q->orderByDesc('id')->paginate((int)$this->perPage);

        return view('livewire.pos.index', [
            'rows'       => $rows,
            'warehouses' => Warehouse::orderBy('name')->get(['id','name']),
            'customers'  => Customer::orderBy('name')->get(['id','name']),
        ]);
    }
}
