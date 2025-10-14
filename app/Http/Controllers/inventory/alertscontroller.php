<?php

namespace App\Http\Controllers\inventory;

use App\Http\Controllers\Controller;

class alertscontroller extends Controller
{
    public function index()
    {
        return view('inventory.alerts.index');
    }
}
