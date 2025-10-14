<?php

namespace App\Http\Controllers\product;

use App\Http\Controllers\Controller;

class productcontroller extends Controller
{
    public function index()
    {
        return view('product.index');
    }

    public function create()
    {
        return view('product.create');
    }

    public function edit($id)
    {
        return view('product.edit', compact('id'));
    }
}
