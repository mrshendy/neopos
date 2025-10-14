<?php

namespace App\Http\Controllers\inventory;

use App\Http\Controllers\Controller;

class settingscontroller extends Controller
{
    public function index()
    {
        return view('inventory.settings.index');
    }
}
