<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;

class customercontroller extends Controller
{
    /**
     * عرض الصفحة الرئيسية لقائمة العملاء
     */
    public function index()
    {
        return view('customers.index');
    }

    /**
     * صفحة إنشاء عميل جديد
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * صفحة تعديل عميل
     */
    public function edit($id)
    {
        return view('customers.edit', compact('id'));
    }

    /**
     * صفحة عرض تفاصيل عميل
     */
    public function show($id)
    {
        return view('customers.show', compact('id'));
    }
}
