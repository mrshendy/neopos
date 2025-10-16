<?php

namespace App\Http\Controllers\supplier;

use App\Http\Controllers\Controller;
use App\models\supplier\supplier;

class suppliercontroller extends Controller
{
    // عرض الجدول مع فلاتر
    public function index()
    {
        return view('supplier.index');
    }

    // إنشاء مورد (نستدعي Livewire)
    public function create()
    {
        return view('supplier.create');
    }

    // عرض تفاصيل مورد
    public function show(supplier $supplier)
    {
        return view('supplier.show', compact('supplier'));
    }

    // تعديل مورد
    public function edit(supplier $supplier)
    {
        return view('supplier.edit', compact('supplier'));
    }

    // (اختياري) إن أردت استخدام الكنترولر بدل Livewire للحفظ/التحديث/الحذف:
    public function store()  { abort(501, 'Handled by Livewire.'); }
    public function update() { abort(501, 'Handled by Livewire.'); }
    public function destroy(supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', __('pos.deleted_success'));
    }
}
