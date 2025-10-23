<?php

namespace App\Http\Controllers;

class purchasescontroller extends Controller
{
    public function index()
    {
        return view('purchases.index');
    }

    public function create()
    {
        return view('purchases.create');
    }
}
