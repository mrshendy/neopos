<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class unitcontroller extends Controller
{
    public function index()
    {
        return view('unit.index');
    }

    public function create()
    {
        return view('unit.create');
    }

    public function edit($id)
    {
        return view('unit.edit', compact('id'));
    }
}
