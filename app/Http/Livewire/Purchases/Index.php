<?php

namespace App\Http\Livewire\Purchases;

use Livewire\Component;
use Livewire\WithPagination;
use App\models\purchases\purchases as Purchase;
use App\models\inventory\warehouse as Warehouse;
use App\models\supplier\supplier as Supplier;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $date_from;
    public $date_to;
    public $warehouse_id = '';
    public $supplier_id  = '';
    public $perPage = 10;

    protected $queryString = [
        'search', 'status', 'date_from', 'date_to', 'warehouse_id', 'supplier_id', 'perPage'
    ];

    protected $listeners = ['deleteConfirmed' => 'destroy'];

    // خلى الباجيناشن بتصميم البوتستراب (لو عامل publish للـ views)
    protected $paginationTheme = 'bootstrap';

    // عشان كل ما تغيّر فلتر نرجّع لأول صفحة
    public function updatingSearch()      { $this->resetPage(); }
    public function updatingStatus()      { $this->resetPage(); }
    public function updatingDateFrom()    { $this->resetPage(); }
    public function updatingDateTo()      { $this->resetPage(); }
    public function updatingWarehouseId() { $this->resetPage(); }
    public function updatingSupplierId()  { $this->resetPage(); }
    public function updatingPerPage()     { $this->resetPage(); }

    public function destroy($id)
    {
        $p = Purchase::find($id);
        if (!$p) {
            session()->flash('error', __('pos.not_found') ?? 'Not found');
            return;
        }
        $p->delete(); // Soft delete
        session()->flash('success', __('pos.deleted_ok'));
    }

    public function render()
    {
        $q = Purchase::query()
            ->with(['warehouse:id,name', 'supplier:id,name'])
            ->withCount('lines');

        if ($s = trim($this->search)) {
            $q->where(function ($qq) use ($s) {
                $qq->where('purchase_no', 'like', "%{$s}%")
                   ->orWhere('notes->ar', 'like', "%{$s}%")
                   ->orWhere('notes->en', 'like', "%{$s}%");
            });
        }

        $q->when($this->status,       fn ($qq) => $qq->where('status', $this->status))
          ->when($this->warehouse_id, fn ($qq) => $qq->where('warehouse_id', $this->warehouse_id))
          ->when($this->supplier_id,  fn ($qq) => $qq->where('supplier_id',  $this->supplier_id))
          ->when($this->date_from,    fn ($qq) => $qq->whereDate('purchase_date', '>=', $this->date_from))
          ->when($this->date_to,      fn ($qq) => $qq->whereDate('purchase_date', '<=', $this->date_to))
          ->orderByDesc('id');

        $rows = $q->paginate($this->perPage);

        return view('livewire.purchases.index', [
            'rows'       => $rows, // <-- مهم
            'warehouses' => Warehouse::select('id','name')->orderBy('id')->get(),
            'suppliers'  => Supplier::select('id','name')->orderBy('id')->get(),
        ]);
    }
}
