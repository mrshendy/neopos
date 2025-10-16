<?php

namespace App\Http\Livewire\Inventory;

use Livewire\Component;

class Manage extends Component
{
    public array $modules = [];

    public function mount(): void
    {
        $this->modules = [
            ['route' => 'inventory.settings.index',     'icon' => 'mdi-cog-outline',             'key' => 'settings'],
            ['route' => 'inventory.counts.index',       'icon' => 'mdi-clipboard-list-outline', 'key' => 'counts'],
            ['route' => 'inventory.alerts.index',       'icon' => 'mdi-alert-decagram-outline', 'key' => 'alerts'],
            ['route' => 'inventory.transactions.index', 'icon' => 'mdi-swap-horizontal-bold',   'key' => 'transactions'],
            ['route' => 'inventory.warehouses.index',   'icon' => 'mdi-warehouse',               'key' => 'warehouses'],
            ['route' => 'product.index',                'icon' => 'mdi-package-variant-closed', 'key' => 'products'],
            // ['route' => 'inventory.stocks.index',    'icon' => 'mdi-database',                'key' => 'stocks'],
        ];
    }

    public function render()
    {
        return view('livewire.inventory.manage');
    }
}
