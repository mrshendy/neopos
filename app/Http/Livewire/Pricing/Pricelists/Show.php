<?php

namespace App\Http\Livewire\Pricing\Pricelists;

use Livewire\Component;
use App\models\pricing\price_list;
use App\models\pricing\price_item;
use Illuminate\Support\Facades\App;

class Show extends Component
{
    public $row;
    public $items = [];

    public function mount($id)
    {
        $this->row = price_list::findOrFail($id);

        $this->items = price_item::with('product')
            ->where('price_list_id', $id)
            ->orderBy('product_id')
            ->orderBy('min_qty')
            ->get();
    }

    public function render()
    {
        return view('livewire.pricing.pricelists.show');
    }
}
