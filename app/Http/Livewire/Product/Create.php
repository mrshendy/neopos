<?php

namespace App\Http\Livewire\product;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\models\product\product;
use App\models\product\category;
use App\models\unit\unit;
use App\models\supplier\supplier;

class Create extends Component
{
    use WithFileUploads;

    // الأساسية
    public $sku, $barcode;
    public $name = ['ar' => '', 'en' => ''];
    public $description = ['ar' => '', 'en' => ''];
    public $category_id, $supplier_id;
    public $status = 'active';

    // الحقول الإضافية من الجدول
    public $tax_rate = 0;           // كنسبة %
    public $opening_stock = 0;      // مخزون افتتاحي
    public $track_batch = false;
    public $track_serial = false;
    public $reorder_level = null;

    // الصورة
    public $image;       // TemporaryUploadedFile
    public $image_path;  // مسار محفوظ بعد الرفع

    // قوائم
    public $units = [];
    public $categories = [];
    public $suppliers = [];

    // مصفوفة الوحدات
    public $units_matrix = [
        'minor'  => ['unit_id' => null, 'cost' => null, 'price' => null, 'factor' => 1],
        'middle' => ['unit_id' => null, 'cost' => null, 'price' => null, 'factor' => 1],
        'major'  => ['unit_id' => null, 'cost' => null, 'price' => null, 'factor' => 1],
    ];
    public $sale_unit_key = 'minor';
    public $purchase_unit_key = 'minor';

    // الصلاحية
    public $expiry_enabled = false;
    public $expiry_unit = 'day';  // day|month|year
    public $expiry_value;
    public $expiry_weekdays = []; // يُستخدم فقط لو عندك منطق خاص للأيام

    protected $rules = [
        'sku'                        => 'required|string|max:100|unique:products,sku',
        'barcode'                    => 'nullable|string|max:100',
        'name.ar'                    => 'required|string|max:255',
        'name.en'                    => 'required|string|max:255',
        'description.ar'             => 'nullable|string|max:5000',
        'description.en'             => 'nullable|string|max:5000',
        'category_id'                => 'nullable|exists:categories,id',
        'supplier_id'                => 'nullable|exists:suppliers,id',
        'status'                     => 'required|in:active,inactive',

        'tax_rate'                   => 'nullable|numeric|min:0|max:100',
        'opening_stock'              => 'nullable|integer|min:0',
        'track_batch'                => 'boolean',
        'track_serial'               => 'boolean',
        'reorder_level'              => 'nullable|integer|min:0',

        'units_matrix.minor.unit_id'  => 'nullable|exists:unit,id',
        'units_matrix.middle.unit_id' => 'nullable|exists:unit,id',
        'units_matrix.major.unit_id'  => 'nullable|exists:unit,id',
        'units_matrix.*.cost'         => 'nullable|numeric|min:0',
        'units_matrix.*.price'        => 'nullable|numeric|min:0',
        'units_matrix.*.factor'       => 'nullable|numeric|min:1',

        'sale_unit_key'              => 'required|in:minor,middle,major',
        'purchase_unit_key'          => 'required|in:minor,middle,major',

        'image'                      => 'nullable|image|max:2048|mimes:jpg,jpeg,png,webp',

        'expiry_enabled'             => 'boolean',
        'expiry_unit'                => 'nullable|in:day,month,year',
        'expiry_value'               => 'nullable|integer|min:1',
        'expiry_weekdays'            => 'array',
    ];

    protected $messages = [
        'sku.required'               => 'كود الصنف مطلوب.',
        'sku.unique'                 => 'كود الصنف مستخدم من قبل.',
        'name.ar.required'           => 'الاسم بالعربية مطلوب.',
        'name.en.required'           => 'الاسم بالإنجليزية مطلوب.',
        'units_matrix.*.unit_id.exists' => 'الوحدة المختارة غير موجودة.',
        'units_matrix.*.factor.min'  => 'معامل التحويل يجب ألا يقل عن 1.',
    ];

    protected function loadLookups(): void
    {
        $this->units = unit::orderBy('name->' . app()->getLocale())->get(['id','name']);
        $this->categories = category::orderBy('name->' . app()->getLocale())->get(['id','name']);
        $this->suppliers = supplier::orderBy('name->' . app()->getLocale())->get(['id','name']);
    }

    public function mount(): void
    {
        $this->loadLookups();
        // قيم افتراضية
        $this->status = 'active';
        $this->sale_unit_key = 'minor';
        $this->purchase_unit_key = 'minor';
        $this->tax_rate = 0;
        $this->opening_stock = 0;
        $this->track_batch = false;
        $this->track_serial = false;
    }

    /** تأكيد أن وحدتي البيع/الشراء ضمن الوحدات المحددة */
    protected function ensureSalePurchaseKeys(): void
    {
        foreach ([$this->sale_unit_key, $this->purchase_unit_key] as $k) {
            if (empty($this->units_matrix[$k]['unit_id'])) {
                $this->addError('sale_purchase_mismatch', 'وحدة البيع/الشراء يجب أن تكون من الوحدات المحددة بالأعلى.');
            }
        }
    }

    /** تهيئة أنواع بيانات units_matrix قبل الحفظ */
    protected function normalizeMatrixForSave(): void
    {
        foreach (['minor','middle','major'] as $lvl) {
            $row = (array)($this->units_matrix[$lvl] ?? []);
            $this->units_matrix[$lvl] = [
                'unit_id' => isset($row['unit_id']) && $row['unit_id'] !== '' ? (int)$row['unit_id'] : null,
                'cost'    => isset($row['cost'])   && $row['cost'] !== ''   ? (float)$row['cost']  : null,
                'price'   => isset($row['price'])  && $row['price'] !== ''  ? (float)$row['price'] : null,
                'factor'  => isset($row['factor']) && $row['factor']        ? (float)$row['factor']: 1.0,
            ];
        }
    }

    public function save()
    {
        $this->validate();
        $this->ensureSalePurchaseKeys();
        if ($this->getErrorBag()->has('sale_purchase_mismatch')) {
            return;
        }

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('products', 'public');
        }

        $this->normalizeMatrixForSave();

        DB::transaction(function () use ($imagePath) {
            $p = new product();

            $p->sku         = $this->sku;
            $p->barcode     = $this->barcode;
            $p->category_id = $this->category_id ?: null;
            $p->supplier_id = $this->supplier_id ?: null;
            $p->status      = $this->status;

            $p->tax_rate       = $this->tax_rate ?? 0;
            $p->opening_stock  = $this->opening_stock ?? 0;
            $p->track_batch    = $this->track_batch ? 1 : 0;
            $p->track_serial   = $this->track_serial ? 1 : 0;
            $p->reorder_level  = $this->reorder_level ?? null;

            if ($imagePath) {
                $p->image_path = '/' . $imagePath;
            }

            // ترجمات
            $p->setTranslation('name', 'ar', $this->name['ar']);
            $p->setTranslation('name', 'en', $this->name['en']);
            $p->setTranslation('description', 'ar', $this->description['ar'] ?? '');
            $p->setTranslation('description', 'en', $this->description['en'] ?? '');

            // الوحدات
            $p->units_matrix      = $this->units_matrix;
            $p->sale_unit_key     = $this->sale_unit_key;
            $p->purchase_unit_key = $this->purchase_unit_key;

            // الصلاحية
            $p->expiry_enabled  = $this->expiry_enabled ? 1 : 0;
            $p->expiry_unit     = $this->expiry_enabled ? $this->expiry_unit : null;
            $p->expiry_value    = $this->expiry_enabled ? ($this->expiry_value ?: null) : null;
            $p->expiry_weekdays = ($this->expiry_enabled && $this->expiry_unit === 'day') ? array_values($this->expiry_weekdays) : null;

            $p->save();
        });

        session()->flash('success', __('pos.saved_success') ?? 'تم الحفظ بنجاح');
        return redirect()->route('product.index');
    }

    public function render()
    {
        return view('livewire.product.create', [
            'categories' => $this->categories,
            'units'      => $this->units,
            'suppliers'  => $this->suppliers,
        ]);
    }
}
