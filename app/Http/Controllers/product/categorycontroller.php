<?php

namespace App\Http\Controllers\product;

use App\Http\Controllers\Controller;

class categorycontroller extends Controller
{
    public function index()
    {
        return view('categories.index');
    }

    public function create()
    {
        return view('categories.create');
    }

    public function edit($id)
    {
        return view('categories.edit', compact('id'));
    }
}
