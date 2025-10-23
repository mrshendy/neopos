<?php

namespace App\Http\Controllers\offers;

use App\Http\Controllers\Controller;
use App\models\offers\offers;

class offerscontroller extends Controller
{
    // صفحة القائمة
    public function index()
    {
        $offers = offers::latest('id')->paginate(12);
        return view('offers.index', compact('offers'));
    }

    // صفحة الإنشاء
    public function create()
    {
        return view('offers.create');
    }

    // صفحة التعديل
    public function edit(offers $offer)
    {
        return view('offers.edit', compact('offer'));
    }
}
