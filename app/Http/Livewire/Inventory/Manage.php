<?php

namespace App\Http\Livewire\Inventory;

use Livewire\Component;
use Illuminate\Support\Facades\Route;

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

            // ✅ كارت الفروع (عدّل اسم الراوت لو عندك بصيغة أخرى مثل inventory.branches.index)
            ['route' => 'branches.index',               'icon' => 'mdi-office-building-outline', 'key' => 'branches'],

            ['route' => 'product.index',                'icon' => 'mdi-package-variant-closed',  'key' => 'products'],
        ];

        // فلترة أي عنصر ملوش Route عشان مانشوفش 404
        $this->modules = array_values(array_filter(
            $this->modules,
            fn ($m) => isset($m['route']) && Route::has($m['route'])
        ));
    }

    public function render()
    {
        return view('livewire.inventory.manage');
    }
}
