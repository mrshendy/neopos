<?php

namespace App\Http\Livewire\inventory\warehouses;

use Livewire\Component;
use App\models\inventory\warehouse;

class Edit extends Component
{
    public $warehouse_id;
    public $name = ['ar' => '', 'en' => ''];
    public $code = '';
    public $branch_id = null;
    public $status = 'active';

    public function mount($warehouse_id)
    {
        $this->warehouse_id = $warehouse_id;
        $w = warehouse::findOrFail($warehouse_id);

        $this->name      = is_array($w->name) ? $w->name : (array)$w->name;
        $this->code      = $w->code;
        $this->branch_id = $w->branch_id;
        $this->status    = $w->status;
    }

    protected function rules()
    {
        return [
            'name.ar'   => 'required|string|max:255',
            'name.en'   => 'required|string|max:255',
            'code'      => 'required|string|max:100|unique:warehouses,code,' . $this->warehouse_id,
            'branch_id' => 'nullable|integer',
            'status'    => 'required|in:active,inactive',
        ];
    }

    protected $messages = [
        'name.ar.required' => 'اسم المخزن بالعربية مطلوب',
        'name.en.required' => 'اسم المخزن بالإنجليزية مطلوب',
        'code.required'    => 'كود المخزن مطلوب',
        'code.unique'      => 'كود المخزن مستخدم من قبل',
        'status.in'        => 'حالة غير صالحة',
    ];

    public function save()
    {
        $this->validate();

        $w = warehouse::findOrFail($this->warehouse_id);
        $w->update([
            'name'      => $this->name,
            'code'      => $this->code,
            'branch_id' => $this->branch_id ?: null,
            'status'    => $this->status,
        ]);

        session()->flash('success', trans('pos.saved_success'));
        return redirect()->route('inventory.warehouses.index');
    }

    public function render()
    {
        return view('livewire.inventory.warehouses.edit');
    }
}
