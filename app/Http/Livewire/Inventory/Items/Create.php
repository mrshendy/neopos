<?php

namespace App\Http\Livewire\inventory\products;

use Livewire\Component;
use App\models\inventory\item;

class Create extends Component
{
    public $name = ['ar' => '', 'en' => ''];
    public $sku = '';
    public $uom = 'unit';
    public $track_batch = true;
    public $track_serial = false;
    public $status = 'active';

    protected $rules = [
        'name.ar'      => 'required|string|max:255',
        'name.en'      => 'required|string|max:255',
        'sku'          => 'required|string|max:100|unique:products,sku',
        'uom'          => 'required|string|max:50',
        'track_batch'  => 'boolean',
        'track_serial' => 'boolean',
        'status'       => 'required|in:active,inactive',
    ];

    protected $messages = [
        'name.ar.required' => 'الاسم بالعربية مطلوب',
        'name.en.required' => 'الاسم بالإنجليزية مطلوب',
        'sku.required'     => 'كود الصنف (SKU) مطلوب',
        'sku.unique'       => 'كود الصنف مستخدم من قبل',
        'uom.required'     => 'وحدة القياس مطلوبة',
        'status.in'        => 'حالة غير صالحة',
    ];

    public function save()
    {
        $this->validate();

        item::create([
            'name'         => $this->name,
            'sku'          => $this->sku,
            'uom'          => $this->uom,
            'track_batch'  => (bool)$this->track_batch,
            'track_serial' => (bool)$this->track_serial,
            'status'       => $this->status,
        ]);

        session()->flash('success', trans('pos.saved_success'));
        return redirect()->route('inventory.manage');
    }

    public function render()
    {
        return view('livewire.inventory.products.create');
    }
}
