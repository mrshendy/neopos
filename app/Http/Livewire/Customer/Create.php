<?php

namespace App\Http\Livewire\Customer;

use Livewire\Component;
use App\Models\customer\customer;
use App\Models\country;
use App\Models\governorate;
use App\Models\city;
use App\Models\area;

class Create extends Component
{
    // الحقول
    public $code = '';
    public $legal_name = ['ar' => '', 'en' => ''];
    public $type = 'individual';     // individual | company | b2b | b2c
    public $channel = 'retail';      // retail | wholesale | online | pharmacy

    public $country_id = '';
    public $governorate_id = '';
    public $city_id = '';
    public $area_id = '';
    public $city = '';               // نص حر

    public $phone = '';
    public $tax_number = '';
    public $credit_limit = null;
    public $account_status = 'active'; // active | inactive | suspended

    // القوائم
    public $country = [];
    public $governorates = [];
    public $cities = [];
    public $areas = [];

    protected $rules = [
        'code'                  => 'required|string|max:100|unique:customers,code',
        'legal_name.ar'         => 'required|string|max:255',
        'legal_name.en'         => 'required|string|max:255',
        'type'                  => 'required|in:individual,company,b2b,b2c',
        'channel'               => 'required|in:retail,wholesale,online,pharmacy',
        'country_id'            => 'nullable|exists:countries,id',
        'governorate_id'        => 'nullable|exists:governorates,id',
        'city_id'               => 'nullable|exists:cities,id',
        'area_id'               => 'nullable|exists:areas,id',
        'city'                  => 'nullable|string|max:255',
        'phone'                 => 'nullable|string|max:100',
        'tax_number'            => 'nullable|string|max:100',
        'credit_limit'          => 'nullable|numeric|min:0',
        'account_status'        => 'required|in:active,inactive,suspended',
    ];

    protected $messages = [
        'code.required'         => 'كود العميل مطلوب.',
        'code.unique'           => 'كود العميل مستخدم من قبل.',
        'legal_name.ar.required'=> 'الاسم القانوني (عربي) مطلوب.',
        'legal_name.en.required'=> 'الاسم القانوني (إنجليزي) مطلوب.',
    ];

    public function mount()
    {
        $this->country      = Country::orderBy('id','desc')->get();
        $this->governorates = collect();
        $this->cities       = collect();
        $this->areas        = collect();
    }

    // تحديث القوائم التابعة
    public function updatedCountryId($val)
    {
        $this->governorate_id = '';
        $this->city_id = '';
        $this->area_id = '';
        $this->governorates = $val ? Governorate::where('country_id', $val)->orderBy('id','desc')->get() : collect();
        $this->cities = collect();
        $this->areas  = collect();
    }

    public function updatedGovernorateId($val)
    {
        $this->city_id = '';
        $this->area_id = '';
        $this->cities = $val ? City::where('governorate_id', $val)->orderBy('id','desc')->get() : collect();
        $this->areas  = collect();
    }

    public function updatedCityId($val)
    {
        $this->area_id = '';
        $this->areas = $val ? Area::where('city_id', $val)->orderBy('id','desc')->get() : collect();
    }

    public function save()
    {
        $data = $this->validate();

        $c = new Customer();
        $c->code = $this->code;
        $c->setTranslation('legal_name', 'ar', $this->legal_name['ar']);
        $c->setTranslation('legal_name', 'en', $this->legal_name['en']);

        $c->type = $this->type;
        $c->channel = $this->channel;

        $c->country_id = $this->country_id ?: null;
        $c->governorate_id = $this->governorate_id ?: null;
        $c->city_id = $this->city_id ?: null;
        $c->area_id = $this->area_id ?: null;
        $c->city = $this->city ?: null;

        $c->phone = $this->phone ?: null;
        $c->tax_number = $this->tax_number ?: null;
        $c->credit_limit = $this->credit_limit ?: null;
        $c->account_status = $this->account_status;

        $c->save();

        session()->flash('success', __('pos.saved_success') ?? 'تم الحفظ بنجاح');
        return redirect()->route('customers.index');
    }

    public function render()
    {
        return view('customers.create', [
            'country'      => $this->country,
            'governorates' => $this->governorates,
            'cities'       => $this->cities,
            'areas'        => $this->areas,
        ]);
    }
}
