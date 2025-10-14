<?php

namespace App\Http\Controllers\pricing;

use App\Http\Controllers\Controller;

class pricingcontroller extends Controller
{
    public function index()
    {
        return view('pricing.index');
    }

    public function create()
    {
        return view('pricing.create');
    }

    public function edit($id)
    {
        return view('pricing.edit', compact('id'));
    }
}
