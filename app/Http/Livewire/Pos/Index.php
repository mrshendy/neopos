<?php

namespace App\Http\Livewire\Pos;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

// لو الموديلات عندك بمسارات مختلفة غيّر namespace هنا
use App\models\pos\pos as Pos;
use App\models\inventory\warehouse as Warehouse;
use App\models\customer\customer as Customer;

class Index extends Component
{
    use WithPagination;

    // فلاتر
    public $perPage = 10;
    public $search = '';
    public $status = '';
    public $date_from = '';
    public $date_to = '';
    public $warehouse_id = '';
    public $customer_id = '';

    // القوائم
    public $warehouses = [];
    public $customers = [];

    // خرائط الأعمدة الديناميكية
    public $columns = [
        'no'     => null,   // sale_no | code | invoice_no | ref_no | number
        'date'   => null,   // sale_date | trx_date | pos_date | invoice_date | date | created_at
        'total'  => null,   // grand_total | total | amount | net_total
        'status' => null,   // status | state
    ];

    protected $queryString = [
        'search'       => ['except' => ''],
        'status'       => ['except' => ''],
        'warehouse_id' => ['except' => ''],
        'customer_id'  => ['except' => ''],
        'date_from'    => ['except' => ''],
        'date_to'      => ['except' => ''],
        'perPage'      => ['except' => 10],
        'page'         => ['except' => 1],
    ];

    protected $listeners = [
        'deleteConfirmed' => 'delete',
        'statusChange'    => 'changeStatus',
    ];

    /*============= Helpers ==============*/
    private function pickColumn(string $table, array $candidates, $fallback = null): ?string
    {
        foreach ($candidates as $c) {
            if (Schema::hasColumn($table, $c)) {
                return $c;
            }
        }
        return $fallback;
    }

    private function t($value, $fallback = '—')
    {
        if ($value === null || $value === '') return $fallback;
        if (is_string($value) && Str::startsWith(trim($value), '{')) {
            $a = json_decode($value, true) ?: [];
            return $a[app()->getLocale()] ?? $a['ar'] ?? $a['en'] ?? $fallback;
        }
        return $value;
    }

    public function updating($name, $value)
    {
        if (in_array($name, ['search','status','warehouse_id','customer_id','date_from','date_to','perPage'])) {
            $this->resetPage();
        }
    }

    public function mount()
    {
        // ضبط أعمدة POS حسب الموجود في الجدول
        if (Schema::hasTable('pos')) {
            $this->columns['no']     = $this->pickColumn('pos', ['sale_no','code','invoice_no','ref_no','number']);
            $this->columns['date']   = $this->pickColumn('pos', ['sale_date','trx_date','pos_date','invoice_date','date','created_at'], 'created_at');
            $this->columns['total']  = $this->pickColumn('pos', ['grand_total','total','amount','net_total']);
            $this->columns['status'] = $this->pickColumn('pos', ['status','state']);
        }

        // تحميل المخازن
        $this->warehouses = $this->loadSimpleList('warehouses', Warehouse::query());

        // تحميل العملاء
        $this->customers  = $this->loadSimpleList('customers', Customer::query());
    }

    /** يحول قائمة الموديل إلى مصفوفة بسيطة [id, name] مع ترجمة آمنة */
    private function loadSimpleList(string $table, $builder): array
    {
        if (!Schema::hasTable($table)) return [];

        // حاول العثور على عمود الاسم
        $nameCol = $this->pickColumn($table, ['name','title',$table === 'customers' ? 'customer_name' : 'warehouse_name']);
        $rows = $builder->select(['id', $nameCol ?: 'id'])->get();

        $out = [];
        foreach ($rows as $r) {
            $raw = $nameCol ? $r->{$nameCol} : ('#'.$r->id);
            $out[] = [
                'id'   => $r->id,
                'name' => $this->t($raw, '#'.$r->id),
            ];
        }
        return $out;
    }

    /*============= Actions ==============*/
    public function changeStatus($id, $status)
    {
        $row = Pos::find($id);
        if (!$row) {
            session()->flash('error', __('pos.not_found'));
            return;
        }
        if ($this->columns['status']) {
            $row->{$this->columns['status']} = $status;
            $row->save();
            session()->flash('success', __('pos.status_changed'));
        }
    }

    public function delete($id)
    {
        $row = Pos::find($id);
        if (!$row) {
            session()->flash('error', __('pos.not_found'));
            return;
        }
        $row->delete();
        session()->flash('success', __('pos.deleted_ok'));
    }

    /*============= Query ==============*/
    private function baseQuery()
    {
        $q = Pos::query();

        // withCount lines إذا العلاقة موجودة
        try {
            if (method_exists(Pos::class, 'lines')) {
                $q->withCount('lines');
            }
        } catch (\Throwable $e) {}

        // علاقات لعرض الأسماء
        try {
            if (method_exists(Pos::class, 'warehouse')) $q->with('warehouse');
            if (method_exists(Pos::class, 'customer'))  $q->with('customer');
        } catch (\Throwable $e) {}

        // بحث
        if ($this->search !== '') {
            $q->where(function($qq){
                // رقم الفاتورة
                if ($this->columns['no']) {
                    $qq->where($this->columns['no'], 'like', "%{$this->search}%");
                }
                // الملاحظات (JSON أو نص)
                if (Schema::hasColumn('pos', 'notes')) {
                    $loc = app()->getLocale();
                    $qq->orWhere("notes->$loc", 'like', "%{$this->search}%")
                       ->orWhere('notes', 'like', "%{$this->search}%");
                }
            });
        }

        // حالة
        if ($this->status !== '' && $this->columns['status']) {
            $q->where($this->columns['status'], $this->status);
        }

        // مخزن
        if ($this->warehouse_id !== '' && Schema::hasColumn('pos', 'warehouse_id')) {
            $q->where('warehouse_id', $this->warehouse_id);
        }

        // عميل
        if ($this->customer_id !== '' && Schema::hasColumn('pos', 'customer_id')) {
            $q->where('customer_id', $this->customer_id);
        }

        // تاريخ
        $dateCol = $this->columns['date'] ?: 'created_at';
        if ($this->date_from) $q->whereDate($dateCol, '>=', $this->date_from);
        if ($this->date_to)   $q->whereDate($dateCol, '<=', $this->date_to);

        // ترتيب
        $q->orderByDesc($dateCol);

        return $q;
    }

    public function render()
    {
        $rows = $this->baseQuery()->paginate((int)$this->perPage ?: 10);

        return view('livewire.pos.index', [
            'rows'     => $rows,
            'columns'  => $this->columns,
            'warehouses' => $this->warehouses,
            'customers'  => $this->customers,
        ]);
    }
}
