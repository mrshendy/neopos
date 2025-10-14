<?php

namespace App\Http\Controllers\inventory;

use App\Http\Controllers\Controller;

class itemscontroller extends Controller
{
    public function index()
    {
        // صفحة host فقط – بداخلها @livewire('inventory.items.index')
        return view('inventory.items.index');
    }

    public function create()
    {
        return view('inventory.items.create');
    }

    public function edit($id)
    {
        // مرّر الـ id للـ host، وهي ستمُرّره للـ Livewire
        return view('inventory.items.edit', ['id' => $id]);
    }
}
