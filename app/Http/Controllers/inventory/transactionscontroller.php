<?php

namespace App\Http\Controllers\inventory;

use App\Http\Controllers\Controller;

class transactionscontroller extends Controller
{
    public function index()
    {
        return view('inventory.transactions.index');
    }

    public function create()
    {
        return view('inventory.transactions.create');
    }

    public function edit($id)
    {
        return view('inventory.transactions.edit', compact('id'));
    }

  
}
