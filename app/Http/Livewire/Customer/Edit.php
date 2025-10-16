<?php

namespace App\Http\Livewire\Customer;

use Livewire\Component;
use App\Models\customer\customer;
use App\Models\country;
use App\Models\governorate;
use App\Models\city;
use App\Models\area;


class Edit extends Component
{
    public $customer_id;
    public $customer;

    // نفس حقول الإنشاء
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

    protected $rules = [
        'code'                  => 'required|string|max:100',
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
        'legal_name.ar.required'=> 'الاسم القانوني (عربي) مطلوب.',
        'legal_name.en.required'=> 'الاسم القانوني (إنجليزي) مطلوب.',
    ];

    public function mount($customer_id)
    {
        $this->customer_id = $customer_id;
        $this->customer = Customer::findOrFail($customer_id);

        // تحميل القوائم
        $this->country      = Country::orderBy('id','desc')->get();
        $this->governorates = Governorate::when($this->customer->country_id, fn($q)=>$q->where('country_id',$this->customer->country_id))
                                         ->orderBy('id','desc')->get();
        $this->cities       = City::when($this->customer->governorate_id, fn($q)=>$q->where('governorate_id',$this->customer->governorate_id))
                                  ->orderBy('id','desc')->get();
        $this->areas        = Area::when($this->customer->city_id, fn($q)=>$q->where('city_id',$this->customer->city_id))
                                  ->orderBy('id','desc')->get();

        // تعبئة الحقول
        $this->code = $this->customer->code;
        $this->legal_name['ar'] = $this->customer->getTranslation('legal_name','ar');
        $this->legal_name['en'] = $this->customer->getTranslation('legal_name','en');

        $this->type = $this->customer->type;
        $this->channel = $this->customer->channel;

        $this->country_id = $this->customer->country_id;
        $this->governorate_id = $this->customer->governorate_id;
        $this->city_id = $this->customer->city_id;
        $this->area_id = $this->customer->area_id;
        $this->city = $this->customer->city;

        $this->phone = $this->customer->phone;
        $this->tax_number = $this->customer->tax_number;
        $this->credit_limit = $this->customer->credit_limit;
        $this->account_status = $this->customer->account_status;
    }

    // قوائم تابعة
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

    public function update()
    {
        $data = $this->validate();

        // تأكيد uniqueness على code باستثناء السجل الحالي
        $exists = Customer::where('code', $this->code)
                    ->where('id', '!=', $this->customer_id)
                    ->exists();
        if ($exists) {
            $this->addError('code', 'كود العميل مستخدم من قبل.');
            return;
        }

        $c = $this->customer;
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

        session()->flash('success', __('pos.updated_success') ?? 'تم تحديث البيانات بنجاح');
        return redirect()->route('customers.show', $c->id);
    }

    public function render()
    {
        return view('customer.edit', [
            'country'      => $this->country,
            'governorates' => $this->governorates,
            'cities'       => $this->cities,
            'areas'        => $this->areas,
            'customer'     => $this->customer,
        ]);
    }
}
