<?php

namespace App\Http\Livewire\product;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\models\product\product;
use App\models\product\category;
use App\models\product\unit;

class Create extends Component
{
    use WithFileUploads;

    public $sku, $barcode, $name = ['ar'=>'','en'=>''], $description = ['ar'=>'','en'=>''];
    public $unit_id, $category_id, $tax_rate = 0, $status = 'active';
    public $track_batch = false, $track_serial = false, $reorder_level;
    public $image;       // TemporaryUploadedFile
    public $image_path;  // stored path after save

    protected $rules = [
        'sku'                 => 'required|string|max:100|unique:products,sku',
        'barcode'             => 'nullable|string|max:100',
        'name.ar'             => 'required|string|max:255',
        'name.en'             => 'required|string|max:255',
        'description.ar'      => 'nullable|string|max:5000',
        'description.en'      => 'nullable|string|max:5000',
        'unit_id'             => 'nullable|exists:units,id',
        'category_id'         => 'nullable|exists:categories,id',
        'tax_rate'            => 'nullable|numeric|min:0|max:999999.999',
        'status'              => 'required|in:active,inactive',
        'track_batch'         => 'boolean',
        'track_serial'        => 'boolean',
        'reorder_level'       => 'nullable|integer|min:0',
        'image'               => 'nullable|image|max:2048|mimes:jpg,jpeg,png,webp',
    ];

    protected $messages = [
        'sku.required'     => 'كود المنتج مطلوب.',
        'sku.unique'       => 'كود المنتج مستخدم من قبل.',
        'name.ar.required' => 'اسم المنتج بالعربية مطلوب.',
        'name.en.required' => 'اسم المنتج بالإنجليزية مطلوب.',
        'image.image'      => 'الملف يجب أن يكون صورة.',
        'image.max'        => 'الحد الأقصى للصورة 2MB.',
    ];

    public function removeImage()
    {
        $this->image = null;
        $this->image_path = null;
    }

    public function save()
    {
        $this->validate();

        $p = new product();

        if ($this->image) {
            $this->image_path = $this->image->store('products', 'public');
            $p->image_path = $this->image_path;
        }

        $p->sku = $this->sku;
        $p->barcode = $this->barcode;
        $p->setTranslation('name','ar',$this->name['ar']);
        $p->setTranslation('name','en',$this->name['en']);
        $p->setTranslation('description','ar',$this->description['ar'] ?? '');
        $p->setTranslation('description','en',$this->description['en'] ?? '');
        $p->unit_id = $this->unit_id ?: null;
        $p->category_id = $this->category_id ?: null;
        $p->tax_rate = $this->tax_rate ?: 0;
        $p->status = $this->status;
        $p->track_batch = $this->track_batch ? 1 : 0;
        $p->track_serial = $this->track_serial ? 1 : 0;
        $p->reorder_level = $this->reorder_level ?: null;
        $p->save();

        session()->flash('success', __('pos.saved_success') ?? 'تم الحفظ بنجاح');

        // إعادة ضبط الحقول بعد الإنشاء
        $this->reset(['sku','barcode','name','description','unit_id','category_id','tax_rate','status','track_batch','track_serial','reorder_level','image','image_path']);
        $this->name = ['ar'=>'','en'=>''];
        $this->description = ['ar'=>'','en'=>''];
        $this->status = 'active';
    }

    public function render()
    {
        return view('livewire.product.create', [
            'categories' => category::orderByDesc('id')->get(['id','name']),
            'units'      => unit::orderByDesc('id')->get(['id','name']),
        ]);
    }
}
