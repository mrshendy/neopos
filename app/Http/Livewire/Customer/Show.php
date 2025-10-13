<?php

namespace App\Http\Livewire\customer;

use Livewire\Component;
use App\models\customer\customer;

class Show extends Component
{
    public $customer_id;
    public $customer;

    public function mount($id)
    {
        $this->customer_id = $id;
        $this->customer = customer::findOrFail($this->customer_id);
    }

    public function render()
    {
        return view('livewire.customer.show');
    }
}


