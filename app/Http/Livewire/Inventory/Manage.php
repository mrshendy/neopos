<?php

namespace App\Http\Livewire\Inventory;

use Livewire\Component;
use Illuminate\Support\Facades\Route;

class Manage extends Component
{
    public array $modules = [];

    public function mount(): void
    {
        // نحاول نلاقي اسم الراوت الحقيقي لصفحة "رصيد المخزن"
        $balanceRoute = null;
        foreach ([
            'inventory.stock_balances.index',
            'inventory.balances.index',
            'inventory.balance',
            'stock_balances.index',
            'inv.balance',
        ] as $candidate) {
            if (Route::has($candidate)) { $balanceRoute = $candidate; break; }
        }

        $this->modules = [
            // ✅ كارت "رصيد المخزن" — يتصدر القائمة
            [
                'route' => $balanceRoute ?: 'inv.balance', // لو مش موجود هيتفلتر بعدين
                'icon'  => 'mdi-clipboard-text-outline',
                'key'   => 'stock_balance',                // تأكد من وجود ترجمة: inventory.module_stock_balance
            ],

            ['route' => 'inventory.settings.index',     'icon' => 'mdi-cog-outline',              'key' => 'settings'],
            ['route' => 'inventory.counts.index',       'icon' => 'mdi-clipboard-list-outline',  'key' => 'counts'],
            ['route' => 'inventory.alerts.index',       'icon' => 'mdi-alert-decagram-outline',  'key' => 'alerts'],
            ['route' => 'inventory.transactions.index', 'icon' => 'mdi-swap-horizontal-bold',    'key' => 'transactions'],
            ['route' => 'inventory.warehouses.index',   'icon' => 'mdi-warehouse',                'key' => 'warehouses'],

            // ✅ كارت الفروع (عدّل اسم الراوت لو مختلف عندك)
            ['route' => 'branches.index',               'icon' => 'mdi-office-building-outline', 'key' => 'branches'],

            ['route' => 'product.index',                'icon' => 'mdi-package-variant-closed',   'key' => 'products'],
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
