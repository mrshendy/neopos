<?php

namespace App\Http\Livewire\customer;

use Livewire\Component;
use App\models\customer\customer;

class Show extends Component
{
    public $id;

    public function mount($id)
    {
        $this->id = $id;
    }

    public function render()
    {
        $customer = customer::with([
            'country','governorate','cityRel','area',
            'priceCategory','contacts','addresses','documents',
            'credit','pricing','transactions'
        ])->findOrFail($this->id);

        return view('livewire.customer.show', compact('customer'));
    }
}
