<?php

namespace App\Http\Livewire\unit;

use Livewire\Component;
use App\models\unit\unit;

class edit extends Component
{
    public $unit_id;

    /** @var array{name:string, en:string} */
    public $name        = ['ar' => '', 'en' => ''];

    /** @var array{name:string, en:string} */
    public $description = ['ar' => '', 'en' => ''];

    public $level  = 'minor';
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

    /** يضمن وجود مفاتيح ar/en */
    private function withLocaleDefaults(?array $arr): array
    {
        return array_merge(['ar' => '', 'en' => ''], (array) $arr);
    }

    public function mount($id)
    {
        $this->unit_id = (int) $id;
        $u = unit::findOrFail($this->unit_id);

        // مهم: دمج الافتراضيات قبل الإسناد
        $this->name        = $this->withLocaleDefaults($u->getTranslations('name'));
        $this->description = $this->withLocaleDefaults($u->getTranslations('description'));
        $this->level       = $u->level;
        $this->status      = $u->status;
    }

    public function save()
    {
        // كمان هنا لو المستخدم مسح حقلًا نضمن المفاتيح موجودة
        $this->name        = $this->withLocaleDefaults($this->name);
        $this->description = $this->withLocaleDefaults($this->description);

        $this->validate();

        $u = unit::findOrFail($this->unit_id);
        $u->setTranslations('name',        ['ar' => (string)$this->name['ar'], 'en' => (string)$this->name['en']]);
        $u->setTranslations('description', ['ar' => (string)$this->description['ar'], 'en' => (string)$this->description['en']]);
        $u->level  = $this->level;
        $u->status = $this->status;
        $u->save();

        session()->flash('success', __('pos.msg_updated') ?: 'تم التحديث بنجاح.');
        return redirect()->route('units.index');
    }

    public function render()
    {
        return view('livewire.unit.edit');
    }
}
