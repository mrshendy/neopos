<?php

namespace App\Http\Livewire\General\Branches;

use Livewire\Component;
use App\Models\General\Branch;

class Create extends Component
{
    public $name = '';
    public $address = '';
    public $status = 1;

    protected $rules = [
        'name'    => 'required|string|min:2|max:190',
        'address' => 'nullable|string|max:255',
        'status'  => 'required|boolean',
    ];

    protected $messages = [
        'name.required' => 'اسم الفرع مطلوب',
        'name.min'      => 'اسم الفرع قصير جداً',
        'name.max'      => 'اسم الفرع كبير جداً',
        'status.required' => 'اختَر الحالة',
        'status.boolean'  => 'قيمة الحالة غير صحيحة',
    ];

    public function save()
    {
        $this->validate();

        Branch::create([
            'name'    => $this->name,
            'address' => $this->address,
            'status'  => (bool) $this->status,
        ]);

        session()->flash('success', 'تم إنشاء الفرع بنجاح');
        return redirect()->route('branches.index');
    }

    public function render()
    {
        return view('livewire.general.branches.create');
    }
}
