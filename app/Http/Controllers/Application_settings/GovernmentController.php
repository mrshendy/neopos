<?php
namespace App\Http\Controllers\Application_settings;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Controllers\Controller;
use App\Http\Requests\Storegovernorate;
use App\models\countries;
use App\models\governorate ;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class governorateController extends Controller
{

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {
    $countries=countries::all();
    $governoratees=governorate::all();
  return view('settings.governorate',compact('governoratees','countries'));

  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {

  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(Storegovernorate $request)
  {
    if(governorate::where('name->ar',$request->name_ar)->orwhere('name->en',$request->name_en)->exists())
    {
        return  redirect()->back()->withErrors([trans('governorate_trans.existes') ]);
    }
    try
    {
        $validated = $request->validated();
        $governoratees=new governorate();
        $governoratees->name=['en'=>$request->name_en,'ar'=>$request->name_ar];
        $governoratees->id_country=$request->id_country;
        $governoratees->user_add=(Auth::user()->id);
        $governoratees->account_id=(Auth::user()->id_account);
        $governoratees->save();
        session()->flash('add');
        return redirect()->route('governorate.index');

    }catch(\Exception $e)
    {
        return redirect()->back()->withErrors(['error'=>$e->getMessage()]);
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($id)
  {

  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id)
  {

  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update(Storegovernorate $request)
  {
    try
    {
    $validated = $request->validated();
    $governoratees= governorate::findorFail($request->id);
    $governoratees->update([
        $governoratees->Name=['en'=>$request->name_en,'ar'=>$request->name_ar],
        $governoratees->id_country=$request->id_country,
        $governoratees->user_add=(Auth::user()->id),
    ]);
    session()->flash('edit_m');
    return redirect()->route('governorate.index');

    }
    catch(\Exception $e)
    {
        return redirect()->back()->withErrors(['error'=>$e->getMessage()]);
    }

  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy(Request $request)
  {
    try
    {

      $governoratees= governorate::findorFail($request->id)->delete();
      Alert::success( '', trans('governorate_trans.savesuccess'));
      return redirect()->route('governorate.index');
    }
    catch(\Exception $e)
    {
        return redirect()->back()->withErrors(['error'=>$e->getMessage()]);
    }
  }

}

?>
