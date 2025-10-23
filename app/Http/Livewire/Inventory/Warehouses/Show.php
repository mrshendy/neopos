<?php

namespace App\Http\Livewire\Inventory\Warehouses;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

use App\models\inventory\warehouse as Warehouse; // موديل المخازن
use App\models\general\branch as Branch;         // موديل الفروع
use App\User;                                    // مسار المستخدمين الصحيح لديك

class Show extends Component
{
    /** ===== المعرف ===== */
    public $warehouse_id;

    /** ===== بيانات المخزن ===== */
    public $name = ['ar' => '', 'en' => ''];
    public $code;
    public $branch_id;
    public $status;
    public $warehouse_type;
    public $manager_ids = [];
    public $address;
    public $category_id;
    public $product_ids = [];

    /** ===== بيانات للعرض ===== */
    public $branch_label = '—';
    public $category_label = '—';
    public $managers = [];            // [{id,name}]
    public $products = [];            // منتجات القسم (id,label,sku) للعرض فقط
    public $selected_products = [];   // أسماء المنتجات المختارة فقط (label[])
    protected string $lang = 'ar';

    /** Helper: rows(name: json|string) -> {id,label} حسب اللغة */
    private function mapWithLabel($rows, string $lang): Collection
    {
        return collect($rows)->map(function ($row) use ($lang) {
            $name  = data_get($row, 'name');
            $label = is_array($name) ? ($name[$lang] ?? '') : (string) $name;
            $label = trim($label);
            if ($label === '') { $label = '—'; }
            return (object)[
                'id'    => data_get($row, 'id'),
                'label' => $label,
            ];
        });
    }

    public function mount($warehouse_id = null): void
    {
        $this->lang = app()->getLocale() ?: 'ar';

        // احصل على الـ ID من البراميتر (لو تم تمريره) أو من الراوت
        $this->warehouse_id = $warehouse_id
            ?? request()->route('warehouse')   // لما الراوت اسمه {warehouse}
            ?? request()->route('id')          // بدائل
            ?? request('warehouse_id');

        $w = Warehouse::query()->whereKey($this->warehouse_id)->first();
        if (!$w) {
            session()->flash('error', 'المخزن غير موجود أو تم حذفه.');
            $this->redirectRoute('inventory.warehouses.index');
            return;
        }

        // تعبئة الحقول الخام
        $this->name           = is_array($w->name) ? $w->name : ['ar' => (string)($w->name ?? ''), 'en' => ''];
        $this->code           = $w->code;
        $this->branch_id      = $w->branch_id;
        $this->status         = $w->status ?? 'active';
        $this->warehouse_type = $w->warehouse_type ?? 'main';
        $this->manager_ids    = (array)($w->manager_ids ?? []);
        $this->address        = $w->address ?? '';
        $this->category_id    = $w->category_id ?? null;
        $this->product_ids    = (array)($w->product_ids ?? []);

        // تسمية الفرع حسب اللغة
        if ($this->branch_id) {
            $b = Branch::select('id','name')->find($this->branch_id);
            if ($b) {
                $this->branch_label = $this->mapWithLabel([$b], $this->lang)->first()->label;
            }
        }

        // تسمية القسم
        if ($this->category_id) {
            $path = '$."'.$this->lang.'"';
            $row = DB::table('categories')
                ->select(['id', DB::raw("JSON_UNQUOTE(JSON_EXTRACT(name, '{$path}')) AS label")])
                ->where('id', $this->category_id)
                ->first();
            if ($row) {
                $this->category_label = $row->label ?: '—';
            }
        }

        // أسماء المسئولين
        $this->managers = User::query()
            ->select('id','name')
            ->whereIn('id', $this->manager_ids ?: [-1])
            ->orderBy('name')
            ->get()
            ->toArray();

        // منتجات القسم (لعرض الأسماء المختارة)
        $this->loadProductsForView();
        $this->buildSelectedProducts();
    }

    /** تحميل كل منتجات القسم الحالي بأسماء مترجمة (للعرض فقط) */
    protected function loadProductsForView(): void
    {
        if (!$this->category_id) { $this->products = []; return; }
        $path = '$."'.$this->lang.'"';
        $this->products = DB::table('products')
            ->select([
                'id',
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(name, '{$path}')) AS label"),
                'sku',
            ])
            ->where('category_id', $this->category_id)
            ->orderByDesc('id')
            ->get()
            ->toArray();
    }

    /** جهّز قائمة أسماء المنتجات المختارة فقط */
    protected function buildSelectedProducts(): void
    {
        $ids = collect($this->product_ids ?? []);
        if ($ids->contains('__ALL__')) {
            $this->selected_products = ['__ALL__']; // هنترجمها في الـ Blade
            return;
        }

        $map = collect($this->products)->keyBy('id');
        $this->selected_products = $ids
            ->filter(fn($id) => $map->has($id))
            ->map(fn($id) => data_get($map->get($id), 'label'))
            ->values()
            ->all();
    }

    public function render()
    {
        return view('livewire.inventory.warehouses.show');
    }
}
