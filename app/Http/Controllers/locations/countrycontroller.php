<?php

namespace App\Http\Controllers\locations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\countries; 

class countrycontroller extends Controller
{
    public function index(Request $req)
    {
        $q = countries::query();

        if ($s = trim((string)$req->get('search', ''))) {
            $q->where('name->ar', 'like', "%{$s}%")
              ->orWhere('name->en', 'like', "%{$s}%")
              ->orWhere('code', 'like', "%{$s}%");
        }

        if ($st = $req->get('status', '')) {
            $q->where('status', $st);
        }

        $rows = $q->orderBy('status', 'desc')->orderBy('code')->paginate(15)->withQueryString();

        return view('settings.countries', compact('rows'));
    }

    public function create()
    {
        return view('settings.country.create');
    }

    public function store(Request $req)
    {
        $data = $req->validate([
            'name_ar' => ['required','string','min:2','max:150'],
            'name_en' => ['required','string','min:2','max:150'],
            'code'    => ['nullable','string','max:10'],
            'status'  => ['required','in:active,inactive'],
        ], [
            'name_ar.required' => 'الاسم بالعربية مطلوب.',
            'name_en.required' => 'الاسم بالإنجليزية مطلوب.',
            'status.in'        => 'قيمة الحالة غير صحيحة.',
        ]);

        $row = countries::create([
            'code'   => $data['code'] ?? null,
            'status' => $data['status'],
        ]);

        // HasTranslations: columns [name]
        $row->setTranslations('name', ['ar'=>$data['name_ar'], 'en'=>$data['name_en']]);
        $row->save();

        return redirect()->route('country.index')->with('success', 'تم إنشاء الدولة بنجاح.');
    }

    public function edit($id)
    {
        $row = countries::findOrFail($id);
        return view('locations.country.edit', compact('row'));
    }

    public function update(Request $req, $id)
    {
        $row = countries::findOrFail($id);

        $data = $req->validate([
            'name_ar' => ['required','string','min:2','max:150'],
            'name_en' => ['required','string','min:2','max:150'],
            'code'    => ['nullable','string','max:10'],
            'status'  => ['required','in:active,inactive'],
        ]);

        $row->code   = $data['code'] ?? null;
        $row->status = $data['status'];
        $row->setTranslations('name', ['ar'=>$data['name_ar'], 'en'=>$data['name_en']]);
        $row->save();

        return redirect()->route('country.index')->with('success', 'تم تحديث الدولة بنجاح.');
    }

    public function destroy($id)
    {
        $row = countries::findOrFail($id);
        $row->delete(); // SoftDeletes
        return redirect()->route('country.index')->with('success', 'تم حذف الدولة (حذف ناعم).');
    }

    public function toggleStatus($id)
    {
        $row = countries::findOrFail($id);
        $row->status = $row->status === 'active' ? 'inactive' : 'active';
        $row->save();
        return back()->with('success', 'تم تغيير الحالة بنجاح.');
    }
}
