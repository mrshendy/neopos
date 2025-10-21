<?php

namespace App\Http\Controllers\inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class purchasescontroller extends Controller
{
    // /inventory/purchases
    public function index()
    {
        return view('purchases.index');
    }

    // /inventory/purchases/create
    public function create()
    {
        return view('purchases.create');
    }

    // /inventory/purchases/{id}/edit
    public function edit($id)
    {
        return view('purchases.edit', compact('id'));
    }

    // /inventory/purchases/{id}/show
    public function show($id)
    {
        // يعرض الفاتورة (قراءة فقط)
        return view('purchases.show', compact('id'));
    }

    // /inventory/purchases/{id}/print
    public function print($id)
    {
        // صفحة طباعة A4
        return view('purchases.print', compact('id'));
    }
}
