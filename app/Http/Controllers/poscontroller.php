<?php

namespace App\Http\Controllers;

class poscontroller extends Controller
{
    public function index()
    {
        return view('pos.index');
    }

    public function create()
    {
        return view('pos.create');
    }

    public function edit($id)
    {
        return view('pos.edit', compact('id'));
    }

    public function show($id)
    {
        return view('pos.show', compact('id'));
    }
}
