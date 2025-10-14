<?php

namespace App\Http\Controllers\inventory;

use App\Http\Controllers\Controller;

class warehousescontroller extends Controller
{
    public function index()
    {
        return view('inventory.warehouses.index');
    }

    public function create()
    {
        return view('inventory.warehouses.create');
    }

    public function edit($id)
    {
        return view('inventory.warehouses.edit', ['id' => $id]);
    }
}
