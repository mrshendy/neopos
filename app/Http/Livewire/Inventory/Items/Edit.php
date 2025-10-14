<?php

namespace App\Http\Livewire\inventory\items;

use Livewire\Component;
use App\models\inventory\item;

class Edit extends Component
{
    public $item_id;
    public $name = ['ar' => '', 'en' => ''];
    public $sku = '';
    public $uom = 'unit';
    public $track_batch = true;
    public $track_serial = false;
    public $status = 'active';

    public function mount($item_id)
    {
        $this->item_id = $item_id;
        $it = item::findOrFail($item_id);

        $this->name         = is_array($it->name) ? $it->name : (array)$it->name;
        $this->sku          = $it->sku;
        $this->uom          = $it->uom;
        $this->track_batch  = (bool)$it->track_batch;
        $this->track_serial = (bool)$it->track_serial;
        $this->status       = $it->status;
    }

    protected function rules()
    {
        return [
            'name.ar'      => 'required|string|max:255',
            'name.en'      => 'required|string|max:255',
            'sku'          => 'required|string|max:100|unique:items,sku,' . $this->item_id,
            'uom'          => 'required|string|max:50',
            'track_batch'  => 'boolean',
            'track_serial' => 'boolean',
            'status'       => 'required|in:active,inactive',
        ];
    }

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

        $it = item::findOrFail($this->item_id);
        $it->update([
            'name'         => $this->name,
            'sku'          => $this->sku,
            'uom'          => $this->uom,
            'track_batch'  => (bool)$this->track_batch,
            'track_serial' => (bool)$this->track_serial,
            'status'       => $this->status,
        ]);

        session()->flash('success', trans('pos.saved_success'));
        return redirect()->route('inventory.items.index');
    }

    public function render()
    {
        return view('livewire.inventory.items.edit');
    }
}
