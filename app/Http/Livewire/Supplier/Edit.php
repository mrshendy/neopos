<?php

namespace App\Http\Livewire\supplier;

use Livewire\Component;
use App\models\supplier\supplier;
use App\models\supplier\suppliercategory;
use App\models\supplier\paymentterm;

// ✅ الجغرافيا داخل App\models مباشرة
use App\models\country;
use App\models\governorate;
use App\models\city;
use App\models\area;

class Edit extends Component
{
    public supplier $supplier;

    public $code;
    public $name = ['ar' => '', 'en' => ''];
    public $commercial_register;
    public $tax_number;

    public $supplier_category_id;
    public $payment_term_id;

    public $country_id;
    public $governorate_id;
    public $city_id;
    public $area_id;

    public $status = 'active';

    public function mount(supplier $supplier)
    {
        $this->supplier = $supplier;

        $this->code = $supplier->code;
        $this->name = [
            'ar' => $supplier->getTranslation('name', 'ar'),
            'en' => $supplier->getTranslation('name', 'en'),
        ];
        $this->commercial_register = $supplier->commercial_register;
        $this->tax_number = $supplier->tax_number;

        $this->supplier_category_id = $supplier->supplier_category_id;
        $this->payment_term_id = $supplier->payment_term_id;

        $this->country_id = $supplier->country_id;
        $this->governorate_id = $supplier->governorate_id;
        $this->city_id = $supplier->city_id;
        $this->area_id = $supplier->area_id;

        $this->status = $supplier->status;
    }

    protected function rules()
    {
        return [
            'code' => 'required|string|max:50|unique:suppliers,code,' . $this->supplier->id,
            'name.ar' => 'required|string|max:255',
            'name.en' => 'required|string|max:255',
            'commercial_register' => 'nullable|string|max:100',
            'tax_number' => 'nullable|string|max:100',
            'supplier_category_id' => 'nullable|exists:supplier_categories,id',
            'payment_term_id' => 'nullable|exists:payment_terms,id',

            // حسب تسمية جداولك
            'country_id' => 'nullable|exists:country,id',
            'governorate_id' => 'nullable|exists:governorate,id',
            'city_id' => 'nullable|exists:city,id',
            'area_id' => 'nullable|exists:Area,id',

            'status' => 'required|in:active,inactive',
        ];
    }

    protected $messages = [
        'code.required' => 'كود المورد مطلوب',
        'code.unique'   => 'هذا الكود مستخدم من قبل',
        'name.ar.required' => 'الاسم بالعربية مطلوب',
        'name.en.required' => 'الاسم بالإنجليزية مطلوب',
        'supplier_category_id.exists' => 'تصنيف غير صحيح',
        'payment_term_id.exists' => 'شرط دفع غير صحيح',
        'country_id.exists' => 'الدولة غير صحيحة',
        'governorate_id.exists' => 'المحافظة غير صحيحة',
        'city_id.exists' => 'المدينة غير صحيحة',
        'area_id.exists' => 'المنطقة غير صحيحة',
        'status.in' => 'قيمة الحالة غير صحيحة',
    ];

    public function updatedGovernorateId()
    {
        $this->city_id = null;
        $this->area_id = null;
    }

    public function updatedCityId()
    {
        $this->area_id = null;
    }

    public function update()
    {
        $this->validate();

        $this->supplier->update([
            'code' => $this->code,
            'name' => $this->name,
            'commercial_register' => $this->commercial_register,
            'tax_number' => $this->tax_number,
            'supplier_category_id' => $this->supplier_category_id,
            'payment_term_id' => $this->payment_term_id,
            'country_id' => $this->country_id,
            'governorate_id' => $this->governorate_id,
            'city_id' => $this->city_id,
            'area_id' => $this->area_id,
            'status' => $this->status,
        ]);

        session()->flash('success', __('pos.updated_success'));
        return redirect()->route('suppliers.index');
    }

    public function render()
    {
        // ⚠️ اسم المتغير للمشهد = country (مش country)
        $country      = country::orderBy('id', 'asc')->get();

        $governorates = governorate::orderBy('id', 'asc')->get();

        $cities = $this->governorate_id
            ? city::where('governorate_id', $this->governorate_id)->orderBy('id', 'asc')->get()
            : ($this->supplier->governorate_id
                ? city::where('governorate_id', $this->supplier->governorate_id)->orderBy('id', 'asc')->get()
                : collect());

        $areas = $this->city_id
            ? area::where('city_id', $this->city_id)->orderBy('id', 'asc')->get()
            : ($this->supplier->city_id
                ? area::where('city_id', $this->supplier->city_id)->orderBy('id', 'asc')->get()
                : collect());

        return view('livewire.supplier.edit', [
            'categories'   => suppliercategory::where('status', 'active')->get(),
            'terms'        => paymentterm::where('status', 'active')->get(),
            'country'      => $country,       // ← زي ما طلبت
            'governorates' => $governorates,
            'cities'       => $cities,
            'areas'        => $areas,
        ]);
    }
}
