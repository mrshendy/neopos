<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class currenciescontroller extends Controller
{
    public function index()
    {
        // صفحة تضم Livewire
        return view('currencies.index');
    }
}
