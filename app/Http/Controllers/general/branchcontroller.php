<?php

namespace App\Http\Controllers\general;

use App\Http\Controllers\Controller;

class branchcontroller extends Controller
{
    /** قائمة الفروع */
    public function index()
    {
        return view('general.branches.index');
    }

    /** إنشاء فرع */
    public function create()
    {
        return view('general.branches.create');
    }

    /** تعديل فرع */
    public function edit($branch_id)
    {
        return view('general.branches.edit', compact('branch_id'));
    }
}
