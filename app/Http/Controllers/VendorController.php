<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Vendor;
use App\Models\Ledger;
use Illuminate\Support\Facades\DB;
//use DB;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
         $vendors = Vendor::where('status',1)->get();
         $data_arr = [];
            foreach($vendors as $v)
            {
                $balance = Ledger::where('vendor_id',$v->id)->sum('amount');
                $data_arr[] = ["vendor" => $v,"balance" => $balance];
            }
           
          
          return view('pages.vendor.index',compact('data_arr'));
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


        $request->validate([
            'vendorname' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        $check = Vendor::where('name',$request->vendorname)->count();

        if($check > 0){
            return redirect()->back()->with('error','Vendor Name Already Exist !!!');
        }

        $vendor = new Vendor();
        $vendor->name = $request->vendorname;
        $vendor->phone = $request->phone;
        $vendor->address = $request->address;
        $vendor->status = 1;
        $vendor->save();

        if($vendor)
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

        $Vendor = Vendor::find($id);

        return response()->json([
             
            'status' => 200,
            'vendor' => $Vendor,

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

        $request->validate([
            'editvendorname' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'status' => 'required',
        ]);

       
        $vendorid = $request->input('vendorid');
        $vendor = Vendor::find($vendorid);

        $vendor->name = $request->editvendorname;
        $vendor->phone = $request->phone;
        $vendor->address = $request->address;
        $vendor->status = $request->status;
        $vendor->update();

        if($vendor)
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
    //function for ledger listing
    public function getledgerList(Request $request)
    {
        if($request->has("v_id") && $request->v_id != "")
        {
            //getting vendor detail
            $vendor = Vendor::findOrFail($request->v_id);
            //getting vendor closing balance
            $closing_balance = Ledger::where('vendor_id',$request->v_id)->sum('amount');
            //getting ledger listing
            // $ledger = DB::table(function($query){
            //     $query->select('*')
            //     ->from('ledgers')->orderby('id','desc')->limit(5);
            // })->orderby('id','asc')->get(); 
            $ledger = Ledger::where('vendor_id',$request->v_id)->orderby('id','desc')->get();
            return view('pages.vendor.ledger',compact('ledger','vendor','closing_balance')); 
        }
        
    }

    public function addpayment(Request $request)
    {
    
           
    //     $request->validate([
    //         'vendoramt' => 'required',
    //     ]);

      
       try{
            DB::beginTransaction();

            $ledger = new Ledger();
            $ledger->vendor_id = $request->vendorid;
            $ledger->amount = $request->vendoramt;
            $ledger->payment_type = $request->paymenttype;
            $ledger->payment_status = 0;
            $ledger->detail = "Payment Added to Ledger";
        
            $ledger->save();


            DB::commit();

        if($ledger)
        {
            return back()->with('message','Balance added Successfully');
        }else{
            return back()->with('error','Something went wrong');
        }


    }catch(\Exception $e){
        DB::rollback();
       
        return back()->with('error','Something went wrong');
        }
    }


    public function getgl(){


        $ledger = Ledger::select('ledgers.*','vendors.name as vname')
        ->join('vendors','ledgers.vendor_id','=','vendors.id')
                ->orderby('id','desc')->get();

        
        return view('pages.vendor.gl',compact('ledger')); 

    }
}
