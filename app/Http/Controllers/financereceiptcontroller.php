<?php
// app/Http/Controllers/financereceiptcontroller.php

namespace App\Http\Controllers;

class financereceiptcontroller extends Controller
{
    public function index()
    {
        // تقرير الملغاة يُفتَح من زر داخل الصفحة (wire:click)، أو مرر ?canceled=1 لو حابب
        return view('finance/receipts');
    }

    public function manage($id = null)
    {
        return view('finance/receipts_manage', compact('id'));
    }
}
