<?php

namespace App\Http\Controllers\locations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\country;

class countrycontroller extends Controller
{
    public function index()
    {
        $country = country::orderByDesc('id')->get();
        return view('settings.country', compact('country'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name_ar' => ['required','string','min:2','max:150'],
            'name_en' => ['required','string','min:2','max:150'],
        ], [
            'name_ar.required' => 'الاسم العربي مطلوب.',
            'name_en.required' => 'الاسم الإنجليزي مطلوب.',
        ]);

        try {
            country::create([
                'name'      => ['ar'=>$data['name_ar'], 'en'=>$data['name_en']],
                'status'    => 'active',
                'user_add'  => auth()->id() ?? 0,
            ]);
            return back()->with('success','تم إنشاء الدولة بنجاح.');
        } catch (\Throwable $e) {
            return back()->with('error','حدث خطأ غير متوقع أثناء الحفظ: '.$e->getMessage())->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        // يدعم hidden id لو موجود
        $id = (int)($request->id ?? $id);

        $data = $request->validate([
            'name_ar' => ['required','string','min:2','max:150'],
            'name_en' => ['required','string','min:2','max:150'],
        ], [
            'name_ar.required' => 'الاسم العربي مطلوب.',
            'name_en.required' => 'الاسم الإنجليزي مطلوب.',
        ]);

        try {
            $row = country::findOrFail($id);
            $row->name = ['ar'=>$data['name_ar'], 'en'=>$data['name_en']];
            $row->user_update = auth()->id() ?? 0;
            $row->save();

            return back()->with('success','تم تحديث الدولة بنجاح.');
        } catch (\Throwable $e) {
            return back()->with('error','حدث خطأ غير متوقع أثناء التحديث: '.$e->getMessage())->withInput();
        }
    }

    public function destroy(Request $request, $id)
    {
        $id = (int)($request->id ?? $id);
        try {
            $row = country::findOrFail($id);
            $row->delete(); // Soft delete
            return back()->with('success','تم حذف الدولة بنجاح.');
        } catch (\Throwable $e) {
            return back()->with('error','تعذر حذف الدولة: '.$e->getMessage());
        }
    }
}
