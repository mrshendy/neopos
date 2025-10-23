<?php

namespace App\Http\Livewire\Currencies;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\models\currencies\currencies;

class Manage extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // ====== Filters / Toolbar ======
    public $search = '';
    public $filter_status = '';
    public $per_page = 10;

    // ====== Form fields ======
    public $currency_id = null;
    public $code = '';                 // ISO code like "EGP"
    public $name_ar = '';
    public $name_en = '';
    public $symbol = '';               // "£" or "E£"
    public $minor_unit = 2;            // عدد المنازل العشرية
    public $exchange_rate = 1.000000;  // مقابل العملة الافتراضية
    public $is_default = false;
    public $status = 'active';         // active / inactive
    public $notes_ar = '';
    public $notes_en = '';

    protected $listeners = [
        'deleteConfirmed' => 'destroy',
    ];

    // رسائل عامة داخل الكومبوننت
    public $warning = null;

    // القواعد
    protected function rules()
    {
        $id = (int) $this->currency_id;

        return [
            'code'          => [
                'required','string','size:3',
                Rule::unique('currencies','code')->ignore($id)->whereNull('deleted_at'),
                'regex:/^[A-Z]{3}$/'
            ],
            'name_ar'       => ['required','string','max:100'],
            'name_en'       => ['required','string','max:100'],
            'symbol'        => ['required','string','max:10'],
            'minor_unit'    => ['required','integer','between:0,6'],
            'exchange_rate' => ['required','numeric','gt:0','max:999999999'],
            'is_default'    => ['boolean'],
            'status'        => ['required', Rule::in(['active','inactive'])],
            'notes_ar'      => ['nullable','string','max:500'],
            'notes_en'      => ['nullable','string','max:500'],
        ];
    }

    // رسائل عربية موجزة
    protected $messages = [
        'code.required'          => 'الكود مطلوب.',
        'code.size'              => 'الكود يجب أن يتكون من 3 أحرف كبيرة (مثال: EGP).',
        'code.regex'             => 'الكود يجب أن يكون أحرف لاتينية كبيرة فقط مثل USD, EGP.',
        'code.unique'            => 'هذا الكود مسجل من قبل.',
        'name_ar.required'       => 'اسم العملة بالعربية مطلوب.',
        'name_en.required'       => 'اسم العملة بالإنجليزية مطلوب.',
        'symbol.required'        => 'رمز العملة مطلوب.',
        'minor_unit.required'    => 'عدد المنازل العشرية مطلوب.',
        'minor_unit.between'     => 'عدد المنازل يجب أن يكون بين 0 و 6.',
        'exchange_rate.required' => 'سعر الصرف مطلوب.',
        'exchange_rate.gt'       => 'سعر الصرف يجب أن يكون أكبر من صفر.',
        'status.required'        => 'الحالة مطلوبة.',
        'status.in'              => 'قيمة الحالة غير صحيحة.',
    ];

    public function mount(): void
    {
        $this->resetForm();
    }

    public function updatingSearch()   { $this->resetPage(); }
    public function updatingPerPage()  { $this->resetPage(); }
    public function updatingFilterStatus() { $this->resetPage(); }

    public function resetForm(): void
    {
        $this->currency_id   = null;
        $this->code          = '';
        $this->name_ar       = '';
        $this->name_en       = '';
        $this->symbol        = '';
        $this->minor_unit    = 2;
        $this->exchange_rate = 1.000000;
        $this->is_default    = false;
        $this->status        = 'active';
        $this->notes_ar      = '';
        $this->notes_en      = '';
        $this->warning       = null;
    }

    public function edit(int $id): void
    {
        $row = currencies::findOrFail($id);
        $this->currency_id   = $row->id;
        $this->code          = $row->code;
        $this->name_ar       = $row->getTranslation('name', 'ar') ?? '';
        $this->name_en       = $row->getTranslation('name', 'en') ?? '';
        $this->symbol        = $row->symbol;
        $this->minor_unit    = (int) $row->minor_unit;
        $this->exchange_rate = (float) $row->exchange_rate;
        $this->is_default    = (bool) $row->is_default;
        $this->status        = $row->status;
        $this->notes_ar      = $row->getTranslation('notes', 'ar') ?? '';
        $this->notes_en      = $row->getTranslation('notes', 'en') ?? '';

        $this->dispatchBrowserEvent('scrollToForm');
    }

    public function save(): void
    {
        $this->validate();

        // تحذير عند جعل العملة غير فعالة وهي افتراضية
        if ($this->is_default && $this->status === 'inactive') {
            $this->warning = 'لا يمكن جعل العملة الافتراضية غير فعالة. سيتم ضبط الحالة إلى فعّالة.';
            $this->status = 'active';
        }

        DB::transaction(function () {
            // إذا تم اختيارها كافتراضية، ألغي الافتراضية عن غيرها
            if ($this->is_default) {
                currencies::query()->update(['is_default' => false]);
            }

            $payload = [
                'code'          => strtoupper($this->code),
                'name'          => [
                    'ar' => $this->name_ar,
                    'en' => $this->name_en,
                ],
                'symbol'        => $this->symbol,
                'minor_unit'    => (int) $this->minor_unit,
                'exchange_rate' => (float) $this->exchange_rate,
                'is_default'    => (bool) $this->is_default,
                'status'        => $this->status,
                'notes'         => [
                    'ar' => $this->notes_ar,
                    'en' => $this->notes_en,
                ],
            ];

            currencies::updateOrCreate(
                ['id' => $this->currency_id],
                $payload
            );

            // إذا لم تكن هناك عملة افتراضية بعد الحفظ، اضبط هذه كافتراضية
            if (! currencies::where('is_default', true)->exists()) {
                currencies::where('code', strtoupper($this->code))->update(['is_default' => true, 'status' => 'active']);
            }
        });

        session()->flash('success', __('pos.saved_successfully') ?: 'تم الحفظ بنجاح.');
        $this->resetForm();
        $this->resetPage();
        $this->dispatchBrowserEvent('focusSearch');
    }

    public function changeStatus(int $id): void
    {
        $row = currencies::findOrFail($id);

        if ($row->is_default && $row->status === 'active') {
            // لا تسمح بإلغاء تفعيل الافتراضية
            $this->warning = 'لا يمكن إلغاء تفعيل العملة الافتراضية.';
            return;
        }

        $row->status = $row->status === 'active' ? 'inactive' : 'active';
        $row->save();

        session()->flash('success', 'تم تغيير الحالة بنجاح.');
    }

    public function destroy(int $id): void
    {
        $row = currencies::findOrFail($id);

        if ($row->is_default) {
            $this->warning = 'لا يمكن حذف العملة الافتراضية.';
            return;
        }

        $row->delete();

        session()->flash('success', 'تم الحذف بنجاح.');
        $this->resetPage();
    }

    public function render()
    {
        $q = currencies::query()
            ->when(strlen($this->search) > 0, function($qq){
                $term = mb_strtolower(trim($this->search));
                $qq->where(function($w) use ($term){
                    $w->whereRaw('LOWER(code) like ?', ["%{$term}%"])
                      ->orWhereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, "$.ar"))) like ?', ["%{$term}%"])
                      ->orWhereRaw('LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, "$.en"))) like ?', ["%{$term}%"]);
                });
            })
            ->when($this->filter_status !== '', fn($qq) => $qq->where('status', $this->filter_status))
            ->orderByDesc('is_default')
            ->orderBy('code');

        $rows = $q->paginate($this->per_page);

        return view('livewire.currencies.manage', [
            'rows' => $rows,
        ]);
    }
}
