<?php

namespace App\Http\Livewire\Product;

use Livewire\Component;
use App\models\product\product;
use App\models\product\unit;
use App\models\product\category;

class Create extends Component
{
    public $sku, $barcode, $name = ['ar'=>'','en'=>''], $description = ['ar'=>'','en'=>''];
    public $unit_id, $category_id, $tax_rate=0, $opening_stock=0, $status='active';

    protected $rules = [
        'sku'                       => 'required|string|max:100|unique:products,sku',
        'barcode'                   => 'nullable|string|max:100|unique:products,barcode',
        'name.ar'                   => 'required|string|max:255',
        'name.en'                   => 'required|string|max:255',
        'description.ar'            => 'nullable|string|max:1000',
        'description.en'            => 'nullable|string|max:1000',
        'unit_id'                   => 'required|exists:units,id',
        'category_id'               => 'nullable|exists:categories,id',
        'tax_rate'                  => 'numeric|min:0|max:100',
        'opening_stock'             => 'integer|min:0',
        'status'                    => 'required|in:active,inactive',
    ];

    protected $messages = [
        'sku.required'              => 'الرجاء إدخال كود المنتج (SKU).',
        'sku.unique'                => 'هذا الـ SKU مستخدم من قبل.',
        'barcode.unique'            => 'هذا الباركود مستخدم من قبل.',
        'name.ar.required'          => 'اسم المنتج بالعربية مطلوب.',
        'name.en.required'          => 'اسم المنتج بالإنجليزية مطلوب.',
        'unit_id.required'          => 'اختر وحدة القياس.',
    ];

    public function save(){
        $this->validate();
        product::create([
            'sku'=>$this->sku, 'barcode'=>$this->barcode,
            'name'=>$this->name, 'description'=>$this->description,
            'unit_id'=>$this->unit_id, 'category_id'=>$this->category_id,
            'tax_rate'=>$this->tax_rate, 'opening_stock'=>$this->opening_stock,
            'status'=>$this->status
        ]);
        session()->flash('success', __('pos.msg_saved_ok'));
        return redirect()->route('products.index');
    }

    public function render(){
        return view('livewire.product.create',[
            'units'=> unit::select('id','name')->get(),
            'categories'=> category::select('id','name')->get(),
        ]);
    }
}
