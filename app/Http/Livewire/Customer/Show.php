<?php

namespace App\Http\Livewire\Customer;

use Livewire\Component;
use App\models\customer\customer;

class Show extends Component
{
    public $customer;

    public function mount($id)
    {
        $this->customer = customer::with([
            'country', 'governorate', 'cityRel', 'area',
            'addresses', 'contacts', 'credit', 'pricing',
            'documents', 'approvals', 'transactions', 'group',
            'salesRep', 'creator', 'updater',
        ])->findOrFail($id);
    }

    public function render()
    {
        return view('customers.show', [
            'c' => $this->customer
        ]);
    }
}
