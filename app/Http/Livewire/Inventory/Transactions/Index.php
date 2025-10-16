<?php

namespace App\Http\Livewire\inventory\transactions;

use Livewire\Component;
use Livewire\WithPagination;
use App\models\inventory\{stock_transaction, warehouse};
use Carbon\Carbon;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $type = '';
    public $warehouse_id = '';
    public $date_from = '';
    public $date_to = '';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'type' => ['except' => ''],
        'warehouse_id' => ['except' => ''],
        'date_from' => ['except' => ''],
        'date_to' => ['except' => ''],
    ];

    public function updating($field)
    {
        if (in_array($field, ['search','type','warehouse_id','date_from','date_to'])) {
            $this->resetPage();
        }
    }

    public function getTypesProperty()
    {
        return [
            'sales_issue'      => 'صرف مبيعات',
            'sales_return'     => 'مرتجع مبيعات',
            'purchase_receive' => 'استلام مشتريات',
            'transfer'         => 'تحويل بين مخازن',
            'adjustment'       => 'تسوية مخزنية',
        ];
    }

    public function clearFilters()
    {
        $this->reset(['search','type','warehouse_id','date_from','date_to']);
    }

    public function render()
    {
        $q = stock_transaction::with(['warehouseFrom','warehouseTo','user'])
            ->when($this->search, function ($qq) {
                $s = trim($this->search);
                $qq->where(function ($w) use ($s) {
                    $w->where('trx_no','like',"%{$s}%")
                      ->orWhere('notes','like',"%{$s}%");
                });
            })
            ->when($this->type, fn($qq) => $qq->where('type',$this->type))
            ->when($this->warehouse_id, function ($qq) {
                $qq->where(function ($w) {
                    $w->where('warehouse_from_id',$this->warehouse_id)
                      ->orWhere('warehouse_to_id',$this->warehouse_id);
                });
            })
            ->when($this->date_from, fn($qq) => $qq->whereDate('trx_date','>=',$this->date_from))
            ->when($this->date_to, fn($qq) => $qq->whereDate('trx_date','<=',$this->date_to))
            ->orderByDesc('id');

        return view('livewire.inventory.transactions.index', [
            'rows' => $q->paginate($this->perPage),
            'warehouses' => warehouse::orderBy('name')->get(),
            'types' => $this->types,
        ]);
    }
}
