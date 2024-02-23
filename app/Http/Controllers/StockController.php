<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Stockdetail;
use App\Models\Unit;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Vendor;
use App\Models\Ledger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DataTables;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $categories = Category::where('status',1)->get();
        $brands = Brand::where('status',1)->get();
        $vendors = Vendor::where('status',1)->get();
        return view('pages.stocks.index',compact('categories','brands','vendors'));
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
       //dd($request);
       $user_id = \Auth::user()->id;
       try{
        DB::beginTransaction();
        //insert data in stock table
        $stock = new Stock;
        $stock->vendor_id = $request->vendor;
        $stock->qty = $request->total_qty;
        $stock->total_amount = $request->total_amount;
        $stock->discount = $request->discount;
        $stock->created_by = $user_id;
        $stock->save();
        $stock_id = $stock->id;
        $data_arr = [];
        //dd(count($request->product_id));
        for($i=0;$i<count($request->product_id);$i++)
        {
          if($request->product_id[$i] != "")
          {
            $data_arr[] = [
                "unit_id" => $request->unit_id[$i],
                "stock_id" => $stock_id,
                "product_id" => $request->product_id[$i],
                "qty" => $request->qty[$i],
                "p_price" => $request->p_price[$i],
                "s_price" => $request->s_price[$i],
                "created_by" => $user_id,
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
                "received_qty" => $request->qty[$i],
                "gst" => $request->gst[$i]
            ];
          }
        }
        if(count($data_arr) > 0)
        {
            Stockdetail::insert($data_arr);
            //inserting ledger
            $paid_amount = $request->total_amount;
            if($request->payment_status == 2)
            {
                Ledger::insert(["vendor_id" => $request->vendor,"amount" => -$paid_amount,"detail" => $request->detail,"stock_id" => $stock_id,"payment_type" => $request->payment_type,"payment_status" => $request->payment_status,"created_at" => date('Y-m-d H:i:s'),"updated_at" => date('Y-m-d H:i:s')]);

                $paid_amount = $request->partial_amount;
            }
            if($request->payment_status == 0)
            {
                Ledger::insert(["vendor_id" => $request->vendor,"amount" => -$paid_amount,"detail" => $request->detail,"stock_id" => $stock_id,"payment_type" => $request->payment_type,"payment_status" => $request->payment_status,"created_at" => date('Y-m-d H:i:s'),"updated_at" => date('Y-m-d H:i:s')]);
            }
            $paid_amount = $request->payment_status == 1 ? -$paid_amount : $paid_amount;
            Ledger::insert(["vendor_id" => $request->vendor,"amount" => $paid_amount,"detail" => $request->detail,"stock_id" => $stock_id,"payment_type" => $request->payment_type,"payment_status" => $request->payment_status,"created_at" => date('Y-m-d H:i:s'),"updated_at" => date('Y-m-d H:i:s')]);
         
        }
        DB::commit();
        return back()->with('message','Data added Successfully');
       }catch(\Exception $e){
        DB::rollback();
        //dd($e);
        return back()->with('error','Something went wrong');
       }
    }

    /**
     * Display the specified resource.S
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id,Request $request)
    {
        //

        $stock = Stockdetail::select('stockdetails.*','products.name as pname','units.name as uname')
                             ->join('products','stockdetails.product_id','=','products.id')
                             ->join('units','stockdetails.unit_id','=','units.id')
                             ->where('product_id',$id)->where('unit_id',$request->u_id)->get();

            
        return view('pages.stocks.stockdetail',compact('stock'));

         

           


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
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
    //getting products
    public function getproduct(Request $request)
    {
        $products = Product::where('status',1);
        if($request->has('category') && $request->category != ""){
            $products = $products->where('category_id',$request->category);
        }
        if($request->has('brand') && $request->brand != ""){
            $products = $products->where('brand_id',$request->brand);  
        }
        $products = $products->get();
        return response()->json([
            "status" => 1,
            'data' => $products,
        ]);
    }
     //getting products units
     public function getproductunit(Request $request)
     {
        
            $units = Unit::where('status',1)->get();
             return response()->json([
                "status" => 1,
                'data' => $units,
            ]);
         
        
     }

            //getting stock puchase
    public function getavalstock(Request $request)
    {

        if ($request->ajax()) {

            $data = Stockdetail::select('products.name as pname', 'stockdetails.product_id','stockdetails.unit_id','units.name as uname',
                 DB::raw('SUM(stockdetails.qty) As tqty'))
              ->join('products','stockdetails.product_id','=','products.id')
              ->join('units','stockdetails.unit_id','=','units.id');

              if(!empty($request->productid)) {

                    $data = $data->where('product_id',$request->productid);

                }
             $data= $data->groupBy('stockdetails.product_id')
                    ->groupBy('stockdetails.unit_id')
                    ->get();
                   
              
                return Datatables::of($data)
                        ->addIndexColumn()

                        ->addColumn('action', function($row){
                            $btn = '
                            
                            <a href="'.route('stock.show',$row->product_id).'?u_id='.$row->unit_id.'" class="btn btn-primary btn-sm">View</a>
                            
                            
                            ';
                                return $btn;
                        })
                        ->rawColumns(['action'])
                        ->make(true);
            }

                        else{
                            $product =  Product::all();
           
                            //   $quries = DB::getQueryLog();
                            //    dd($quries);
                            
                            return view('pages.stocks.availablestock',compact('product'));
                        }
        
    }


    public function getstock(){


        $stock = Stock::select('stocks.*','vendors.name as vname','users.name as uname')
                        ->join('vendors','stocks.vendor_id','=','vendors.id')
                        ->join('users','stocks.created_by','=','users.id')
                        ->orderby('id','desc')->get();

        return view('pages.stocks.stocklist',compact('stock'));

    }


    public function getstockview($id){


        $stockdetail = Stockdetail::select('stockdetails.*','products.name as pname','units.name as uname','users.name as username')
                        ->join('products','stockdetails.product_id','=','products.id')
                        ->join('units','stockdetails.unit_id','=','units.id')
                        ->join('users','stockdetails.created_by','=','users.id')
                        ->orderby('id','desc')->where('stockdetails.stock_id',$id)->get();

                        


        return view('pages.stocks.stocklistview',compact('stockdetail'));

    }
   

}
