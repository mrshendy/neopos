<?php

namespace App\Http\Controllers\locations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\city;
use App\models\country;
use App\models\governorate;

class citycontroller extends Controller
{
    public function index()
    {
        $citys        = city::with(['country','governoratees'])->orderByDesc('id')->get();
        $country      = country::orderBy('name->ar')->get();
        // المحافظات ستُجلب ديناميكيًا عند اختيار الدولة (AJAX)؛ أو اجلبها كلها إن أحببت
        return view('settings.city', compact('citys','country'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name_ar'          => ['required','string','min:2','max:150'],
            'name_en'          => ['required','string','min:2','max:150'],
            'id_country'       => ['required','integer','exists:country,id'],
            'id_governoratees' => ['required','integer','exists:governorate,id'],
        ], [
            'name_ar.required'          => 'الاسم العربي مطلوب.',
            'name_en.required'          => 'الاسم الإنجليزي مطلوب.',
            'id_country.required'       => 'يجب اختيار الدولة.',
            'id_governoratees.required' => 'يجب اختيار المحافظة.',
        ]);

        // تحقق الاتساق: المحافظة تتبع الدولة
        $gov = governorate::findOrFail((int)$data['id_governoratees']);
        if ((int)$gov->id_country !== (int)$data['id_country']) {
            return back()->with('error','المحافظة لا تنتمي إلى الدولة المختارة.')->withInput();
        }

        try {
            city::create([
                'name'              => ['ar'=>$data['name_ar'],'en'=>$data['name_en']],
                'id_country'        => (int)$data['id_country'],
                'id_governoratees'  => (int)$data['id_governoratees'],
                'status'            => 'active',
                'user_add'          => auth()->id() ?? 0,
            ]);
            return back()->with('success','تم إضافة المدينة بنجاح.');
        } catch (\Throwable $e) {
            return back()->with('error','حدث خطأ أثناء الإضافة: '.$e->getMessage())->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $id = (int)($request->id ?? $id);
        $data = $request->validate([
            'name_ar'          => ['required','string','min:2','max:150'],
            'name_en'          => ['required','string','min:2','max:150'],
            'id_country'       => ['required','integer','exists:country,id'],
            'id_governoratees' => ['required','integer','exists:governorate,id'],
        ]);

        $gov = governorate::findOrFail((int)$data['id_governoratees']);
        if ((int)$gov->id_country !== (int)$data['id_country']) {
            return back()->with('error','المحافظة لا تنتمي إلى الدولة المختارة.')->withInput();
        }

        try {
            $row = city::findOrFail($id);
            $row->name             = ['ar'=>$data['name_ar'],'en'=>$data['name_en']];
            $row->id_country       = (int)$data['id_country'];
            $row->id_governoratees = (int)$data['id_governoratees'];
            $row->user_update      = auth()->id() ?? 0;
            $row->save();

            return back()->with('success','تم تحديث المدينة بنجاح.');
        } catch (\Throwable $e) {
            return back()->with('error','حدث خطأ أثناء التحديث: '.$e->getMessage())->withInput();
        }
    }

    public function destroy(Request $request, $id)
    {
        $id = (int)($request->id ?? $id);
        try {
            $row = city::findOrFail($id);
            $row->delete();
            return back()->with('success','تم حذف المدينة بنجاح.');
        } catch (\Throwable $e) {
            return back()->with('error','تعذر حذف المدينة: '.$e->getMessage());
        }
    }

    // (اختياري) Endpoint لتعبئة محافظات دولة معينة بالـ AJAX
    public function governoratesByCountry($countryId)
    {
        $list = governorate::where('id_country', (int)$countryId)
            ->orderBy('name->ar')
            ->get(['id','name']);

        $locale = app()->getLocale();
        return response()->json(
            $list->map(fn($g)=>['id'=>$g->id,'name'=>$g->getTranslation('name',$locale) ?: $g->getTranslation('name','en')])
        );
    }
}
