<?php

namespace App\Http\Livewire\Inventory\Units;

use Livewire\Component;
use Illuminate\Validation\Rule;
use App\models\product\unit as UnitModel;

class Form extends Component
{
    /** نموذج للتعديل (اختياري) */
    public ?UnitModel $unit = null;

    /** يُمرَّر من الراوت اختيارياً */
    public int|string|null $unitId = null;

    // ====== الحقول ======
    public string $code = '';
    public array  $name = ['ar' => '', 'en' => ''];
    public ?string $abbreviation = null;

    /** minor (افتراضي) أو major */
    public string $kind = 'minor';

    public ?int $parent_id = null;
    public ?float $ratio_to_parent = null;
    public bool $is_default_minor = true;
    public string $status = 'active';

    /** قائمة الوحدات الكبرى لاختيار الأب */
    public $majors = [];

    // ====== قواعد التحقق الأساسية (يُستدعى أثناء الحفظ) ======
    protected function baseRules(): array
    {
        $ignoreId = $this->unit?->id;

        return [
            'code' => [
                'required', 'string', 'max:50',
                Rule::unique('units', 'code')->ignore($ignoreId),
            ],
            'name.ar'      => ['required', 'string', 'max:255'],
            'name.en'      => ['required', 'string', 'max:255'],
            'abbreviation' => ['nullable', 'string', 'max:50'],
            'kind'         => ['required', Rule::in(['major','minor'])],
            'parent_id'    => ['nullable', 'integer', 'exists:units,id'],
            'ratio_to_parent' => ['nullable', 'numeric', 'gt:0'],
            'is_default_minor' => ['boolean'],
            'status'       => ['required', Rule::in(['active','inactive'])],
        ];
    }

    // ====== التحميل ======
    public function mount($unitId = null): void
    {
        $this->unitId = $unitId;

        // تحميل السجل في حالة التعديل
        if ($unitId) {
            $this->unit = UnitModel::findOrFail(is_numeric($unitId) ? (int)$unitId : $unitId);
            $this->code  = (string)$this->unit->code;
            $this->name  = [
                'ar' => (string)$this->unit->getTranslation('name', 'ar'),
                'en' => (string)$this->unit->getTranslation('name', 'en'),
            ];
            $this->abbreviation     = $this->unit->abbreviation;
            $this->kind             = (string)$this->unit->kind;
            $this->parent_id        = $this->unit->parent_id;
            $this->ratio_to_parent  = $this->unit->ratio_to_parent;
            $this->is_default_minor = (bool)$this->unit->is_default_minor;
            $this->status           = (string)$this->unit->status;
        }

        $this->reloadMajors();
    }

    protected function reloadMajors(): void
    {
        // لو عندك scope active() في الموديل استخدمه، وإلا استخدم where('status','active')
        $this->majors = UnitModel::where('kind', 'major')
            ->where('status', 'active')
            ->orderBy('code')
            ->get();
    }

    // تبديل النوع
    public function updatedKind(): void
    {
        if ($this->kind === 'major') {
            $this->parent_id = null;
            $this->ratio_to_parent = null;
            $this->is_default_minor = false;
        } else {
            // عند التحويل لصغرى اجعل الافتراضي true بشكل مبدئي
            $this->is_default_minor = true;
        }
    }

    // ====== حفظ ======
    public function save()
    {
        $data = $this->validate($this->baseRules());

        // شروط إضافية عند كون الوحدة صغرى
        if ($data['kind'] === 'minor') {
            $this->validate([
                'parent_id'       => ['required', 'integer', 'exists:units,id'],
                'ratio_to_parent' => ['required', 'numeric', 'gt:0'],
            ]);
        } else {
            // لو كبرى لا نحتاج حقول الصغرى
            $data['parent_id'] = null;
            $data['ratio_to_parent'] = null;
            $data['is_default_minor'] = false;
        }

        // إنشاء/تحديث
        $record = $this->unit ?? new UnitModel();

        $record->code            = $data['code'];
        $record->setTranslations('name', $data['name']); // حقل name قابل للترجمة
        $record->abbreviation    = $data['abbreviation'] ?: null;
        $record->kind            = $data['kind'];
        $record->parent_id       = $data['parent_id'] ?? null;
        $record->ratio_to_parent = $data['ratio_to_parent'] ?? null;
        $record->is_default_minor= (bool)($data['is_default_minor'] ?? false);
        $record->status          = $data['status'];
        $record->save();

        // إن تم اختيار هذه الصغرى كافتراضية، ألغِ الافتراضيات الأخرى تحت نفس الكبرى
        if ($record->kind === 'minor' && $record->is_default_minor && $record->parent_id) {
            UnitModel::where('parent_id', $record->parent_id)
                ->where('kind', 'minor')
                ->where('id', '!=', $record->id)
                ->update(['is_default_minor' => false]);
        }

        // تحديث الحالة الداخلية (مفيد لو كمل المستخدم في نفس الصفحة)
        $this->unit = $record;
        $this->unitId = $record->id;

        session()->flash('success', $this->unit ? 'تم حفظ الوحدة بنجاح.' : 'تم إنشاء الوحدة بنجاح.');
        return redirect()->route('units.index');
    }

    public function render()
    {
        // في حال تم تعديل حالة الوحدات الكبرى من شاشة أخرى
        $this->reloadMajors();

        return view('livewire.inventory.units.form');
    }
}
