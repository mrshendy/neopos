<?php
// app/Http/Controllers/financehandovercontroller.php

namespace App\Http\Controllers;

class financehandovercontroller extends Controller
{
    public function index()
    {
        return view('finance/handovers');
    }

    public function manage($id = null)
    {
        return view('finance/handovers_manage', compact('id'));
    }
}
