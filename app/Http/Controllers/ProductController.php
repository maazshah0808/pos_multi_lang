<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Unit;
use App\Models\Brand;
use App\Models\Category;
use DataTables;

// use DB;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
       
        if ($request->ajax()) {
            $data = Product::select('products.*','brands.name as bname','categories.name as cname','users.name as uname')
            ->join('brands','products.brand_id','=','brands.id')
            ->join('categories','products.category_id','=','categories.id')
            ->join('users','products.created_by','=','users.id')->orderBy('products.id','DESC')
            ->get();
            
        
            return Datatables::of($data)
                    ->addIndexColumn()

                //     ->addColumn('status', function($row){
                //         if($row->status == 1){
                //             return "Active";
                //        }else{
                //             return "Inactive";
                //        }
                //  })

                    ->addColumn('action', function($row){
                           $btn = '<button value="'.$row->id.'" class="edit btn btn-primary btn-sm editbtn">Edit</button>';
                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        
        $unit = Unit::select('units.*')->get();
        $brand = Brand::select('brands.*')->get();
        $category = Category::select('categories.*')->get();


        return view('pages/product/index',compact('brand','category','unit'));
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
            'productname' => 'required',
            'brand' => 'required',
            'category' => 'required',
        ]);

        $check = Product::where('name',$request->productname)->count();

        if($check > 0){
            return redirect()->back()->with('error','Unit Name Already Exist !!!');
        }

        $product = new Product();
        $product->name = $request->productname;
        $product->brand_id = $request->brand; 
        $product->category_id = $request->category; 
        $product->status = 1;
        $product->created_by = $id;
        $product->save();

        if($product)
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

        $product = Product::find($id);

        return response()->json([
             
            'status' => 200,
            'product' => $product,

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
            'productname' => 'required',
            'brand' => 'required',
            'category' => 'required',
        ]);

    
        $productid = $request->input('productid');
        $product = Product::find($productid);

        $product->name = $request->productname;
        $product->brand_id = $request->brand; 
        $product->category_id = $request->category; 
        $product->status = $request->status;
        $product->created_by = $id;
        $product->update();

        if($product)
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
