<?php

namespace App\Http\Controllers;

class finance_settingscontroller extends Controller
{
    public function index()
    {
        return view('finance_settings.index');
    }

    public function create()
    {
        return view('finance_settings.manage');
    }

    public function edit($id)
    {
        return view('finance_settings.manage', compact('id'));
    }

    public function show($id)
    {
        return view('finance_settings.show', compact('id'));
    }
}
