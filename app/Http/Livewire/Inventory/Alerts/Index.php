<?php

namespace App\Http\Livewire\inventory\alerts;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $type = 'reorder'; // reorder | expiry | expired

    public function render()
    {
        // مخرجات توضيحية (اعمل SQL حقيقية حسب هيكلتك)
        $alerts = collect([]);

        return view('livewire.inventory.alerts.index', [
            'alerts' => $alerts,
        ]);
    }
}
