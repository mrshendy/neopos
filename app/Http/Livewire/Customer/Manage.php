<?php

namespace App\Http\Livewire\Customer;

use Livewire\Component;
use App\models\customer\customer as Customer;
use Illuminate\Validation\Rule;

class Manage extends Component
{
    public string $mode = 'create';
    public ?int $customer_id = null;

    // Basic
    public ?string $code = null;
    public string $type = 'person';         // person | company
    public string $status = 'active';       // active | inactive
    public ?string $country = null;

    // Translatable
    public ?string $name_ar = null;
    public ?string $name_en = null;

    public ?string $city_ar = null;
    public ?string $city_en = null;

    public ?string $address_ar = null;
    public ?string $address_en = null;

    public ?string $notes_ar = null;
    public ?string $notes_en = null;

    // Contact
    public ?string $phone = null;
    public ?string $mobile = null;
    public ?string $email = null;

    // Extra
    public ?string $tax_no = null;
    public ?string $commercial_no = null;

    protected function rules(): array
    {
        return [
            'code'    => [
                'nullable', 'max:60',
                Rule::unique('customer','code')->ignore($this->customer_id)->whereNull('deleted_at')
            ],
            'type'    => ['required','in:person,company'],
            'status'  => ['required','in:active,inactive'],
            'country' => ['nullable','max:60'],

            'name_ar' => ['required','max:190'],
            'name_en' => ['nullable','max:190'],

            'city_ar' => ['nullable','max:120'],
            'city_en' => ['nullable','max:120'],

            'address_ar' => ['nullable','max:500'],
            'address_en' => ['nullable','max:500'],

            'phone'   => ['nullable','max:60'],
            'mobile'  => ['nullable','max:60'],
            'email'   => ['nullable','email','max:190'],

            'tax_no'        => ['nullable','max:60'],
            'commercial_no' => ['nullable','max:60'],

            'notes_ar' => ['nullable','max:2000'],
            'notes_en' => ['nullable','max:2000'],
        ];
    }

    protected array $messages = [
        'name_ar.required' => 'حقل الاسم العربي مطلوب',
        'email.email'      => 'صيغة البريد الإلكتروني غير صحيحة',
        'code.unique'      => 'الكود مُستخدم مسبقًا',
    ];

    public function mount(?int $id = null): void
    {
        if ($id) {
            $this->mode = 'edit';
            $this->customer_id = $id;

            $c = Customer::findOrFail($id);

            $this->code    = $c->code;
            $this->type    = $c->type ?: 'person';
            $this->status  = $c->status ?: 'active';
            $this->country = $c->country;

            $this->name_ar = $c->getTranslation('name', 'ar') ?? null;
            $this->name_en = $c->getTranslation('name', 'en') ?? null;

            $this->city_ar = $c->getTranslation('city', 'ar') ?? null;
            $this->city_en = $c->getTranslation('city', 'en') ?? null;

            $this->address_ar = $c->getTranslation('address', 'ar') ?? null;
            $this->address_en = $c->getTranslation('address', 'en') ?? null;

            $this->notes_ar = $c->getTranslation('notes', 'ar') ?? null;
            $this->notes_en = $c->getTranslation('notes', 'en') ?? null;

            $this->phone   = $c->phone;
            $this->mobile  = $c->mobile;
            $this->email   = $c->email;

            $this->tax_no        = $c->tax_no;
            $this->commercial_no = $c->commercial_no;
        }
    }

    public function save()
    {
        $data = $this->validate();

        $payload = [
            'code'    => $this->code,
            'type'    => $this->type,
            'status'  => $this->status,
            'country' => $this->country,

            'phone'   => $this->phone,
            'mobile'  => $this->mobile,
            'email'   => $this->email,

            'tax_no'        => $this->tax_no,
            'commercial_no' => $this->commercial_no,
        ];

        // Translatables
        $payload['name']    = ['ar' => $this->name_ar,    'en' => $this->name_en];
        $payload['city']    = ['ar' => $this->city_ar,    'en' => $this->city_en];
        $payload['address'] = ['ar' => $this->address_ar, 'en' => $this->address_en];
        $payload['notes']   = ['ar' => $this->notes_ar,   'en' => $this->notes_en];

        if ($this->mode === 'edit' && $this->customer_id) {
            Customer::where('id', $this->customer_id)->update($payload);
            session()->flash('success', __('pos.updated_ok') ?? 'تم التحديث بنجاح');
        } else {
            $c = Customer::create($payload);
            $this->customer_id = $c->id;
            $this->mode = 'edit';
            session()->flash('success', __('pos.saved_ok') ?? 'تم الحفظ بنجاح');
        }

        // ممكن تحويل لصفحة الفهرس:
        // return redirect()->route('customers.index');
    }

    public function render()
    {
        return view('livewire.customer.manage');
    }
}
