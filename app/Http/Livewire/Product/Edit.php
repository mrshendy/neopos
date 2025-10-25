<?php

namespace App\Http\Livewire\product;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;

use App\models\product\product;
use App\models\product\category;
use App\models\unit\unit;
use App\models\supplier\supplier;

class Edit extends Component
{
    use WithFileUploads;

    public $row_id;

    // الأساسية
    public $sku, $barcode;
    public $name = ['ar' => '', 'en' => ''];
    public $description = ['ar' => '', 'en' => ''];
    public $category_id, $supplier_id;
    public $status = 'active';

    // الإضافية
    public $tax_rate = 0;
    public $opening_stock = 0;
    public $track_batch = false;
    public $track_serial = false;
    public $reorder_level = null;

    // الصورة
    public $image;
    public $image_path;

    // قوائم
    public $units = [];
    public $categories = [];
    public $suppliers = [];

    // الوحدات
    public $units_matrix = [
        'minor'  => ['unit_id' => null, 'cost' => null, 'price' => null, 'factor' => 1],
        'middle' => ['unit_id' => null, 'cost' => null, 'price' => null, 'factor' => 1],
        'major'  => ['unit_id' => null, 'cost' => null, 'price' => null, 'factor' => 1],
    ];
    public $sale_unit_key = 'minor';
    public $purchase_unit_key = 'minor';

    // الصلاحية
    public $expiry_enabled = false;
    public $expiry_unit = 'day';
    public $expiry_value;
    public $expiry_weekdays = [];

    protected $rules = [
        // sku: نحقن unique لاحقًا
        'barcode'                     => 'nullable|string|max:100',
        'name.ar'                     => 'required|string|max:255',
        'name.en'                     => 'required|string|max:255',
        'description.ar'              => 'nullable|string|max:5000',
        'description.en'              => 'nullable|string|max:5000',
        'category_id'                 => 'nullable|exists:categories,id',
        'supplier_id'                 => 'nullable|exists:suppliers,id',
        'status'                      => 'required|in:active,inactive',

        'tax_rate'                    => 'nullable|numeric|min:0|max:100',
        'opening_stock'               => 'nullable|integer|min:0',
        'track_batch'                 => 'boolean',
        'track_serial'                => 'boolean',
        'reorder_level'               => 'nullable|integer|min:0',

        'units_matrix.minor.unit_id'  => 'nullable|exists:unit,id',
        'units_matrix.middle.unit_id' => 'nullable|exists:unit,id',
        'units_matrix.major.unit_id'  => 'nullable|exists:unit,id',
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
        'name.ar.required'              => 'الاسم بالعربية مطلوب.',
        'name.en.required'              => 'الاسم بالإنجليزية مطلوب.',
        'units_matrix.*.unit_id.exists' => 'الوحدة المختارة غير موجودة.',
        'units_matrix.*.factor.min'     => 'معامل التحويل يجب ألا يقل عن 1.',
    ];

    protected function loadLookups(): void
    {
        $this->units = unit::orderBy('name->' . app()->getLocale())->get(['id','name']);
        $this->categories = category::orderBy('name->' . app()->getLocale())->get(['id','name']);
        $this->suppliers = supplier::orderBy('name->' . app()->getLocale())->get(['id','name']);
    }

    protected function asArray($val): array
    {
        if (is_array($val)) return $val;
        if (is_string($val) && strlen($val)) {
            $dec = json_decode($val, true);
            return is_array($dec) ? $dec : [];
        }
        return [];
    }

    protected function hydrateUnitsMatrix($raw): void
    {
        $base = [
            'minor'  => ['unit_id' => null, 'cost' => null, 'price' => null, 'factor' => 1],
            'middle' => ['unit_id' => null, 'cost' => null, 'price' => null, 'factor' => 1],
            'major'  => ['unit_id' => null, 'cost' => null, 'price' => null, 'factor' => 1],
        ];

        $src = $this->asArray($raw);
        foreach (['minor','middle','major'] as $lvl) {
            $row = (array)($src[$lvl] ?? []);
            $base[$lvl]['unit_id'] = isset($row['unit_id']) && $row['unit_id'] !== '' ? (string)$row['unit_id'] : null;
            $base[$lvl]['cost']    = isset($row['cost'])    && $row['cost'] !== ''    ? (float)$row['cost']  : null;
            $base[$lvl]['price']   = isset($row['price'])   && $row['price'] !== ''   ? (float)$row['price'] : null;
            $base[$lvl]['factor']  = isset($row['factor'])  && $row['factor']         ? (float)$row['factor']: 1.0;
        }
        $this->units_matrix = $base;

        foreach (['sale_unit_key','purchase_unit_key'] as $prop) {
            $k = $this->{$prop};
            if (!in_array($k, ['minor','middle','major'], true)) {
                $this->{$prop} = 'minor';
            }
            if (empty($this->units_matrix[$this->{$prop}]['unit_id'])) {
                foreach (['minor','middle','major'] as $try) {
                    if (!empty($this->units_matrix[$try]['unit_id'])) {
                        $this->{$prop} = $try; break;
                    }
                }
            }
        }
    }

    public function mount($id)
    {
        $this->loadLookups();

        $p = product::findOrFail($id);
        $this->row_id = $p->id;

        // الأساسية
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
        $this->status      = in_array($p->status, ['active','inactive'], true) ? $p->status : 'active';
        $this->image_path  = $p->image_path;

        // الإضافية
        $this->tax_rate      = $p->tax_rate ?? 0;
        $this->opening_stock = $p->opening_stock ?? 0;
        $this->track_batch   = (bool)($p->track_batch ?? 0);
        $this->track_serial  = (bool)($p->track_serial ?? 0);
        $this->reorder_level = $p->reorder_level ?? null;

        // الوحدات
        $this->sale_unit_key     = $p->sale_unit_key ?: 'minor';
        $this->purchase_unit_key = $p->purchase_unit_key ?: 'minor';
        $this->hydrateUnitsMatrix($p->units_matrix);

        // الصلاحية
        $this->expiry_enabled  = (bool)($p->expiry_enabled ?? false);
        $this->expiry_unit     = $this->expiry_enabled ? ($p->expiry_unit ?: 'day') : 'day';
        $this->expiry_value    = $this->expiry_enabled ? ($p->expiry_value ?: null) : null;
        $this->expiry_weekdays = ($this->expiry_enabled && is_array($p->expiry_weekdays)) ? array_values($p->expiry_weekdays) : [];
    }

    public function removeImage()
    {
        $this->image = null;
    }

    public function save()
    {
        // unique للـ sku مع تجاهل السجل الحالي
        $table = (new product)->getTable();
        $this->rules['sku'] = 'required|string|max:100|unique:' . $table . ',sku,' . $this->row_id;
        $this->validate();

        foreach ([$this->sale_unit_key, $this->purchase_unit_key] as $k) {
            if (empty($this->units_matrix[$k]['unit_id'])) {
                $this->addError('sale_purchase_mismatch', 'وحدة البيع/الشراء يجب أن تكون من الوحدات المحددة بالأعلى.');
                return;
            }
        }

        $p = product::findOrFail($this->row_id);

        if ($this->image) {
            $this->image_path = $this->image->store('products', 'public');
            $p->image_path = '/' . $this->image_path;
        }

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

        // ترجمات
        $p->setTranslation('name', 'ar', $this->name['ar']);
        $p->setTranslation('name', 'en', $this->name['en']);
        $p->setTranslation('description', 'ar', $this->description['ar'] ?? '');
        $p->setTranslation('description', 'en', $this->description['en'] ?? '');

        // تهيئة وحدات قبل الحفظ
        foreach (['minor','middle','major'] as $lvl) {
            $row = $this->units_matrix[$lvl] ?? [];
            $this->units_matrix[$lvl] = [
                'unit_id' => isset($row['unit_id']) && $row['unit_id'] !== '' ? (int)$row['unit_id'] : null,
                'cost'    => isset($row['cost'])   && $row['cost'] !== ''   ? (float)$row['cost']  : null,
                'price'   => isset($row['price'])  && $row['price'] !== ''  ? (float)$row['price'] : null,
                'factor'  => isset($row['factor']) && $row['factor']        ? (float)$row['factor']: 1.0,
            ];
        }
        $p->units_matrix      = $this->units_matrix;
        $p->sale_unit_key     = $this->sale_unit_key;
        $p->purchase_unit_key = $this->purchase_unit_key;

        // الصلاحية
        $p->expiry_enabled  = $this->expiry_enabled ? 1 : 0;
        $p->expiry_unit     = $this->expiry_enabled ? $this->expiry_unit : null;
        $p->expiry_value    = $this->expiry_enabled ? ($this->expiry_value ?: null) : null;
        $p->expiry_weekdays = ($this->expiry_enabled && $this->expiry_unit === 'day') ? array_values($this->expiry_weekdays) : null;

        $p->save();

        session()->flash('success', __('pos.saved_success') ?? 'تم التحديث بنجاح');
        return redirect()->route('product.index');
    }

    public function render()
    {
        return view('livewire.product.edit', [
            'categories' => $this->categories,
            'units'      => $this->units,
            'suppliers'  => $this->suppliers,
        ]);
    }
}
