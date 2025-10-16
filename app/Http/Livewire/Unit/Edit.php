<?php

namespace App\Http\Livewire\unit;

use Livewire\Component;
use App\models\unit\unit;

class edit extends Component
{
    public $unit_id;
    public $name = ['ar' => '', 'en' => ''];
    public $description = ['ar' => '', 'en' => ''];
    public $level = 'minor';
    public $status = 'active';

    protected $rules = [
        'name.ar'        => 'required|string|max:255',
        'name.en'        => 'required|string|max:255',
        'description.ar' => 'nullable|string|max:1000',
        'description.en' => 'nullable|string|max:1000',
        'level'          => 'required|in:minor,middle,major',
        'status'         => 'required|in:active,inactive',
    ];

    protected $messages = [
        'name.ar.required' => 'الاسم العربي حقل إلزامي.',
        'name.en.required' => 'الاسم الإنجليزي حقل إلزامي.',
        'level.required'   => 'يجب اختيار مستوى الوحدة.',
        'status.required'  => 'يجب اختيار حالة الوحدة.',
    ];

    public function mount($id)
    {
        $this->unit_id = $id;
        $u = unit::findOrFail($id);

        $this->name = $u->getTranslations('name');
        $this->description = $u->getTranslations('description') ?? ['ar' => '', 'en' => ''];
        $this->level = $u->level;
        $this->status = $u->status;
    }

    public function save()
    {
        $this->validate();

        $u = unit::findOrFail($this->unit_id);
        $u->setTranslations('name', $this->name);
        $u->setTranslations('description', $this->description);
        $u->level  = $this->level;
        $u->status = $this->status;
        $u->save();

        session()->flash('success', __('pos.msg_updated'));
        return redirect()->route('units.index');
    }

    public function render()
    {
        return view('livewire.unit.edit');
    }
}
