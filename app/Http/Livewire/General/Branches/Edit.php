<?php

namespace App\Http\Livewire\general\branches;

use Livewire\Component;
use App\models\general\branch;

class edit extends Component
{
    public $branch_id;
    public $name_ar;
    public $name_en;
    public $address;
    public $status = 'active';

    protected $rules = [
        'name_ar' => 'required|string|max:255',
        'name_en' => 'required|string|max:255',
        'address' => 'nullable|string|max:255',
        'status'  => 'required|in:active,inactive',
    ];

    protected $messages = [
        'name_ar.required' => 'الاسم العربي مطلوب',
        'name_en.required' => 'الاسم الإنجليزي مطلوب',
        'status.in'        => 'حقل الحالة غير صحيح',
    ];

    public function mount($branch_id)
    {
        $this->branch_id = $branch_id;
        $b = branch::findOrFail($branch_id);
        $this->name_ar = $b->getTranslation('name', 'ar');
        $this->name_en = $b->getTranslation('name', 'en');
        $this->address = $b->address;
        $this->status  = $b->status;
    }

    public function update()
    {
        $this->validate();

        $b = branch::findOrFail($this->branch_id);
        $b->name    = ['ar' => $this->name_ar, 'en' => $this->name_en];
        $b->address = $this->address ?: null;
        $b->status  = $this->status;
        $b->save();

        session()->flash('success', 'تم تحديث بيانات الفرع بنجاح');
        return redirect()->route('branches.index');
    }

    public function render()
    {
        return view('general.branches.edit')->layout('layouts.master');
    }
}
