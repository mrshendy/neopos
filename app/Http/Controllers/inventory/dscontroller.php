<?php

namespace App\Http\Controllers\inventory;

use App\Http\Controllers\Controller;

class dscontroller extends Controller
{
    public function index()
    {
        // صفحة بسيطة لنداء مكون لايفواير
        return view('inventory.ds.index');
    }
}
