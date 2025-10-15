<?php

namespace App\Http\Livewire\product;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\models\product\product;
use App\models\product\category;
use App\models\product\unit;

class Edit extends Component
{
    use WithFileUploads;

    public $product_id;

    public $sku, $barcode, $name = ['ar'=>'','en'=>''], $description = ['ar'=>'','en'=>''];
    public $unit_id, $category_id, $tax_rate = 0, $status = 'active';
    public $track_batch = false, $track_serial = false, $reorder_level;
    public $image;       // TemporaryUploadedFile
    public $image_path;  // current path or newly uploaded

    protected function rules()
    {
        return [
            'sku'                 => 'required|string|max:100|unique:products,sku,'.$this->product_id,
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
    }

    public function mount($id)
    {
        $this->product_id = (int) $id;
        $p = product::findOrFail($this->product_id);

        $this->sku = $p->sku;
        $this->barcode = $p->barcode;
        $this->name = ['ar'=>$p->getTranslation('name','ar') ?? '','en'=>$p->getTranslation('name','en') ?? ''];
        $this->description = ['ar'=>$p->getTranslation('description','ar') ?? '','en'=>$p->getTranslation('description','en') ?? ''];
        $this->unit_id = $p->unit_id;
        $this->category_id = $p->category_id;
        $this->tax_rate = $p->tax_rate ?? 0;
        $this->status = $p->status ?? 'active';
        $this->track_batch = (bool)($p->track_batch ?? false);
        $this->track_serial = (bool)($p->track_serial ?? false);
        $this->reorder_level = $p->reorder_level;
        $this->image_path = $p->image_path;
    }

    public function removeImage()
    {
        $this->image = null;
        $this->image_path = null; // سيتم حذف القديمة عند الحفظ لو كانت موجودة
    }

    public function save()
    {
        $this->validate();

        $p = product::findOrFail($this->product_id);

        if ($this->image) {
            if ($p->image_path && Storage::disk('public')->exists($p->image_path)) {
                Storage::disk('public')->delete($p->image_path);
            }
            $this->image_path = $this->image->store('products','public');
            $p->image_path = $this->image_path;
        } else {
            if (!$this->image_path && $p->image_path && Storage::disk('public')->exists($p->image_path)) {
                Storage::disk('public')->delete($p->image_path);
            }
            $p->image_path = $this->image_path; // قد تكون null
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
    }

    public function render()
    {
        return view('livewire.product.edit', [
            'categories' => category::orderByDesc('id')->get(['id','name']),
            'units'      => unit::orderByDesc('id')->get(['id','name']),
        ]);
    }
}
