<?php

namespace App\Http\Livewire\product;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\models\product\product;
use App\models\product\category;
use App\models\unit\unit; 
use App\models\supplier\supplier; 

class edit extends Component
{
    use WithFileUploads;

    public $row_id;

    // أساسية
    public $sku, $barcode;
    public $name = ['ar'=>'','en'=>''];
    public $description = ['ar'=>'','en'=>''];
    public $category_id, $supplier_id;
    public $status = 'active';

    // صورة
    public $image;       // TemporaryUploadedFile
    public $image_path;  // stored path

    // الوحدات
    public $units_matrix = [
        'minor'  => ['cost'=>null, 'price'=>null, 'factor'=>1],
        'middle' => ['cost'=>null, 'price'=>null, 'factor'=>1],
        'major'  => ['cost'=>null, 'price'=>null, 'factor'=>1],
    ];
    public $sale_unit = 'minor';
    public $purchase_unit = 'minor';

    // صلاحية
    public $expiry_enabled = false;
    public $expiry_unit = 'day';
    public $expiry_value;
    public $expiry_weekdays = [];

    protected $rules = [
        'sku'                 => 'required|string|max:100|unique:products,sku,{{row_id}}',
        'barcode'             => 'nullable|string|max:100',
        'name.ar'             => 'required|string|max:255',
        'name.en'             => 'required|string|max:255',
        'description.ar'      => 'nullable|string|max:5000',
        'description.en'      => 'nullable|string|max:5000',
        'category_id'         => 'nullable|exists:categories,id',
        'supplier_id'         => 'nullable|exists:suppliers,id',
        'status'              => 'required|in:active,inactive',
        'image'               => 'nullable|image|max:2048|mimes:jpg,jpeg,png,webp',

        // الوحدات
        'units_matrix.minor.factor'  => 'nullable|numeric|min:1',
        'units_matrix.middle.factor' => 'nullable|numeric|min:1',
        'units_matrix.major.factor'  => 'nullable|numeric|min:1',
        'units_matrix.*.cost'        => 'nullable|numeric|min:0',
        'units_matrix.*.price'       => 'nullable|numeric|min:0',
        'sale_unit'                  => 'required|in:minor,middle,major',
        'purchase_unit'              => 'required|in:minor,middle,major',

        // الصلاحية
        'expiry_enabled'      => 'boolean',
        'expiry_unit'         => 'nullable|in:day,month,year',
        'expiry_value'        => 'nullable|integer|min:1',
        'expiry_weekdays'     => 'array',
    ];

    protected $messages = [
        'sku.required'        => 'كود الصنف مطلوب.',
        'sku.unique'          => 'كود الصنف مستخدم من قبل.',
        'name.ar.required'    => 'الاسم بالعربية مطلوب.',
        'name.en.required'    => 'الاسم بالإنجليزية مطلوب.',
        'image.image'         => 'الملف يجب أن يكون صورة.',
        'image.max'           => 'الحد الأقصى للصورة 2MB.',
        'units_matrix.*.factor.min' => 'معامل التحويل يجب ألا يقل عن 1.',
    ];

    public function mount($id)
    {
        $p = product::findOrFail($id);
        $this->row_id = $p->id;

        // أساسية
        $this->sku = $p->sku;
        $this->barcode = $p->barcode;
        $this->name = [
            'ar' => (string)$p->getTranslation('name', 'ar'),
            'en' => (string)$p->getTranslation('name', 'en'),
        ];
        $this->description = [
            'ar' => (string)$p->getTranslation('description', 'ar'),
            'en' => (string)$p->getTranslation('description', 'en'),
        ];
        $this->category_id = $p->category_id;
        $this->supplier_id = $p->supplier_id;
        $this->status      = $p->status;

        // صورة
        $this->image_path = $p->image_path;

        // وحدات
        $this->units_matrix  = is_array($p->units_matrix) ? $p->units_matrix : $this->units_matrix;
        $this->sale_unit     = $p->sale_unit ?: 'minor';
        $this->purchase_unit = $p->purchase_unit ?: 'minor';

        // صلاحية
        $this->expiry_enabled  = (bool)$p->expiry_enabled;
        $this->expiry_unit     = $p->expiry_enabled ? ($p->expiry_unit ?: 'day') : 'day';
        $this->expiry_value    = $p->expiry_enabled ? ($p->expiry_value ?: null) : null;
        $this->expiry_weekdays = $p->expiry_enabled && is_array($p->expiry_weekdays) ? $p->expiry_weekdays : [];
    }

    public function removeImage()
    {
        $this->image = null;
        // لا نحذف الملف القديم تلقائياً إلا لو حبيت
    }

    public function save()
    {
        // تعديل قاعدة الـ unique ديناميكياً ليتجاهل السجل الحالي
        $this->rules['sku'] = 'required|string|max:100|unique:products,sku,'.$this->row_id;
        $this->validate();

        $p = product::findOrFail($this->row_id);

        // صورة
        if ($this->image) {
            $this->image_path = $this->image->store('products', 'public');
            $p->image_path = $this->image_path;
        }

        // أساسية
        $p->sku = $this->sku;
        $p->barcode = $this->barcode;
        $p->setTranslation('name','ar',$this->name['ar']);
        $p->setTranslation('name','en',$this->name['en']);
        $p->setTranslation('description','ar',$this->description['ar'] ?? '');
        $p->setTranslation('description','en',$this->description['en'] ?? '');
        $p->category_id = $this->category_id ?: null;
        $p->supplier_id = $this->supplier_id ?: null;
        $p->status      = $this->status;

        // وحدات
        $p->units_matrix   = $this->units_matrix;
        $p->sale_unit      = $this->sale_unit;
        $p->purchase_unit  = $this->purchase_unit;

        // صلاحية
        $p->expiry_enabled  = $this->expiry_enabled ? 1 : 0;
        $p->expiry_unit     = $this->expiry_enabled ? $this->expiry_unit : null;
        $p->expiry_value    = $this->expiry_enabled ? ($this->expiry_value ?: null) : null;
        $p->expiry_weekdays = $this->expiry_enabled && $this->expiry_unit === 'day' ? array_values($this->expiry_weekdays) : null;

        $p->save();

        session()->flash('success', __('pos.saved_success') ?? 'تم التحديث بنجاح');
        return redirect()->route('product.index');
    }

    public function render()
    {
        return view('livewire.product.edit', [
            'categories' => category::orderBy('name->'.app()->getLocale())->get(['id','name']),
            'units'      => unit::orderBy('name->'.app()->getLocale())->get(['id','name']),
            'suppliers'  => supplier::orderBy('name->'.app()->getLocale())->get(['id','name']),
        ]);
    }
}
