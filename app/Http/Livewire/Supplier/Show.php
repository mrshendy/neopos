<?php

namespace App\Http\Livewire\supplier;

use Livewire\Component;
use App\Models\supplier\supplier;

class Show extends Component
{
    public supplier $supplier;

    public function mount(supplier $supplier)
    {
        // eager load كل العلاقات المهمة
        $supplier->load([
            'category', 'paymentTerm',
            'country','governorate','city','area',
            'addresses.country','addresses.governorate','addresses.city','addresses.area',
            'contacts',
            'qualityDocs.type',
            'contracts.paymentTerm','contracts.items',
            'discounts',
            'blacklists',
            'evaluations.scores.criterion',
        ]);

        $this->supplier = $supplier;
    }

    public function getReadyToBuyProperty(): bool
    {
        return $this->supplier->isReadyToBuy();
    }

    public function render()
    {
        return view('livewire.supplier.show');
    }
}
