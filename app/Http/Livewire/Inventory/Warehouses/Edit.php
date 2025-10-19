<?php

namespace App\Http\Livewire\Inventory\Warehouses;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;

use App\models\inventory\warehouse as Warehouse; // موديل المخازن
use App\models\general\branch as Branch;         // موديل الفروع
use App\User;                                    // ✅ مسار المستخدمين الصحيح عندك

class Edit extends Component
{
    /** ===== المعرف ===== */
    public $warehouse_id;

    /** ===== حقول النموذج ===== */
    public $name = ['ar' => '', 'en' => ''];
    public $code;
    public $branch_id;
    public $status = 'active';
    public $warehouse_type = 'main';
    public $manager_ids = [];
    public $address = '';
    public $category_id = null;
    public $product_ids = [];

    /** ===== القوائم ===== */
    public $branches = [];   // عناصرها: {id, label}
    public $users = [];      // عناصرها: {id, name}
    public $categories = []; // عناصرها: {id, label}
    public $products = [];   // عناصرها: {id, label, sku}

    protected string $lang = 'ar';

    /** ===== قواعد التحقق ===== */
    protected function rules()
    {
        return [
            'name.ar'         => ['required','string','max:255'],
            'name.en'         => ['required','string','max:255'],
            'code'            => ['required','string','max:50', Rule::unique('warehouses','code')->ignore($this->warehouse_id)],
            'branch_id'       => ['nullable','integer','exists:branches,id'],
            'status'          => ['required','in:active,inactive'],
            'warehouse_type'  => ['required','in:main,sub'],
            'manager_ids'     => ['array'],
            'manager_ids.*'   => ['integer','exists:users,id'],
            'address'         => ['nullable','string','max:2000'],
            'category_id'     => ['nullable','integer','exists:categories,id'],
            'product_ids'     => ['array'],
        ];
    }

    /** حوّل أي rows فيها name (JSON أو نص) إلى {id,label} حسب اللغة */
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

    /** ===== تحميل البيانات عند الفتح ===== */
    public function mount($warehouse_id = null): void
    {
        $this->lang = app()->getLocale() ?: 'ar';

        // الحصول على المعرف من البراميتر أو الراوت
        $this->warehouse_id = $warehouse_id
            ?? request()->route('warehouse')
            ?? request()->route('id')
            ?? request('warehouse_id');

        $w = Warehouse::query()->whereKey($this->warehouse_id)->first();
        if (!$w) {
            session()->flash('error', 'المخزن غير موجود أو تم حذفه.');
            $this->redirectRoute('inventory.warehouses.index'); // stop further rendering
            return;
        }

        // تعبئة الحقول
        $this->name           = is_array($w->name) ? $w->name : ['ar' => (string)($w->name ?? ''), 'en' => ''];
        $this->code           = $w->code;
        $this->branch_id      = $w->branch_id;
        $this->status         = $w->status ?? 'active';
        $this->warehouse_type = $w->warehouse_type ?? 'main';
        $this->manager_ids    = (array)($w->manager_ids ?? []);
        $this->address        = $w->address ?? '';
        $this->category_id    = $w->category_id ?? null;
        $this->product_ids    = (array)($w->product_ids ?? []);

        // الفروع (label حسب اللغة)
        $rawBranches    = Branch::query()->select('id','name')->orderByDesc('id')->get();
        $this->branches = $this->mapWithLabel($rawBranches, $this->lang);

        // المستخدمون
        $this->users = User::query()->select('id','name')->orderBy('name')->get();

        // الأقسام (label من JSON_EXTRACT)
        $namePath = '$."'.$this->lang.'"';
        $this->categories = DB::table('categories')
            ->select(['id', DB::raw("JSON_UNQUOTE(JSON_EXTRACT(name, '{$namePath}')) AS label")])
            ->orderByDesc('id')
            ->get();

        // منتجات القسم الحالي (لو محدد)
        $this->loadProducts();
    }

    /** عند تغيير القسم: نحمّل المنتجات ونصفّر الاختيارات */
    public function updatedCategoryId(): void
    {
        $this->loadProducts();
        $this->product_ids = [];
    }

    /** تحميل المنتجات الخاصة بالقسم الحالي */
    protected function loadProducts(): void
    {
        if (!$this->category_id) { $this->products = collect(); return; }

        $namePath = '$."'.$this->lang.'"';
        $this->products = DB::table('products')
            ->select([
                'id',
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(name, '{$namePath}')) AS label"),
                'sku',
                'category_id',
            ])
            ->where('category_id', $this->category_id)
            ->orderByDesc('id')
            ->get();
    }

    /** ضبط منطق اختيار المنتجات (القسم كامل يلغي باقي الاختيارات) */
    public function updatedProductIds()
    {
        $ids = collect($this->product_ids ?? []);
        if ($ids->contains('__ALL__')) {
            $this->product_ids = ['__ALL__'];
            return;
        }
        $this->product_ids = $ids->unique()->values()->all();
    }

    /** حفظ التعديلات */
    public function save()
    {
        $this->validate();

        // تنظيف product_ids
        $selected = collect($this->product_ids ?? []);
        if ($selected->isNotEmpty()) {
            if ($selected->contains('__ALL__')) {
                $selected = collect(['__ALL__']);
            } else {
                $valid = ($this->products ?? collect())->pluck('id')->map(fn($v)=>(string)$v)->all();
                $selected = $selected->filter(fn($id)=>in_array((string)$id, $valid))->values();
            }
        }

        $w = Warehouse::query()->whereKey($this->warehouse_id)->first();
        if (!$w) {
            session()->flash('error', 'المخزن غير موجود.');
            return $this->redirectRoute('inventory.warehouses.index');
        }

        $w->update([
            'name'           => $this->name,                               // JSON
            'code'           => Str::upper(trim($this->code)),
            'branch_id'      => $this->branch_id ?: null,
            'status'         => $this->status,                             // active|inactive
            'warehouse_type' => $this->warehouse_type,                     // main|sub
            'manager_ids'    => array_values($this->manager_ids ?: []),    // JSON
            'address'        => $this->address,
            'category_id'    => $this->category_id,
            'product_ids'    => $selected->all(),                          // JSON
        ]);

        session()->flash('success', __('pos.msg_updated'));
        return $this->redirectRoute('inventory.warehouses.index');
    }

    /** ===== عرض الفيو ===== */
    public function render()
    {
        return view('livewire.inventory.warehouses.edit', [
            'branches'   => $this->branches,
            'users'      => $this->users,
            'categories' => $this->categories,
            'products'   => $this->products,
        ]);
    }
}
