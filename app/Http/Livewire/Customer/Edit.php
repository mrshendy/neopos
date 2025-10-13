<?php

namespace App\Http\Livewire\customer;

use Livewire\Component;
use Illuminate\Validation\Rule;
use App\models\customer\customer;
use App\models\countries;
use App\models\governorate;
use App\models\city;
use App\models\area;
use App\models\pricelist;

class Edit extends Component
{
    public $customer_id;

    public $code;
    public $legal_name = ['ar'=>'','en'=>''];
    public $trade_name = ['ar'=>'','en'=>''];
    public $type = 'b2b';
    public $channel = 'retail';

    public $country_id;
    public $governorate_id;
    public $city_id;
    public $area_id;

    public $city;
    public $phone;
    public $tax_number;

    public $price_category_id;
    public $sales_rep_id;
    public $credit_limit = 0;
    public $balance = 0;
    public $account_status = 'active';

    protected function rules()
    {
        return [
            'code'               => ['required','string','max:20', Rule::unique('customers','code')->ignore($this->customer_id)],
            'legal_name.ar'      => ['required','string','max:255'],
            'legal_name.en'      => ['required','string','max:255'],
            'trade_name.ar'      => ['nullable','string','max:255'],
            'trade_name.en'      => ['nullable','string','max:255'],
            'type'               => ['required', Rule::in(['individual','company','b2b','b2c'])],
            'channel'            => ['nullable', Rule::in(['retail','wholesale','online','pharmacy'])],

            'country_id'         => ['nullable','exists:Countries,id'],
            'governorate_id'     => ['nullable','exists:governorate,id'],
            'city_id'            => ['nullable','exists:city,id'],
            'area_id'            => ['nullable','exists:area,id'],

            'city'               => ['nullable','string','max:100'],
            'phone'              => ['nullable','string','max:50'],
            'tax_number'         => ['nullable','string','max:50'],

            'price_category_id'  => ['nullable','exists:price_lists,id'],
            'sales_rep_id'       => ['nullable','exists:users,id'],

            'credit_limit'       => ['required','numeric','min:0'],
            'balance'            => ['required','numeric'],
            'account_status'     => ['required', Rule::in(['active','inactive','suspended'])],
        ];
    }

  
    protected function messages(): array
    {
        return [
        'code.required'          => '⚠ ' . __('pos.val_code_required'),
        'code.unique'            => '⚠ ' . __('pos.val_code_unique'),
        'legal_name.ar.required' => '⚠ ' . __('pos.val_legal_ar_required'),
        'legal_name.en.required' => '⚠ ' . __('pos.val_legal_en_required'),
        'credit_limit.min'       => '⚠ ' . __('pos.val_credit_limit_min'),
        'area_id.exists'         => '⚠ ' . __('pos.val_area_exists'),
    ];
    }
    public function mount($id)
    {
        $c = customer::findOrFail($id);
        $this->customer_id     = $c->id;

        $this->code            = $c->code;
        $this->legal_name      = $c->getTranslations('legal_name');
        $this->trade_name      = $c->getTranslations('trade_name') ?? ['ar'=>'','en'=>''];

        $this->type            = $c->type;
        $this->channel         = $c->channel;

        $this->country_id      = $c->country_id;
        $this->governorate_id  = $c->governorate_id;
        $this->city_id         = $c->city_id;
        $this->area_id         = $c->area_id;
        $this->city            = $c->city;

        $this->phone           = $c->phone;
        $this->tax_number      = $c->tax_number;

        $this->price_category_id = $c->price_category_id;
        $this->sales_rep_id      = $c->sales_rep_id;

        $this->credit_limit      = $c->credit_limit;
        $this->balance           = $c->balance;

        $this->account_status    = $c->account_status;
    }

    // سلاسل اختيار متتابعة
    public function updatedCountryId(){ $this->governorate_id = $this->city_id = $this->area_id = null; }
    public function updatedGovernorateId(){ $this->city_id = $this->area_id = null; }
    public function updatedCityId(){ $this->area_id = null; }

    public function update()
    {
        $this->validate();

        $c = customer::findOrFail($this->customer_id);

        $c->update([
            'code' => $this->code,
            'legal_name' => $this->legal_name,
            'trade_name' => $this->trade_name,
            'type' => $this->type,
            'channel' => $this->channel,

            'country_id'     => $this->country_id,
            'governorate_id' => $this->governorate_id,
            'city_id'        => $this->city_id,
            'area_id'        => $this->area_id,
            'city'           => $this->city,

            'phone' => $this->phone,
            'tax_number' => $this->tax_number,
            'price_category_id' => $this->price_category_id,
            'sales_rep_id' => $this->sales_rep_id,

            'credit_limit' => $this->credit_limit,
            'balance' => $this->balance,

            'account_status' => $this->account_status,
            'updated_by' => auth()->id(),
        ]);

        session()->flash('success', __('pos.msg_updated'));
        return redirect()->route('customers.index');
    }

    public function render()
    {
        return view('livewire.customer.edit', [
            'countries'    => countries::all(),
            'governorates' => $this->country_id ? governorate::where('country_id',$this->country_id)->get() : collect(),
            'cities'       => $this->governorate_id ? city::where('governorate_id',$this->governorate_id)->get() : collect(),
            'areas'        => $this->city_id ? area::where('city_id',$this->city_id)->get() : collect(),
            'priceLists'   => pricelist::where('status','active')->get(),
        ]);
    }
}
