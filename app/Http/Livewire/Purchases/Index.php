<?php

namespace App\Http\Livewire\Purchases;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\models\purchases\purchases as Purchase;
use App\models\inventory\warehouse as Warehouse;
use App\models\supplier\supplier   as Supplier;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // فلاتر
    public $search = '';
    public $status = '';
    public $date_from = null;
    public $date_to   = null;
    public $warehouse_id = '';
    public $supplier_id  = '';
    public $per_page = 10;

    protected $listeners = [
        'deleteConfirmed' => 'delete',   // يأتي من SweetAlert (JS)
    ];

    public function updatingSearch()     { $this->resetPage(); }
    public function updatingStatus()     { $this->resetPage(); }
    public function updatingDateFrom()   { $this->resetPage(); }
    public function updatingDateTo()     { $this->resetPage(); }
    public function updatingWarehouseId(){ $this->resetPage(); }
    public function updatingSupplierId() { $this->resetPage(); }
    public function updatingPerPage()    { $this->resetPage(); }

    public function changeStatus($id, $newStatus)
    {
        $allowed = ['draft','approved','posted','cancelled'];
        if (!in_array($newStatus, $allowed, true)) {
            session()->flash('error', __('pos.invalid_status'));
            return;
        }

        $p = Purchase::find($id);
        if (!$p) {
            session()->flash('error', __('pos.not_found'));
            return;
        }

        $p->status = $newStatus;
        $p->save();

        session()->flash('success', __('pos.status_updated'));
    }

    public function delete($id)
    {
        $p = Purchase::find($id);
        if (!$p) {
            session()->flash('error', __('pos.not_found'));
            return;
        }
        $p->delete();
        session()->flash('success', __('pos.deleted_ok'));
        $this->resetPage();
    }

    private function resolveName($value)
    {
        $locale = app()->getLocale();
        if (is_string($value) && strlen($value) && $value[0] === '{') {
            $arr = json_decode($value, true) ?: [];
            return $arr[$locale] ?? $arr['ar'] ?? $arr['en'] ?? $value;
        }
        return $value;
    }

    public function render()
    {
        $wTable = (new Warehouse)->getTable();
        $sTable = (new Supplier )->getTable();

        $q = Purchase::query()
            ->leftJoin($wTable.' as w', 'w.id', '=', 'purchases.warehouse_id')
            ->leftJoin($sTable.' as s', 's.id', '=', 'purchases.supplier_id')
            ->select('purchases.*', 'w.name as wh_name', 's.name as sup_name')
            ->when($this->status !== '', fn($qq) => $qq->where('purchases.status', $this->status))
            ->when($this->warehouse_id !== '', fn($qq) => $qq->where('purchases.warehouse_id', (int)$this->warehouse_id))
            ->when($this->supplier_id  !== '', fn($qq) => $qq->where('purchases.supplier_id', (int)$this->supplier_id))
            ->when($this->date_from, fn($qq) => $qq->whereDate('purchases.purchase_date', '>=', $this->date_from))
            ->when($this->date_to,   fn($qq) => $qq->whereDate('purchases.purchase_date', '<=', $this->date_to))
            ->when($this->search, function($qq) {
                $term = '%'.$this->search.'%';
                $qq->where(function($w) use ($term) {
                    $w->where('purchases.purchase_no', 'like', $term)
                      ->orWhere('purchases.notes', 'like', $term)
                      ->orWhere('w.name', 'like', $term)
                      ->orWhere('s.name', 'like', $term);
                });
            })
            ->orderByDesc('purchases.purchase_date')
            ->orderByDesc('purchases.id');

        $purchases = $q->paginate((int)$this->per_page)->withQueryString();

        // قوائم الفلاتر
        $warehouses = Warehouse::orderBy('name')->get(['id','name']);
        $suppliers  = Supplier ::orderBy('name')->get(['id','name']);

        // حل أسماء متعددة اللغات (عرض فقط)
        $purchases->getCollection()->transform(function($row) {
            $row->wh_name  = $this->resolveName($row->wh_name);
            $row->sup_name = $this->resolveName($row->sup_name);
            return $row;
        });

        return view('livewire.purchases.index', compact('purchases','warehouses','suppliers'));
    }
}
