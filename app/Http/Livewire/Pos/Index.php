<?php

namespace App\Http\Livewire\Pos;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\pos\Pos;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

// عدّل النيمسبيس حسب مشروعك
use App\Models\customer\customer as Customer;
use App\models\inventory\warehouse as Warehouse;

class Index extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $search = '';
    public $status = '';
    public $warehouse_id = '';
    public $customer_id = '';
    public $date_from = '';
    public $date_to = '';

    public array $warehouses = [];
    public array $customers  = [];

    public function mount()
    {
        // تحميل المخازن (لو عندك JSON name هنعرضه في الواجهة زي ما كنت بتعمل سابقاً)
        $this->warehouses = Warehouse::orderBy('id')->get(['id','name'])->toArray();

        // ✅ بدل select('id','name') استخدم دالة مرنة
        $this->customers  = $this->customerOptions();
    }

    /**
     * يبني قائمة العملاء بشكل مرن حسب الأعمدة المتاحة عندك
     * ويُرجع مصفوفة [['id'=>..,'name'=>..], ...]
     */
    private function customerOptions(): array
    {
        $q = Customer::query();

        // لو فيه عمود name صريح
        if (Schema::hasColumn('customers', 'name')) {
            $q->select('id', 'name');
        }
        // display_name
        elseif (Schema::hasColumn('customers', 'display_name')) {
            $q->select('id', DB::raw("display_name as name"));
        }
        // company / company_name
        elseif (Schema::hasColumn('customers', 'company_name')) {
            $q->select('id', DB::raw("company_name as name"));
        } elseif (Schema::hasColumn('customers', 'company')) {
            $q->select('id', DB::raw("company as name"));
        }
        // first_name + last_name
        elseif (Schema::hasColumn('customers', 'first_name') && Schema::hasColumn('customers', 'last_name')) {
            $q->select('id', DB::raw("CONCAT(first_name,' ',last_name) as name"));
        }
        // أي بديل آمن (مثلاً البريد) لو مافيش ولا واحدة من فوق
        elseif (Schema::hasColumn('customers', 'email')) {
            $q->select('id', DB::raw("email as name"));
        } else {
            // fallback نهائي
            $q->select('id', DB::raw("CONCAT('Customer #', id) as name"));
        }

        $list = $q->get()->map(fn($r) => ['id' => $r->id, 'name' => (string)$r->name]);

        // رتب طبيعي بالاسم
        return $list->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)->values()->all();
    }

    // باقي الكود كما هو ...
    public function render()
    {
        $q = Pos::withCount('lines')
            ->when($this->search, function ($s) {
                $term = trim($this->search);
                $s->where(function ($w) use ($term) {
                    // عدّل الحقل حسب جدولك: pos_no أو sale_no
                    $w->where('pos_no', 'like', "%{$term}%")
                      ->orWhere('notes->'.app()->getLocale(), 'like', "%{$term}%");
                });
            })
            ->when($this->status, fn($qq) => $qq->where('status', $this->status))
            ->when($this->warehouse_id, fn($qq) => $qq->where('warehouse_id', $this->warehouse_id))
            ->when($this->customer_id,  fn($qq) => $qq->where('customer_id',  $this->customer_id))
            ->when($this->date_from,    fn($qq) => $qq->whereDate('pos_date', '>=', $this->date_from))
            ->when($this->date_to,      fn($qq) => $qq->whereDate('pos_date', '<=', $this->date_to))
            ->orderByDesc('id');

        return view('pos.index', [
            'rows' => $q->paginate($this->perPage),
            // ولو عايز تحدث القوائم عند كل رندر:
            // 'customers'  => $this->customerOptions(),
            // 'warehouses' => $this->warehouses,
        ]);
    }
}
