<?php

namespace App\Http\Controllers\product;

use App\Http\Controllers\Controller;
use App\models\product\unit;

class unitscontroller extends Controller
{
    public function index()
    {
        // يعرض صفحة تحتوي على مكوّن Livewire للفهرس
        return view('inventory.units.index');
    }

    public function create()
    {
        // صفحة فيها مكوّن النموذج (إنشاء)
        return view('inventory.units.create');
    }

    public function edit(unit $unit)
    {
        // صفحة فيها مكوّن النموذج (تعديل) ونمرّر الـ id
        return view('inventory.units.edit', compact('unit'));
    }
}
