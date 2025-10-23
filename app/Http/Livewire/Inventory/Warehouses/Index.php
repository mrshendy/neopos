<?php

namespace App\Http\Livewire\Inventory\Warehouses;

use Livewire\Component;
use Livewire\WithPagination;

use App\models\inventory\warehouse; // عدّل الـ namespace لو مختلف
use App\models\general\branch;       // عدّل الـ namespace لو مختلف

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // فلاتر
    public $q = '';
    public $branch_id = '';
    public $warehouse_type = '';
    public $status = '';
    public $perPage = 10;

    // الاستماع لأيفنت الحذف القادم من SweetAlert
    protected $listeners = ['deleteConfirmed' => 'delete'];

    protected $queryString = [
        'q'              => ['except' => ''],
        'branch_id'      => ['except' => ''],
        'warehouse_type' => ['except' => ''],
        'status'         => ['except' => ''],
        'perPage'        => ['except' => 10],
    ];

    public function mount(): void
    {
        $this->fill(request()->only(array_keys($this->queryString)));
    }

    public function updating($name, $value)
    {
        if (in_array($name, ['q','branch_id','warehouse_type','status','perPage'])) {
            $this->resetPage();
        }
    }

    public function clearFilters(): void
    {
        $this->q = '';
        $this->branch_id = '';
        $this->warehouse_type = '';
        $this->status = '';
        $this->perPage = 10;
        $this->resetPage();
    }

    public function delete($id): void
    {
        $w = warehouse::find($id);
        if (!$w) {
            session()->flash('success', __('pos.no_data'));
            return;
        }
        $w->delete();
        session()->flash('success', __('pos.msg_deleted'));

        if ($this->page > 1 && $this->currentPageEmpty()) {
            $this->previousPage();
        }
    }

    protected function currentPageEmpty(): bool
    {
        $count = $this->makeQuery()->count();
        $from = ($this->page - 1) * $this->perPage + 1;
        return $count < $from && $count > 0;
    }

    protected function makeQuery()
    {
        return warehouse::query()
            ->with(['branch'])
            ->when($this->q, function ($qq) {
                $q = trim($this->q);
                $qq->where(function ($sub) use ($q) {
                    $sub->where('code', 'like', "%{$q}%")
                        ->orWhere('name->ar', 'like', "%{$q}%")
                        ->orWhere('name->en', 'like', "%{$q}%");
                });
            })
            ->when($this->branch_id, fn($qq) => $qq->where('branch_id', $this->branch_id))
            ->when($this->warehouse_type, fn($qq) => $qq->where('warehouse_type', $this->warehouse_type))
            ->when($this->status, fn($qq) => $qq->where('status', $this->status))
            ->orderByDesc('id');
    }

    public function render()
    {
        $warehouses = $this->makeQuery()->paginate($this->perPage);
        $branches   = branch::query()->orderByDesc('id')->get();

        return view('livewire.inventory.warehouses.index', [
            'warehouses' => $warehouses,
            'branches'   => $branches,
        ]);
    }
}
