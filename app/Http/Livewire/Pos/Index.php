<?php

namespace App\Http\Livewire\Pos;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

use App\models\pos\pos as Pos;
use App\models\inventory\warehouse as Warehouse;
use App\models\customer\customer as Customer;

class Index extends Component
{
    use WithPagination;

    // فلاتر
    public string $q = '';
    public ?string $date_from = null;
    public ?string $date_to   = null;
    public ?int $warehouse_id = null;
    public ?int $customer_id  = null;
    public ?string $status    = null;

    // تحكم الجدول
    public int $perPage = 10;
    public string $sortBy = 'pos_date';
    public string $sortDir = 'desc';

    // مصادر الفلاتر
    public $warehouses;
    public $customers;

    protected $queryString = [
        'q'            => ['except' => ''],
        'date_from'    => ['except' => null],
        'date_to'      => ['except' => null],
        'warehouse_id' => ['except' => null],
        'customer_id'  => ['except' => null],
        'status'       => ['except' => null],
        'sortBy'       => ['except' => 'pos_date'],
        'sortDir'      => ['except' => 'desc'],
        'perPage'      => ['except' => 10],
        'page'         => ['except' => 1],
    ];

    public function mount(): void
    {
        $this->warehouses = Warehouse::orderBy('id')->get();
        $this->customers  = Customer::latest('id')->limit(500)->get();
        if (!$this->date_from && !$this->date_to) {
            $this->date_from = Carbon::today()->startOfMonth()->toDateString();
            $this->date_to   = Carbon::today()->toDateString();
        }
    }

    private function localize($raw): string
    {
        if (is_array($raw)) return (string)($raw[app()->getLocale()] ?? ($raw['ar'] ?? (reset($raw) ?: '')));
        if (is_string($raw)) {
            $t = trim($raw);
            if (Str::startsWith($t,'{') || Str::startsWith($t,'[')) {
                $arr = json_decode($t, true);
                if (is_array($arr)) return (string)($arr[app()->getLocale()] ?? ($arr['ar'] ?? $raw));
            }
            return $raw;
        }
        return (string)$raw;
    }

    public function updatingQ()            { $this->resetPage(); }
    public function updatingDateFrom()     { $this->resetPage(); }
    public function updatingDateTo()       { $this->resetPage(); }
    public function updatingWarehouseId()  { $this->resetPage(); }
    public function updatingCustomerId()   { $this->resetPage(); }
    public function updatingStatus()       { $this->resetPage(); }
    public function updatingPerPage()      { $this->resetPage(); }
    public function updatingSortBy()       { $this->resetPage(); }
    public function updatingSortDir()      { $this->resetPage(); }

    public function sort(string $col): void
    {
        if ($this->sortBy === $col) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy  = $col;
            $this->sortDir = 'asc';
        }
    }

    public function clearFilters(): void
    {
        $this->q = '';
        $this->date_from = Carbon::today()->startOfMonth()->toDateString();
        $this->date_to   = Carbon::today()->toDateString();
        $this->warehouse_id = null;
        $this->customer_id  = null;
        $this->status = null;
        $this->perPage = 10;
        $this->sortBy  = 'pos_date';
        $this->sortDir = 'desc';
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        $pos = Pos::find($id);
        if ($pos) {
            $pos->delete();
            session()->flash('success', __('pos.deleted_ok'));
        }
        $this->resetPage();
    }

    private function baseQuery()
    {
        return Pos::query()
            ->with(['warehouse:id,name', 'customer:id,name'])
            ->when($this->q, function ($qq) {
                $txt = mb_strtolower($this->q);
                $qq->where(function ($w) use ($txt) {
                    $w->whereRaw('LOWER(pos_no) LIKE ?', ["%{$txt}%"])
                      ->orWhereRaw('LOWER(status)  LIKE ?', ["%{$txt}%"])
                      ->orWhereRaw('LOWER(notes)   LIKE ?', ["%{$txt}%"]);
                });
            })
            ->when($this->date_from, fn($qq) => $qq->whereDate('pos_date', '>=', $this->date_from))
            ->when($this->date_to,   fn($qq) => $qq->whereDate('pos_date', '<=', $this->date_to))
            ->when($this->warehouse_id, fn($qq) => $qq->where('warehouse_id', $this->warehouse_id))
            ->when($this->customer_id,  fn($qq) => $qq->where('customer_id',  $this->customer_id))
            ->when($this->status,       fn($qq) => $qq->where('status', $this->status));
    }

    public function render()
    {
        $rows = $this->baseQuery()
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        return view('livewire.pos.index', [
            'rows'       => $rows,
            'warehouses' => $this->warehouses,
            'customers'  => $this->customers,
        ]);
    }
}
