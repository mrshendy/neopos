<?php

namespace App\Http\Controllers\offers;

use App\Http\Controllers\Controller;
use App\models\offers\coupons;

class couponscontroller extends Controller
{
    /**
     * قائمة الكوبونات — تعرض Blade فيه @livewire('coupons.index')
     */
    public function index()
    {
        return view('coupons.index');
    }

    /**
     * إنشاء كوبون — يعرض Blade فيه @livewire('coupons.manage')
     */
    public function create()
    {
        return view('coupons.create');
    }

    /**
     * تعديل كوبون — يعرض Blade فيه @livewire('coupons.manage', ['coupon' => $coupon])
     */
    public function edit(coupons $coupon)
    {
        return view('coupons.edit', compact('coupon'));
    }
}
