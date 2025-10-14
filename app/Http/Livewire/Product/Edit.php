<?php

namespace App\Http\Livewire\Product;

use Livewire\Component;
use Illuminate\Validation\Rule;
use App\models\product\product;
use App\models\product\unit;
use App\models\product\category;

class Edit extends Component
{
    // المفتاح الأساسي
    public $product_id;

    // الحقول
    public $sku, $barcode;
    public $name = ['ar' => '', 'en' => ''];
    public $description = ['ar' => '', 'en' => ''];
    public $unit_id, $category_id;
    public $tax_rate = 0, $opening_stock = 0;
    public $status = 'active';

    // قوائم منسدلة
    public $units = [];
    public $categories = [];

    // القواعد
    protected function rules()
    {
        return [
            'sku'           => [
                'required','string','max:100',
                Rule::unique('products','sku')
                    ->ignore($this->product_id)
                    ->whereNull('deleted_at')
            ],
            'barcode'       => [
                'nullable','string','max:100',
                Rule::unique('products','barcode')
                    ->ignore($this->product_id)
                    ->whereNull('deleted_at')
            ],
            'name.ar'       => 'required|string|max:255',
            'name.en'       => 'required|string|max:255',
            'description.ar'=> 'nullable|string|max:1000',
            'description.en'=> 'nullable|string|max:1000',
            'unit_id'       => 'required|exists:units,id',
            'category_id'   => 'nullable|exists:categories,id',
            'tax_rate'      => 'numeric|min:0|max:100',
            'opening_stock' => 'integer|min:0',
            'status'        => 'required|in:active,inactive',
        ];
    }

    protected $messages = [
        'sku.required'        => 'الرجاء إدخال كود المنتج (SKU).',
        'sku.unique'          => 'هذا الـ SKU مستخدم من قبل.',
        'barcode.unique'      => 'هذا الباركود مستخدم من قبل.',
        'name.ar.required'    => 'اسم المنتج بالعربية مطلوب.',
        'name.en.required'    => 'اسم المنتج بالإنجليزية مطلوب.',
        'unit_id.required'    => 'اختر وحدة القياس.',
        'unit_id.exists'      => 'وحدة القياس غير صحيحة.',
        'category_id.exists'  => 'الفئة غير صحيحة.',
        'tax_rate.min'        => 'نسبة الضريبة لا تقل عن 0.',
        'tax_rate.max'        => 'نسبة الضريبة لا تتجاوز 100.',
        'opening_stock.min'   => 'الرصيد الافتتاحي لا يقل عن 0.',
        'status.in'           => 'حالة غير صحيحة.',
    ];

    public function mount($id)
    {
        // تحميل القوائم
        $this->units = unit::select('id','name')->orderBy('id','desc')->get();
        $this->categories = category::select('id','name')->orderBy('id','desc')->get();

        // جلب المنتج
        $row = product::find($id);
        if (!$row) {
            session()->flash('error', __('pos.no_data'));
            return redirect()->route('products.index');
        }

        $this->product_id    = $row->id;
        $this->sku           = $row->sku;
        $this->barcode       = $row->barcode;
        $this->name          = [
            'ar' => $row->getTranslation('name','ar'),
            'en' => $row->getTranslation('name','en'),
        ];
        $this->description   = [
            'ar' => $row->getTranslation('description','ar') ?? '',
            'en' => $row->getTranslation('description','en') ?? '',
        ];
        $this->unit_id       = $row->unit_id;
        $this->category_id   = $row->category_id;
        $this->tax_rate      = $row->tax_rate;
        $this->opening_stock = $row->opening_stock;
        $this->status        = $row->status;
    }

    public function save()
    {
        $this->validate();

        $row = product::findOrFail($this->product_id);

        $row->update([
            'sku'           => $this->sku,
            'barcode'       => $this->barcode,
            'name'          => $this->name,
            'description'   => $this->description,
            'unit_id'       => $this->unit_id,
            'category_id'   => $this->category_id,
            'tax_rate'      => $this->tax_rate,
            'opening_stock' => $this->opening_stock,
            'status'        => $this->status,
        ]);

        session()->flash('success', __('pos.msg_saved_ok'));
        return redirect()->route('products.index');
    }

    public function render()
    {
        return view('livewire.product.edit');
    }
}
