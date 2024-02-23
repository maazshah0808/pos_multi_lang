<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
//use DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        //DB::enableQueryLog();
        $category = Category::select('categories.*','users.name as uname')->join('users','categories.created_by','=','users.id')->get();
       // $quries = DB::getQueryLog();
       // dd($quries);
        return view('pages/category/categories',compact('category'));
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
            'category_name' => 'required',
        ]);

        $check = Category::where('name',$request->category_name)->count();

        if($check > 0){
            return redirect()->back()->with('error','Category Name Already Exist !!!');
        }

        $category = new Category();
        $category->name = $request->category_name;
        $category->status = 1;
        $category->created_by = $id;
        $category->save();

        if($category)
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

        $category = Category::find($id);

        return response()->json([
             
            'status' => 200,
            'category' => $category,

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

            $category_id = $request->input('category_id');

            $category = Category::find($category_id);
            
            $category->name = $request->category_name;
            $category->status = $request->status;
            $category->created_by = $id;
            $category->update();

            if($category)
                {
                    return back()->with('message','Data Updated Successfully');
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
