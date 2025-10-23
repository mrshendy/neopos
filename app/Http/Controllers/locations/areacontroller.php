<?php

namespace App\Http\Controllers\locations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\area;
use App\models\city;
use App\models\country;
use App\models\governorate;

class areacontroller extends Controller
{
    public function index()
    {
        $areaes  = area::with(['country','governoratees','city'])->orderByDesc('id')->get();
        $country = country::orderBy('name->ar')->get();
        return view('settings.area', compact('areaes','country'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name_ar'          => ['required','string','min:2','max:150'],
            'name_en'          => ['required','string','min:2','max:150'],
            'id_country'       => ['required','integer','exists:country,id'],
            'id_governoratees' => ['required','integer','exists:governorate,id'],
            'id_city'          => ['required','integer','exists:city,id'],
        ], [
            'name_ar.required'          => 'الاسم العربي مطلوب.',
            'name_en.required'          => 'الاسم الإنجليزي مطلوب.',
            'id_country.required'       => 'يجب اختيار الدولة.',
            'id_governoratees.required' => 'يجب اختيار المحافظة.',
            'id_city.required'          => 'يجب اختيار المدينة.',
        ]);

        // تحقق اتساق السلاسل (مدينة ⟵ محافظة ⟵ دولة)
        $gov = governorate::findOrFail((int)$data['id_governoratees']);
        if ((int)$gov->id_country !== (int)$data['id_country']) {
            return back()->with('error','المحافظة لا تنتمي إلى الدولة المختارة.')->withInput();
        }
        $cty = city::findOrFail((int)$data['id_city']);
        if ((int)$cty->id_governoratees !== (int)$data['id_governoratees']) {
            return back()->with('error','المدينة لا تنتمي إلى المحافظة المختارة.')->withInput();
        }

        try {
            area::create([
                'name'              => ['ar'=>$data['name_ar'],'en'=>$data['name_en']],
                'id_country'        => (int)$data['id_country'],
                'id_governoratees'  => (int)$data['id_governoratees'],
                'id_city'           => (int)$data['id_city'],
                'status'            => 'active',
                'user_add'          => auth()->id() ?? 0,
            ]);
            return back()->with('success','تم إضافة المنطقة بنجاح.');
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
            'id_city'          => ['required','integer','exists:city,id'],
        ]);

        $gov = governorate::findOrFail((int)$data['id_governoratees']);
        if ((int)$gov->id_country !== (int)$data['id_country']) {
            return back()->with('error','المحافظة لا تنتمي إلى الدولة المختارة.')->withInput();
        }
        $cty = city::findOrFail((int)$data['id_city']);
        if ((int)$cty->id_governoratees !== (int)$data['id_governoratees']) {
            return back()->with('error','المدينة لا تنتمي إلى المحافظة المختارة.')->withInput();
        }

        try {
            $row = area::findOrFail($id);
            $row->name             = ['ar'=>$data['name_ar'],'en'=>$data['name_en']];
            $row->id_country       = (int)$data['id_country'];
            $row->id_governoratees = (int)$data['id_governoratees'];
            $row->id_city          = (int)$data['id_city'];
            $row->user_update      = auth()->id() ?? 0;
            $row->save();

            return back()->with('success','تم تحديث المنطقة بنجاح.');
        } catch (\Throwable $e) {
            return back()->with('error','حدث خطأ أثناء التحديث: '.$e->getMessage())->withInput();
        }
    }

    public function destroy(Request $request, $id)
    {
        $id = (int)($request->id ?? $id);
        try {
            $row = area::findOrFail($id);
            $row->delete();
            return back()->with('success','تم حذف المنطقة بنجاح.');
        } catch (\Throwable $e) {
            return back()->with('error','تعذر حذف المنطقة: '.$e->getMessage());
        }
    }

    // AJAX: محافظات دولة معينة
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

    // AJAX: مدن محافظة معينة
    public function citiesByGovernorate($govId)
    {
        $list = city::where('id_governoratees', (int)$govId)
            ->orderBy('name->ar')
            ->get(['id','name']);

        $locale = app()->getLocale();
        return response()->json(
            $list->map(fn($c)=>['id'=>$c->id,'name'=>$c->getTranslation('name',$locale) ?: $c->getTranslation('name','en')])
        );
    }
}
