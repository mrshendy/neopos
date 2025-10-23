<?php

namespace App\Http\Controllers\locations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\city;         
use App\models\governorate;  

class citycontroller extends Controller
{
    public function index(Request $req)
    {
        $q = city::with('governorate');

        if ($s = trim((string)$req->get('search',''))) {
            $q->where('name->ar','like',"%{$s}%")
              ->orWhere('name->en','like',"%{$s}%");
        }
        if ($g = $req->get('governorate_id')) {
            $q->where('governorate_id', (int)$g);
        }
        if ($st = $req->get('status','')) {
            $q->where('status', $st);
        }

        $rows = $q->orderBy('governorate_id')->orderBy('id','desc')->paginate(15)->withQueryString();
        $governorates = governorate::orderBy('name->ar')->get();

        return view('locations.city.index', compact('rows','governorates'));
    }

    public function create()
    {
        $governorates = governorate::orderBy('name->ar')->get();
        return view('locations.city.create', compact('governorates'));
    }

    public function store(Request $req)
    {
        $data = $req->validate([
            'governorate_id' => ['required','integer','exists:governorate,id'],
            'name_ar'        => ['required','string','min:2','max:150'],
            'name_en'        => ['required','string','min:2','max:150'],
            'status'         => ['required','in:active,inactive'],
        ], [
            'governorate_id.required' => 'المحافظة مطلوبة.',
        ]);

        $row = city::create([
            'governorate_id' => (int)$data['governorate_id'],
            'status'         => $data['status'],
        ]);
        $row->setTranslations('name', ['ar'=>$data['name_ar'],'en'=>$data['name_en']]);
        $row->save();

        return redirect()->route('city.index')->with('success','تم إنشاء المدينة بنجاح.');
    }

    public function edit($id)
    {
        $row = city::findOrFail($id);
        $governorates = governorate::orderBy('name->ar')->get();
        return view('locations.city.edit', compact('row','governorates'));
    }

    public function update(Request $req, $id)
    {
        $row = city::findOrFail($id);

        $data = $req->validate([
            'governorate_id' => ['required','integer','exists:governorate,id'],
            'name_ar'        => ['required','string','min:2','max:150'],
            'name_en'        => ['required','string','min:2','max:150'],
            'status'         => ['required','in:active,inactive'],
        ]);

        $row->governorate_id = (int)$data['governorate_id'];
        $row->status         = $data['status'];
        $row->setTranslations('name', ['ar'=>$data['name_ar'],'en'=>$data['name_en']]);
        $row->save();

        return redirect()->route('city.index')->with('success','تم تحديث المدينة بنجاح.');
    }

    public function destroy($id)
    {
        $row = city::findOrFail($id);
        $row->delete();
        return redirect()->route('city.index')->with('success','تم حذف المدينة (حذف ناعم).');
    }

    public function toggleStatus($id)
    {
        $row = city::findOrFail($id);
        $row->status = $row->status === 'active' ? 'inactive' : 'active';
        $row->save();
        return back()->with('success','تم تغيير الحالة بنجاح.');
    }
}
