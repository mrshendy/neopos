<?php

namespace App\Http\Livewire\Inventory\Warehouses;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

use App\models\inventory\warehouse;   
use App\models\general\branch;        
use App\User;

class Create extends Component
{
    /** form fields */
    public $name = ['ar' => '', 'en' => ''];
    public $code;
    public $branch_id;
    public $status = 'active';
    public $warehouse_type = 'main';
    public $manager_ids = [];
    public $address = '';
    public $category_id = null;
    public $product_ids = [];

    /** dropdown lists */
    public $branches = [];
    public $users = [];
    public $categories = [];
    public $products = [];

    /** قواعد وتراسل */
    protected $rules = [
        'name.ar'         => ['required','string','max:255'],
        'name.en'         => ['required','string','max:255'],
        'code'            => ['required','string','max:50','unique:warehouses,code'],
        'branch_id'       => ['nullable','integer','exists:branches,id'],
        'status'          => ['required','in:active,inactive'],
        'warehouse_type'  => ['required','in:main,sub'],
        'manager_ids'     => ['array'],
        'manager_ids.*'   => ['integer','exists:users,id'],
        'address'         => ['nullable','string','max:2000'],
        'category_id'     => ['nullable','integer','exists:categories,id'],
        'product_ids'     => ['array'],
    ];

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

    public function mount(): void
    {
        $this->branches   = branch::query()->orderBy('id','desc')->get();
        $this->users      = User::query()->orderBy('name')->get();
        // عدّل جدول/أعمدة الأقسام إذا لزم
        $this->categories = DB::table('categories')->select('id','name')->orderBy('id','desc')->get();
        $this->products   = collect();
    }

    /** عند تغيير القسم */
    public function updatedCategoryId(): void
    {
        $this->loadProducts();
        // تفريغ اختيارات المنتجات عند تغيير القسم
        $this->product_ids = [];
    }

    protected function loadProducts(): void
    {
        if (!$this->category_id) {
            $this->products = collect();
            return;
        }

        // عدّل جدول/الأعمدة حسب مشروعك
        $this->products = DB::table('products')
            ->select('id','name','sku','category_id')
            ->where('category_id', $this->category_id)
            ->orderBy('id','desc')
            ->get();
    }

    public function save()
    {
        $this->validate();

        // تنظيف product_ids: يسمح بـ "__ALL__" أو IDs من نفس القائمة
        $selected = collect($this->product_ids ?? []);
        if ($selected->isNotEmpty()) {
            if ($selected->contains('__ALL__')) {
                $selected = collect(['__ALL__']);
            } else {
                $valid = ($this->products ?? collect())->pluck('id')->map(fn($v)=>(string)$v)->all();
                $selected = $selected->filter(fn($id)=>in_array((string)$id, $valid))->values();
            }
        }

        warehouse::create([
            'name'           => $this->name,                              // JSON
            'code'           => Str::upper(trim($this->code)),
            'branch_id'      => $this->branch_id ?: null,
            'status'         => $this->status,
            'warehouse_type' => $this->warehouse_type,                    // main/sub
            'manager_ids'    => array_values($this->manager_ids ?: []),   // JSON
            'address'        => $this->address,
            'category_id'    => $this->category_id,
            'product_ids'    => $selected->all(),                         // JSON
        ]);

        session()->flash('success', __('pos.msg_created'));
        return redirect()->route('inventory.warehouses.index');
    }

    public function render()
    {
        return view('livewire.inventory.warehouses.create', [
            'branches'   => $this->branches,
            'users'      => $this->users,
            'categories' => $this->categories,
            'products'   => $this->products,
        ]);
    }
}
