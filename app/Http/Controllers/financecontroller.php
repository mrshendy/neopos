<?php

namespace App\Http\Controllers;

class financecontroller extends Controller
{
    public function index()
    {
        return view('finance.index');
    }

    public function settings()
    {
        return view('finance.settings');
    }       // ضع فيها <livewire:finance.index />

    public function movements()
    {
        return view('finance.movements');
    }      // تبنيها لاحقًا

    public function shifts()
    {
        return view('finance.shifts');
    }         // تبنيها لاحقًا

    public function receipts()
    {
        return view('finance.receipts');
    }       // تبنيها لاحقًا

    public function receiptsVoid()
    {
        return view('finance.receipts_void');
    } // تبنيها لاحقًا

    public function create()
    {
        return view('finance.manage');
    }

    public function edit($id)
    {
        return view('finance.manage', compact('id'));
    }

    public function show($id)
    {
        return view('finance.show', compact('id'));
    }
}
