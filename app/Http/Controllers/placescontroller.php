<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class placescontroller extends Controller
{


    /**
     * صفحة الدول: مناداة لايفواير فقط.
     */
    public function countries()
    {
        return view('settings.country');
    }

    /**
     * صفحة المحافظات: مناداة لايفواير فقط.
     */
    public function governorates()
    {
        return view('settings.governorate');
    }

    /**
     * صفحة المدن: مناداة لايفواير فقط.
     */
    public function cities()
    {
        return view('settings.city');
    }

    /**
     * صفحة المناطق: مناداة لايفواير فقط.
     */
    public function areas()
    {
        return view('settings.area');
    }
}
