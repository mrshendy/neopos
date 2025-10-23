<?php

namespace App\Http\Controllers\inventory;

use App\Http\Controllers\Controller;

class inventorycontroller extends Controller
{
    public function manage()
    {
        $modules = [
            // نفس ترتيب صورتك + فرع جديد
            ['key' => 'general_settings', 'icon' => 'mdi mdi-cog-outline',              'route' => 'settings.index'],
            ['key' => 'items',            'icon' => 'mdi mdi-database-cog-outline',    'route' => 'products.index'],
            ['key' => 'categories',       'icon' => 'mdi mdi-shape-outline',           'route' => 'categories.index'],
            ['key' => 'units',            'icon' => 'mdi mdi-ruler',                   'route' => 'units.index'],

            // ✅ كارت الفروع
            ['key' => 'branches',         'icon' => 'mdi mdi-office-building-outline', 'route' => 'branches.index'],
        ];

        return view('inventory.manage', compact('modules'));
    }

}
