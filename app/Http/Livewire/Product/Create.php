<?php

namespace App\Http\Livewire\product;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\models\product\product;
use App\models\product\category;
use App\models\unit\unit;              
use App\models\supplier\supplier;

class Create extends Component
{
    use WithFileUploads;

    // البيانات الأساسية
    public $sku, $barcode;
    public $name = ['ar' => '', 'en' => ''];
    public $description = ['ar' => '', 'en' => ''];
    public $category_id, $supplier_id;
    public $status = 'active';

    // الصورة
    public $image;
    public $image_path;

    // وحدات للاختيار من القاعدة
    public $units = [];

    // مصفوفة الوحدات لكل مستوى
    public $units_matrix = [
        'minor'  => ['unit_id' => null, 'cost' => null, 'price' => null, 'factor' => 1],
        'middle' => ['unit_id' => null, 'cost' => null, 'price' => null, 'factor' => 1],
        'major'  => ['unit_id' => null, 'cost' => null, 'price' => null, 'factor' => 1],
    ];

    // إعدادات البيع/الشراء (تشير للمفاتيح: minor|middle|major)
    public $sale_unit_key = 'minor';
    public $purchase_unit_key = 'minor';

    // صلاحية الصنف
    public $expiry_enabled = false;
    public $expiry_unit = 'day'; // day|month|year
    public $expiry_value;
    public $expiry_weekdays = []; // ['sat','sun',..] عند اختيار day

    protected $rules = [
        'sku'                         => 'required|string|max:100|unique:products,sku',
        'barcode'                     => 'nullable|string|max:100',
        'name.ar'                     => 'required|string|max:255',
        'name.en'                     => 'required|string|max:255',
        'description.ar'              => 'nullable|string|max:5000',
        'description.en'              => 'nullable|string|max:5000',
        'category_id'                 => 'nullable|exists:categories,id',
        'supplier_id'                 => 'nullable|exists:suppliers,id',
        'status'                      => 'required|in:active,inactive',

        'units_matrix.minor.unit_id'  => 'nullable|exists:units,id',
        'units_matrix.middle.unit_id' => 'nullable|exists:units,id',
        'units_matrix.major.unit_id'  => 'nullable|exists:units,id',
        'units_matrix.*.cost'         => 'nullable|numeric|min:0',
        'units_matrix.*.price'        => 'nullable|numeric|min:0',
        'units_matrix.*.factor'       => 'nullable|numeric|min:1',

        'sale_unit_key'               => 'required|in:minor,middle,major',
        'purchase_unit_key'           => 'required|in:minor,middle,major',

        'image'                       => 'nullable|image|max:2048|mimes:jpg,jpeg,png,webp',

        'expiry_enabled'              => 'boolean',
        'expiry_unit'                 => 'nullable|in:day,month,year',
        'expiry_value'                => 'nullable|integer|min:1',
        'expiry_weekdays'             => 'array',
    ];

    protected $messages = [
        'sku.required' => 'كود الصنف مطلوب.',
        'sku.unique'   => 'كود الصنف مستخدم من قبل.',
        'name.ar.required' => 'الاسم بالعربية مطلوب.',
        'name.en.required' => 'الاسم بالإنجليزية مطلوب.',
        'units_matrix.*.unit_id.exists' => 'الوحدة المختارة غير موجودة.',
        'units_matrix.*.factor.min'     => 'معامل التحويل يجب ألا يقل عن 1.',
    ];

    public function removeImage()
    {
        $this->image = null;
        $this->image_path = null;
    }

    public function save()
    {
        $this->validate();

        // تأكيد أن وحدة البيع/الشراء من ضمن الأعمدة المختارة ولها unit_id
        foreach ([$this->sale_unit_key, $this->purchase_unit_key] as $k) {
            if (empty($this->units_matrix[$k]['unit_id'])) {
                $this->addError('sale_purchase_mismatch', 'وحدة البيع/الشراء يجب أن تكون من الوحدات المحددة بالأعلى.');
                return;
            }
        }

        $p = new product();

        // صورة
        if ($this->image) {
            $this->image_path = $this->image->store('products', 'public');
            $p->image_path = $this->image_path;
        }

        // أساسية
        $p->sku = $this->sku;
        $p->barcode = $this->barcode;
        $p->setTranslation('name', 'ar', $this->name['ar']);
        $p->setTranslation('name', 'en', $this->name['en']);
        $p->setTranslation('description', 'ar', $this->description['ar'] ?? '');
        $p->setTranslation('description', 'en', $this->description['en'] ?? '');
        $p->category_id = $this->category_id ?: null;
        $p->supplier_id = $this->supplier_id ?: null;
        $p->status      = $this->status;

        // وحدات
        $p->units_matrix      = $this->units_matrix;   // يتطلب cast في الموديل إلى array/json
        $p->sale_unit_key     = $this->sale_unit_key;
        $p->purchase_unit_key = $this->purchase_unit_key;

        // صلاحية
        $p->expiry_enabled  = $this->expiry_enabled ? 1 : 0;
        $p->expiry_unit     = $this->expiry_enabled ? $this->expiry_unit : null;
        $p->expiry_value    = $this->expiry_enabled ? ($this->expiry_value ?: null) : null;
        $p->expiry_weekdays = ($this->expiry_enabled && $this->expiry_unit === 'day') ? array_values($this->expiry_weekdays) : null;

        $p->save();

        session()->flash('success', __('pos.saved_success') ?? 'تم الحفظ بنجاح');

        // إعادة ضبط قيَم أساسية مع الاحتفاظ ببعض الافتراضات
        $this->reset([
            'sku','barcode','name','description','category_id','supplier_id','status',
            'image','image_path','units_matrix','sale_unit_key','purchase_unit_key',
            'expiry_enabled','expiry_unit','expiry_value','expiry_weekdays'
        ]);

        $this->status = 'active';
        $this->units_matrix = [
            'minor'  => ['unit_id' => null, 'cost' => null, 'price' => null, 'factor' => 1],
            'middle' => ['unit_id' => null, 'cost' => null, 'price' => null, 'factor' => 1],
            'major'  => ['unit_id' => null, 'cost' => null, 'price' => null, 'factor' => 1],
        ];
        $this->sale_unit_key = 'minor';
        $this->purchase_unit_key = 'minor';
        $this->expiry_unit = 'day';
        $this->expiry_enabled = false;
        $this->expiry_weekdays = [];
    }

    public function render()
    {
        return view('livewire.product.create', [
            'categories' => category::orderBy('name->' . app()->getLocale())->get(['id', 'name']),
            'units'      => $this->units = unit::orderBy('name->' . app()->getLocale())->get(['id', 'name']),
            'suppliers'  => supplier::orderBy('name->' . app()->getLocale())->get(['id', 'name']),
        ]);
    }
}
