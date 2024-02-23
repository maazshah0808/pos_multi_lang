<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Brand;
//use DB;


class BrandController extends Controller
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
        $brand = Brand::select('brands.*','users.name as uname')->join('users','brands.created_by','=','users.id')->get();
       // $quries = DB::getQueryLog();
       // dd($quries);

        return view('pages/brand/index',compact('brand'));

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
            'brand_name' => 'required',
        ]);

        $check = Brand::where('name',$request->brand_name)->count();

        if($check > 0){
            return redirect()->back()->with('error','Brand Name Already Exist !!!');
        }

        $brand = new Brand();
        $brand->name = $request->brand_name;
        $brand->status = 1;
        $brand->created_by = $id;
        $brand->save();

        if($brand)
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

        $brand = Brand::find($id);

        return response()->json([
             
            'status' => 200,
            'brand' => $brand,

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
            'brand_name' => 'required',
        ]);

        $brandid = $request->input('brandid');
        $brand = Brand::find($brandid);

        $brand->name = $request->brand_name;
        $brand->status = $request->status;
        $brand->created_by = $id;
        $brand->update();

        if($brand)
       {
        return back()->with('message','Data updated Successfully');
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
