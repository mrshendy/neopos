<?php

namespace App\Http\Controllers\locations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\governorate;
use App\models\country;

class governoratecontroller extends Controller
{
    public function index()
    {
        $governoratees = governorate::with('country')->orderByDesc('id')->get();
        $country       = country::orderBy('name->ar')->get();
        return view('settings.governorate', compact('governoratees','country'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name_ar'    => ['required','string','min:2','max:150'],
            'name_en'    => ['required','string','min:2','max:150'],
            'id_country' => ['required','integer','exists:country,id'],
        ], [
            'name_ar.required'    => 'الاسم العربي مطلوب.',
            'name_en.required'    => 'الاسم الإنجليزي مطلوب.',
            'id_country.required' => 'يجب اختيار الدولة.',
        ]);

        try {
            governorate::create([
                'name'       => ['ar'=>$data['name_ar'],'en'=>$data['name_en']],
                'id_country' => (int)$data['id_country'],
                'status'     => 'active',
                'user_add'   => auth()->id() ?? 0,
            ]);
            return back()->with('success','تم إضافة المحافظة بنجاح.');
        } catch (\Throwable $e) {
            return back()->with('error','حدث خطأ أثناء الإضافة: '.$e->getMessage())->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $id = (int)($request->id ?? $id);
        $data = $request->validate([
            'name_ar'    => ['required','string','min:2','max:150'],
            'name_en'    => ['required','string','min:2','max:150'],
            'id_country' => ['required','integer','exists:country,id'],
        ]);

        try {
            $row = governorate::findOrFail($id);
            $row->name       = ['ar'=>$data['name_ar'],'en'=>$data['name_en']];
            $row->id_country = (int)$data['id_country'];
            $row->user_update = auth()->id() ?? 0;
            $row->save();

            return back()->with('success','تم تحديث المحافظة بنجاح.');
        } catch (\Throwable $e) {
            return back()->with('error','حدث خطأ أثناء التحديث: '.$e->getMessage())->withInput();
        }
    }

    public function destroy(Request $request, $id)
    {
        $id = (int)($request->id ?? $id);
        try {
            $row = governorate::findOrFail($id);
            $row->delete();
            return back()->with('success','تم حذف المحافظة بنجاح.');
        } catch (\Throwable $e) {
            return back()->with('error','تعذر حذف المحافظة: '.$e->getMessage());
        }
    }
}
