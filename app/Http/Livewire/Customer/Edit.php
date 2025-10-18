<?php

namespace App\Http\Livewire\Customer;

use Livewire\Component;
use App\models\customer\customer;
use App\models\customer\country;
use App\models\customer\governorate;
use App\models\customer\city;
use App\models\customer\area;

class Edit extends Component
{
    public $customer_id;

    // الحقول
    public $code = '';
    public $legal_name = ['ar' => '', 'en' => ''];
    public $type = 'individual';
    public $channel = 'retail';

    public $country_id = '';
    public $governorate_id = '';
    public $city_id = '';
    public $area_id = '';
    public $city = '';

    public $phone = '';
    public $tax_number = '';
    public $credit_limit = null;
    public $account_status = 'active';

    // القوائم
    public $country = [];
    public $governorates = [];
    public $cities = [];
    public $areas = [];

    protected function rules()
    {
        return [
            'code'                  => 'required|string|max:100|unique:customers,code,'.$this->customer_id,
            'legal_name.ar'         => 'required|string|max:255',
            'legal_name.en'         => 'required|string|max:255',
            'type'                  => 'required|in:individual,company,b2b,b2c',
            'channel'               => 'required|in:retail,wholesale,online,pharmacy',
            'country_id'            => 'nullable|exists:countries,id',
            'governorate_id'        => 'nullable|exists:governorate,id',
            'city_id'               => 'nullable|exists:city,id',
            'area_id'               => 'nullable|exists:area,id',
            'city'                  => 'nullable|string|max:255',
            'phone'                 => 'nullable|string|max:100',
            'tax_number'            => 'nullable|string|max:100',
            'credit_limit'          => 'nullable|numeric|min:0',
            'account_status'        => 'required|in:active,inactive,suspended',
        ];
    }

    protected $messages = [
        'code.required'          => 'كود العميل مطلوب.',
        'code.unique'            => 'كود العميل مستخدم من قبل.',
        'legal_name.ar.required' => 'الاسم القانوني (عربي) مطلوب.',
        'legal_name.en.required' => 'الاسم القانوني (إنجليزي) مطلوب.',
    ];

    public function mount($id)
    {
        $this->customer_id = $id;

        $this->country      = country::orderBy('id','desc')->get();
        $this->governorates = collect();
        $this->cities       = collect();
        $this->areas        = collect();

        $c = customer::findOrFail($id);

        $this->code = $c->code;
        $this->legal_name['ar'] = $c->getTranslation('legal_name','ar');
        $this->legal_name['en'] = $c->getTranslation('legal_name','en');

        $this->type    = $c->type;
        $this->channel = $c->channel;

        $this->country_id     = $c->country_id;
        $this->governorate_id = $c->governorate_id;
        $this->city_id        = $c->city_id;
        $this->area_id        = $c->area_id;
        $this->city           = $c->city;

        $this->phone        = $c->phone;
        $this->tax_number   = $c->tax_number;
        $this->credit_limit = $c->credit_limit;
        $this->account_status = $c->account_status;

        // تحميل القوائم التابعة حسب القيم الحالية
        if ($this->country_id) {
            $this->governorates = governorate::where('country_id', $this->country_id)->orderBy('id','desc')->get();
        }
        if ($this->governorate_id) {
            $this->cities = city::where('governorate_id', $this->governorate_id)->orderBy('id','desc')->get();
        }
        if ($this->city_id) {
            $this->areas = area::where('city_id', $this->city_id)->orderBy('id','desc')->get();
        }
    }

    // تحديث القوائم التابعة
    public function updatedCountryId($val)
    {
        $this->governorate_id = '';
        $this->city_id = '';
        $this->area_id = '';
        $this->governorates = $val ? governorate::where('country_id', $val)->orderBy('id','desc')->get() : collect();
        $this->cities = collect();
        $this->areas  = collect();
    }

    public function updatedGovernorateId($val)
    {
        $this->city_id = '';
        $this->area_id = '';
        $this->cities = $val ? city::where('governorate_id', $val)->orderBy('id','desc')->get() : collect();
        $this->areas  = collect();
    }

    public function updatedCityId($val)
    {
        $this->area_id = '';
        $this->areas = $val ? area::where('city_id', $val)->orderBy('id','desc')->get() : collect();
    }

    public function save()
    {
        $this->validate();

        $c = customer::findOrFail($this->customer_id);

        $c->code = $this->code;

        $c->setTranslation('legal_name', 'ar', $this->legal_name['ar']);
        $c->setTranslation('legal_name', 'en', $this->legal_name['en']);

        $c->type    = $this->type;
        $c->channel = $this->channel;

        $c->country_id     = $this->country_id ?: null;
        $c->governorate_id = $this->governorate_id ?: null;
        $c->city_id        = $this->city_id ?: null;
        $c->area_id        = $this->area_id ?: null;
        $c->city           = $this->city ?: null;

        $c->phone        = $this->phone ?: null;
        $c->tax_number   = $this->tax_number ?: null;
        $c->credit_limit = $this->credit_limit ?: null;
        $c->account_status = $this->account_status;

        $c->save();

        session()->flash('success', __('pos.saved_success') ?: 'تم تحديث البيانات بنجاح');
        return redirect()->route('customers.index');
    }

    public function render()
    {
        return view('customers.edit', [
            'country'      => $this->country,
            'governorates' => $this->governorates,
            'cities'       => $this->cities,
            'areas'        => $this->areas,
        ]);
    }
}
