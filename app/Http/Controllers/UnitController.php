<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Unit;
//use DB;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
         //DB::enableQueryLog();
         $unit = Unit::select('units.*','users.name as uname')->join('users','units.created_by','=','users.id')->get();
         // $quries = DB::getQueryLog();
         // dd($quries);
          return view('pages/unit/index',compact('unit'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

        $id = Auth::id();

        $request->validate([
            'unitname' => 'required',
        ]);

        $check = Unit::where('name',$request->unitname)->count();

        if($check > 0){
            return redirect()->back()->with('error','Unit Name Already Exist !!!');
        }

        $unit = new Unit();
        $unit->name = $request->unitname;
        $unit->status = 1;
        $unit->created_by = $id;
        $unit->save();

        if($unit)
       {
        return back()->with('message','Data added Successfully');
       }else{
           return back()->with('error','Something went wrong');
       }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //

        $unit = Unit::find($id);

        return response()->json([
             
            'status' => 200,
            'unit' => $unit,

        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //

        $id = Auth::id();

        $request->validate([
            'unitname' => 'required',
        ]);

        $unitid = $request->input('unitid');
        $unit = Unit::find($unitid);

        $unit->name = $request->unitname;
        $unit->status = $request->status;
        $unit->created_by = $id;
        $unit->update();

        if($unit)
       {
        return back()->with('message','Data added Successfully');
       }else{
           return back()->with('error','Something went wrong');
       }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
