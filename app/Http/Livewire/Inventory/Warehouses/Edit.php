<?php

namespace App\Http\Livewire\Inventory\Warehouses;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

// ✅ غيّر الـ namespace لو مختلف عندك:
use App\Models\Inventory\Warehouse;   // تأكد من الـ namespace
use App\Models\General\Branch;        // تأكد من الـ namespace
use App\Models\User;

class Edit extends Component
{
    public $warehouse_id;

    // حقول النموذج
    public $name = ['ar' => '', 'en' => ''];
    public $code;
    public $branch_id;
    public $status = 'active';
    public $warehouse_type = 'main';
    public $manager_ids = [];
    public $address = '';
    public $category_id = null;
    public $product_ids = [];

    // بيانات القوائم
    public $branches = [];
    public $users = [];
    public $categories = [];
    public $products = [];

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

    protected $messages = [
        'name.ar.required' => 'اسم المخزن بالعربية مطلوب',
        'name.en.required' => 'اسم المخزن بالإنجليزية مطلوب',
        'code.required'    => 'كود المخزن مطلوب',
        'code.unique'      => 'هذا الكود مستخدم بالفعل',
        'branch_id.exists' => 'الفرع المحدد غير موجود',
        'status.in'        => 'قيمة الحالة غير صحيحة',
        'warehouse_type.in'=> 'نوع المخزن يجب أن يكون رئيسي أو فرعي',
        'manager_ids.*.exists' => 'مستخدم غير موجود ضمن المسئولين',
        'category_id.exists'   => 'القسم المحدد غير موجود',
    ];

    /**
     * نحاول جلب المعرف من:
     * 1) بارامتر الماونت، أو
     * 2) بارامتر الراوت (warehouse / id)، أو
     * 3) كويري سترينج (warehouse_id)
     */
    public function mount($warehouse_id = null): void
    {
        $this->warehouse_id = $warehouse_id
            ?? request()->route('warehouse')
            ?? request()->route('id')
            ?? request('warehouse_id');

        $w = Warehouse::query()->whereKey($this->warehouse_id)->first();

        if (!$w) {
            session()->flash('error', 'المخزن غير موجود أو تم حذفه.');
            $this->redirectRoute('inventory.warehouses.index');
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

        // القوائم
        $this->branches   = Branch::query()->orderBy('id','desc')->get();
        $this->users      = User::query()->orderBy('name')->get();
        $this->categories = DB::table('categories')->select('id','name')->orderBy('id','desc')->get();

        $this->loadProducts(); // تحميل منتجات القسم الحالي
    }

    public function updatedCategoryId(): void
    {
        $this->loadProducts();
        $this->product_ids = []; // إعادة ضبط الاختيارات عند تغيير القسم
    }

    protected function loadProducts(): void
    {
        if (!$this->category_id) {
            $this->products = collect();
            return;
        }

        $this->products = DB::table('products')
            ->select('id','name','sku','category_id')
            ->where('category_id', $this->category_id)
            ->orderBy('id','desc')
            ->get();
    }

    public function save()
    {
        $this->validate();

        // تنظيف product_ids: "__ALL__" أو IDs من نفس القائمة
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
            'name'           => $this->name,
            'code'           => Str::upper(trim($this->code)),
            'branch_id'      => $this->branch_id ?: null,
            'status'         => $this->status,
            'warehouse_type' => $this->warehouse_type,
            'manager_ids'    => array_values($this->manager_ids ?: []),
            'address'        => $this->address,
            'category_id'    => $this->category_id,
            'product_ids'    => $selected->all(),
        ]);

        session()->flash('success', __('pos.msg_updated'));
        return $this->redirectRoute('inventory.warehouses.index');
    }

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
