<?php

namespace App\Http\Controllers\inventory;

use App\Http\Controllers\Controller;

class inventorycontroller extends Controller
{
    public function manage()
    {
        // صفحة host فقط – بداخلها @livewire('inventory.manage')
        return view('inventory.manage');
    }

}
