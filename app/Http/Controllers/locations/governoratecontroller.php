<?php

namespace App\Http\Controllers\locations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\governorate;  
use App\models\countries;     

class governoratecontroller extends Controller
{
    public function index(Request $req)
    {
        $q = governorate::with('country');

        if ($s = trim((string)$req->get('search',''))) {
            $q->where('name->ar','like',"%{$s}%")
              ->orWhere('name->en','like',"%{$s}%");
        }
        if ($c = $req->get('country_id')) {
            $q->where('country_id', (int)$c);
        }
        if ($st = $req->get('status','')) {
            $q->where('status', $st);
        }

        $rows = $q->orderBy('country_id')->orderBy('id','desc')->paginate(15)->withQueryString();
        $countries = countries::orderBy('name->ar')->get();

        return view('locations.governorate.index', compact('rows','countries'));
    }

    public function create()
    {
        $countries = countries::orderBy('name->ar')->get();
        return view('locations.governorate.create', compact('countries'));
    }

    public function store(Request $req)
    {
        $data = $req->validate([
            'country_id' => ['required','integer','exists:countries,id'],
            'name_ar'    => ['required','string','min:2','max:150'],
            'name_en'    => ['required','string','min:2','max:150'],
            'status'     => ['required','in:active,inactive'],
        ], [
            'country_id.required' => 'الدولة مطلوبة.',
        ]);

        $row = governorate::create([
            'country_id' => (int)$data['country_id'],
            'status'     => $data['status'],
        ]);
        $row->setTranslations('name', ['ar'=>$data['name_ar'],'en'=>$data['name_en']]);
        $row->save();

        return redirect()->route('governorate.index')->with('success','تم إنشاء المحافظة بنجاح.');
    }

    public function edit($id)
    {
        $row = governorate::findOrFail($id);
        $countries = countries::orderBy('name->ar')->get();
        return view('locations.governorate.edit', compact('row','countries'));
    }

    public function update(Request $req, $id)
    {
        $row = governorate::findOrFail($id);
        $data = $req->validate([
            'country_id' => ['required','integer','exists:countries,id'],
            'name_ar'    => ['required','string','min:2','max:150'],
            'name_en'    => ['required','string','min:2','max:150'],
            'status'     => ['required','in:active,inactive'],
        ]);

        $row->country_id = (int)$data['country_id'];
        $row->status     = $data['status'];
        $row->setTranslations('name', ['ar'=>$data['name_ar'],'en'=>$data['name_en']]);
        $row->save();

        return redirect()->route('governorate.index')->with('success','تم تحديث المحافظة بنجاح.');
    }

    public function destroy($id)
    {
        $row = governorate::findOrFail($id);
        $row->delete();
        return redirect()->route('governorate.index')->with('success','تم حذف المحافظة (حذف ناعم).');
    }

    public function toggleStatus($id)
    {
        $row = governorate::findOrFail($id);
        $row->status = $row->status === 'active' ? 'inactive' : 'active';
        $row->save();
        return back()->with('success','تم تغيير الحالة بنجاح.');
    }
}
