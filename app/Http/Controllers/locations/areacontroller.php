<?php

namespace App\Http\Controllers\locations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\area; 
use App\models\city;  

class areacontroller extends Controller
{
    public function index(Request $req)
    {
        $q = area::with('city');

        if ($s = trim((string)$req->get('search',''))) {
            $q->where('name->ar','like',"%{$s}%")
              ->orWhere('name->en','like',"%{$s}%");
        }
        if ($c = $req->get('city_id')) {
            $q->where('city_id', (int)$c);
        }
        if ($st = $req->get('status','')) {
            $q->where('status', $st);
        }

        $rows = $q->orderBy('city_id')->orderBy('id','desc')->paginate(15)->withQueryString();
        $cities = city::orderBy('name->ar')->get();

        return view('locations.area.index', compact('rows','cities'));
    }

    public function create()
    {
        $cities = city::orderBy('name->ar')->get();
        return view('locations.area.create', compact('cities'));
    }

    public function store(Request $req)
    {
        $data = $req->validate([
            'city_id' => ['required','integer','exists:city,id'],
            'name_ar' => ['required','string','min:2','max:150'],
            'name_en' => ['required','string','min:2','max:150'],
            'status'  => ['required','in:active,inactive'],
        ], [
            'city_id.required' => 'المدينة مطلوبة.',
        ]);

        $row = area::create([
            'city_id' => (int)$data['city_id'],
            'status'  => $data['status'],
        ]);
        $row->setTranslations('name', ['ar'=>$data['name_ar'],'en'=>$data['name_en']]);
        $row->save();

        return redirect()->route('area.index')->with('success','تم إنشاء المنطقة بنجاح.');
    }

    public function edit($id)
    {
        $row = area::findOrFail($id);
        $cities = city::orderBy('name->ar')->get();
        return view('locations.area.edit', compact('row','cities'));
    }

    public function update(Request $req, $id)
    {
        $row = area::findOrFail($id);

        $data = $req->validate([
            'city_id' => ['required','integer','exists:city,id'],
            'name_ar' => ['required','string','min:2','max:150'],
            'name_en' => ['required','string','min:2','max:150'],
            'status'  => ['required','in:active,inactive'],
        ]);

        $row->city_id = (int)$data['city_id'];
        $row->status  = $data['status'];
        $row->setTranslations('name', ['ar'=>$data['name_ar'],'en'=>$data['name_en']]);
        $row->save();

        return redirect()->route('area.index')->with('success','تم تحديث المنطقة بنجاح.');
    }

    public function destroy($id)
    {
        $row = area::findOrFail($id);
        $row->delete();
        return redirect()->route('area.index')->with('success','تم حذف المنطقة (حذف ناعم).');
    }

    public function toggleStatus($id)
    {
        $row = area::findOrFail($id);
        $row->status = $row->status === 'active' ? 'inactive' : 'active';
        $row->save();
        return back()->with('success','تم تغيير الحالة بنجاح.');
    }
}
