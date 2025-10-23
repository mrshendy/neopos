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

    public function show(\App\models\inventory\warehouse $warehouse)
    {
        return view('inventory.warehouses.show', [
            'warehouse_id' => $warehouse->getKey(),
        ]);
    }
}
