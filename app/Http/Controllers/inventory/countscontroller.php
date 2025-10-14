<?php

namespace App\Http\Controllers\inventory;

use App\Http\Controllers\Controller;

class countscontroller extends Controller
{
    public function index()
    {
        return view('inventory.counts.index');
    }
}
