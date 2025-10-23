<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class financecontroller extends Controller
{
    public function index()
    {
        return view('finance.index'); 
    }

}