<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class financemovementscontroller extends Controller
{
    // قائمة حركات الخزائن
    public function index()
    {
        // يحتوي على: <livewire:finance_movements.index />
        return view('finance.movements');
    }

    // إنشاء/تعديل حركة خزينة
    public function manage($id = null)
    {
        // يحتوي على: <livewire:finance_movements.manage :id="$id" />
        return view('finance.movements_manage', compact('id'));
    }
}
