<?php

namespace App\Http\Livewire\Customer;

use Livewire\Component;
use App\Models\customer\customer;


class Show extends Component
{
    public $customer;
    public $customer_id;

    public function mount($customer_id)
    {
        $this->customer_id = $customer_id;
        $this->customer = Customer::with(['country','governorate','cityRel','area'])->findOrFail($customer_id);
    }

    public function render()
    {
        return view('customer.show', [
            'customer' => $this->customer,
        ]);
    }
}
