<?php

namespace App\Http\Livewire\general\branches;

use Livewire\Component;
use App\models\general\branch;
use App\models\inventory\warehouse;

class create extends Component
{
    public $name_ar = '';
    public $name_en = '';
    public $address = '';
    public $status  = 'active';

    // اختياري: إنشاء مستودع افتراضي مربوط بالفرع
    public $create_default_warehouse = false;
    public $warehouse_name_ar = 'المستودع الرئيسي';
    public $warehouse_name_en = 'Main Warehouse';

    protected $rules = [
        'name_ar' => 'required|string|max:255',
        'name_en' => 'required|string|max:255',
        'address' => 'nullable|string|max:255',
        'status'  => 'required|in:active,inactive',
        'create_default_warehouse' => 'boolean',
        'warehouse_name_ar' => 'required_if:create_default_warehouse,true|string|max:255',
        'warehouse_name_en' => 'required_if:create_default_warehouse,true|string|max:255',
    ];

    protected $messages = [
        'name_ar.required' => 'الاسم العربي مطلوب',
        'name_en.required' => 'الاسم الإنجليزي مطلوب',
        'status.in'        => 'حقل الحالة غير صحيح',
        'warehouse_name_ar.required_if' => 'اسم المستودع العربي مطلوب عند اختيار إنشاء مستودع افتراضي',
        'warehouse_name_en.required_if' => 'اسم المستودع الإنجليزي مطلوب عند اختيار إنشاء مستودع افتراضي',
    ];

    public function save()
    {
        $this->validate();

        $branch = branch::create([
            'name'    => ['ar' => $this->name_ar, 'en' => $this->name_en],
            'address' => $this->address ?: null,
            'status'  => $this->status,
        ]);

        if ($this->create_default_warehouse) {
            warehouse::create([
                'name'      => ['ar' => $this->warehouse_name_ar, 'en' => $this->warehouse_name_en],
                'branch_id' => $branch->id,
                'status'    => 'active',
            ]);
        }

        session()->flash('success', 'تم إنشاء الفرع بنجاح');
        return redirect()->route('branches.index');
    }

    public function render()
    {
        return view('general.branches.create')->layout('layouts.master');
    }
}
