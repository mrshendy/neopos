<?php

namespace App\Http\Livewire\Inventory\Units;

use Livewire\Component;
use App\models\product\unit;

class Form extends Component
{
    public ?unit $unit = null;  // يُملأ في حالة التعديل
    public $unitId = null;      // يُمرّر من الراوت (اختياري)

    // حقول
    public $code = '';
    public $name = ['ar'=>'','en'=>''];
    public $abbreviation = null;        // VARCHAR عادي
    public $kind = 'minor';             // الافتراضي صغرى
    public $parent_id = null;
    public $ratio_to_parent = null;
    public $is_default_minor = true;    // الافتراضي true للصغرى
    public $status = 'active';

    public $majors = [];

    protected function rules()
    {
        $id = $this->unit?->id;
        return [
            'code'            => ['required','string','max:50','unique:units,code'.($id?",$id":'')],
            'name.ar'         => ['required','string','max:255'],
            'name.en'         => ['required','string','max:255'],
            'abbreviation'    => ['nullable','string','max:50'],
            'kind'            => ['required','in:major,minor'],
            'parent_id'       => ['nullable','integer','exists:units,id'],
            'ratio_to_parent' => ['nullable','numeric','gt:0'],
            'is_default_minor'=> ['boolean'],
            'status'          => ['required','in:active,inactive'],
        ];
    }

    public function mount($unitId = null)
    {
        $this->unitId = $unitId;

        if ($unitId) {
            $this->unit = unit::findOrFail($unitId);
            $this->code = $this->unit->code;
            $this->name = [
                'ar' => $this->unit->getTranslation('name','ar'),
                'en' => $this->unit->getTranslation('name','en'),
            ];
            $this->abbreviation   = $this->unit->abbreviation;
            $this->kind           = $this->unit->kind;
            $this->parent_id      = $this->unit->parent_id;
            $this->ratio_to_parent= $this->unit->ratio_to_parent;
            $this->is_default_minor = (bool)$this->unit->is_default_minor;
            $this->status         = $this->unit->status;
        }

        $this->majors = unit::majors()->orderBy('code')->get();
    }

    public function updatedKind()
    {
        if ($this->kind === 'major') {
            $this->parent_id = null;
            $this->ratio_to_parent = null;
            $this->is_default_minor = false;
        } else {
            $this->is_default_minor = true;
        }
    }

    public function save()
    {
        $data = $this->validate();

        if ($data['kind'] === 'major') {
            $data['parent_id'] = null;
            $data['ratio_to_parent'] = null;
            $data['is_default_minor'] = false;
        } else {
            if (!$data['parent_id'] || !$data['ratio_to_parent']) {
                $this->addError('parent_id','يجب اختيار وحدة كبرى وتحديد النسبة للصغرى.');
                return;
            }
            if ($data['is_default_minor']) {
                unit::where('parent_id', $data['parent_id'])
                    ->where('kind','minor')
                    ->when($this->unit, fn($q)=>$q->where('id','!=',$this->unit->id))
                    ->update(['is_default_minor'=>false]);
            }
        }

        $record = $this->unit ?? new unit();
        $record->code = $data['code'];
        $record->setTranslations('name', $data['name']); // name ترجمية
        $record->abbreviation   = $data['abbreviation']; // اختصار عادي
        $record->kind           = $data['kind'];
        $record->parent_id      = $data['parent_id'];
        $record->ratio_to_parent= $data['ratio_to_parent'];
        $record->is_default_minor = (bool)$data['is_default_minor'];
        $record->status         = $data['status'];
        $record->save();

        session()->flash('success', $this->unit ? 'تم تحديث الوحدة بنجاح.' : 'تم إنشاء الوحدة بنجاح.');
        return redirect()->route('units.index');
    }

    public function render()
    {
        return view('livewire.inventory.units.form');
    }
}
