<?php

namespace App\Http\Livewire\inventory\warehouses;

use Livewire\Component;
use App\models\inventory\warehouse;

class Create extends Component
{
    public $name = ['ar' => '', 'en' => ''];
    public $code = '';
    public $branch_id = null;
    public $status = 'active';

    protected $rules = [
        'name.ar'   => 'required|string|max:255',
        'name.en'   => 'required|string|max:255',
        'code'      => 'required|string|max:100|unique:warehouses,code',
        'branch_id' => 'nullable|integer',
        'status'    => 'required|in:active,inactive',
    ];

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

        warehouse::create([
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
        return view('livewire.inventory.warehouses.create');
    }
}
