<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Invoicedetail;
use App\Models\Returntbl;
use App\Models\Returndetail;
use Illuminate\Support\Facades\DB;
use DataTables;
use DateTime;

class ReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //

        $request->validate([
            'return' => 'required',
        ]);

        if(!empty($request->return)) {
                    
          return redirect()->route('salereturn.create','id='.$request->return);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        $invoice = Invoice::find($request->id);
        if($invoice == null ){return back()->with('error','Invoice Not found!');}
        $invoice_date = new DateTime(date('Y-m-d', strtotime($invoice->created_at)));
        $today = new DateTime(date('Y-m-d'));
       
        $diff = $today->diff($invoice_date);
        if($diff->d > 3)
        {
            return back()->with('error','Unable to return invoice!');
        }
        $invoicedetail = Invoicedetail::select('invoicedetails.*','products.name','units.name as uname')->join('products','invoicedetails.product_id','=','products.id')->join('units','invoicedetails.unit_id','=','units.id')->where('inv_id',$request->id)->get();
        return view('pages.returnsale.return',compact('invoice','invoicedetail'));
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
            $return_arr = [];
            $invoicedetail_arr = [];
            $total_qty = 0;
            $total_amount = 0;
            $return_qty = 0;
            $return_amount = 0;
            $product_return_amount = 0;
            $taxtotal = 0;
            $item = [];
            $return = new Returntbl;
            $return->total_amount = 0;
            $return->total_qty = 0;
            $return->invoice_id = $request->invoice_id;
            $return->detail = $request->detail;
            $return->created_by = $user_id;
            $return->save();
            $return_id = $return->id;

            for($i=0;$i<count($request->product_id);$i++)
            {
                if($request->r_qty[$i] != 0)
                {
                    if($request->r_qty[$i] == $request->qty[$i])
                    {
                       
                        $return_qty+=$request->r_qty[$i];
                        
                        $return_amount+=$request->r_qty[$i] * $request->price[$i];
                        $product_return_amount+=$request->r_qty[$i] * $request->product_price[$i];
                        $taxtotal+=(floatval($request->tax_value[$i]))*floatval($request->r_qty[$i]);
                        //add just in return array
                        $return_arr[] = [
                            "unit_id" => $request->unit_id[$i],
                            "product_id" => $request->product_id[$i],
                            "qty" => $request->r_qty[$i],
                            "price" => $request->price[$i],
                            "created_at" => date('Y-m-d H:i:s'),
                            "updated_at" => date('Y-m-d H:i:s'),
                            "taxvalue" => $request->tax_value[$i],
                            "return_id" => $return_id
                        ];
                        $item[] = [
                            "ItemCode" => $request->product_id[$i],//productid
                            "ItemName" => $request->product_name[$i],//product name
                            "Quantity" => $request->r_qty[$i],//total qty
                            "PCTCode"=> config('global.fbrapidata.PCTCode'),//"11001010",
                            "TaxRate"=> config('global.fbrapidata.tax'),//item tax rate 17
                            "SaleValue"=> $request->product_price[$i],//product price 250
                            "TotalAmount"=> $request->price[$i]*$request->r_qty[$i],//row total
                            "TaxCharged"=> (floatval($request->tax_value[$i]))*floatval($request->r_qty[$i]),//tax value
                            "Discount"=> 0.0,
                            "FurtherTax"=> 0.0,
                            "InvoiceType"=> 3,
                            "RefUSIN"=> null
                        ];
                    }else{
                        //add in both array
                        $newqty = $request->qty[$i] - $request->r_qty[$i];
                        $total_qty+=$newqty;
                        $total_amount+=$request->price[$i]*$newqty;

                        
                        $invoicedetail_arr[] = [
                            "unit_id" => $request->unit_id[$i],
                            "product_id" => $request->product_id[$i],
                            "qty" => $newqty,
                            "product_price" => $request->product_price[$i],
                            "price" => $request->price[$i],
                            "created_at" => date('Y-m-d H:i:s'),
                            "updated_at" => date('Y-m-d H:i:s'),
                            "taxvalue" => $request->tax_value[$i],
                            "inv_id" => $request->invoice_id
                        ];
                        $return_qty+=$request->r_qty[$i];
                        $return_amount+=$request->r_qty[$i] * $request->price[$i];
                        $product_return_amount+=$request->r_qty[$i] * $request->product_price[$i];
                        $taxtotal+=(floatval($request->tax_value[$i]))*floatval($request->r_qty[$i]);
                        $return_arr[] = [
                            "unit_id" => $request->unit_id[$i],
                            "product_id" => $request->product_id[$i],
                            "qty" => $request->r_qty[$i],
                            "price" => $request->price[$i],
                            "created_at" => date('Y-m-d H:i:s'),
                            "updated_at" => date('Y-m-d H:i:s'),
                            "return_id" => $return_id,
                            "taxvalue" => $request->tax_value[$i],
                        ];
                        $item[] = [
                            "ItemCode" => $request->product_id[$i],//productid
                            "ItemName" => $request->product_name[$i],//product name
                            "Quantity" => $request->r_qty[$i],//total qty
                            "PCTCode"=> config('global.fbrapidata.PCTCode'),//"11001010",
                            "TaxRate"=> config('global.fbrapidata.tax'),//item tax rate 17
                            "SaleValue"=> $request->product_price[$i],//product price 250
                            "TotalAmount"=> $request->price[$i]*$request->r_qty[$i],//row total
                            "TaxCharged"=> (floatval($request->tax_value[$i]))*floatval($request->r_qty[$i]),//tax value
                            "Discount"=> 0.0,
                            "FurtherTax"=> 0.0,
                            "InvoiceType"=> 3,
                            "RefUSIN"=> null
                        ];
                    }
                }
            }

           // dd($invoicedetail_arr);
            Invoicedetail::where('inv_id',$request->invoice_id)->delete();
            if(count($invoicedetail_arr) > 0)
            {
                Invoicedetail::insert($invoicedetail_arr);
            }
            if(count($return_arr) > 0)
            {
                Returndetail::insert($return_arr);

            }
            $data = [
                "InvoiceNumber"=> "",
                "POSID"=> config('global.fbrapidata.POSID'),//801842,
                "USIN"=> config('global.fbrapidata.USIN'),//"USIN0",
                "DateTime"=> date('Y-m-d H:i:s'),//"2020-01-01 12:00:00",
                "BuyerNTN"=> config('global.fbrapidata.BuyerNTN'),//"999999",
                "BuyerCNIC"=> config('global.fbrapidata.BuyerCNIC'),//"12345-1234567-8",
                "BuyerName"=> config('global.fbrapidata.BuyerName'),//"countersale",
                "BuyerPhoneNumber"=> config('global.fbrapidata.BuyerPhoneNumber'),//"0000-0000000",
                "items"=> $item,
                "TotalBillAmount"=> $return_amount,//grand total
                "TotalQuantity"=> $return_qty,//total qty
                "TotalSaleValue"=> $product_return_amount,//total amount
                "TotalTaxCharged"=> $taxtotal,//total tax charged
                "PaymentMode"=> 1,//cash
                "InvoiceType"=> 3//return
            ];
            $response = $this->generatingfbrinvoice($data);
            if($response["status"] == true)
            {
              
                //get FBR invoice id
                $data = $response["data"];
                $this->insertFbrinvoiceno($return_id,$data->InvoiceNumber,$return_amount,$return_qty);
                $this->updateinvoice($request->invoice_id,$total_amount,$total_qty);
                DB::commit();
                 return redirect(route('getreturnlist'));
            }else{
               // dd($response);
               dd("Something went wrong!");
            }
            
        }catch(\Exception $e){
            DB::rollback();
            //dd($data);
            dd($e);
            }
         

    }
    function insertFbrinvoiceno($return_id,$value,$total_amount,$total_qty)
    {
        $ret=Returntbl::find($return_id);
        $ret->total_amount=$total_amount;
        $ret->total_qty=$total_qty;
        $ret->fbr_invoice_no=$value;
        $ret->save();
    }
    function updateinvoice($invoice_id,$total_amount,$total_qty)
    {
        $in=Invoice::find($invoice_id);
        $in->invoice_amount=$total_amount;
        $in->total_qty=$total_qty;
        $in->save();
    }
    function generatingfbrinvoice($data)
    {
        $response_arr=["status" => false];
        try{

            $client = new \GuzzleHttp\Client(['verify' => false]);//for local host
           // $client = new \GuzzleHttp\Client();
           $response = $client->request('POST', config('global.fbrapidata.url'), [
                  
            'headers' => ['Content-type' => 'application/json','Authorization' => config('global.fbrapidata.tokken')],
                'body' => json_encode($data)
                //'json' => $data
            ]);
            
        $response = $response->getBody()->getContents();
        $response = json_decode($response);
        if($response->Code == 100){
           
            $response_arr=["status" => true,"data" =>$response];
        }else{
            dd($response);
        }
        return $response_arr;
        //dd($response->Code);
        }catch (\Exception $e){//(\ClientErrorResponseException $e) {

               $response_arr=["status" => false];
             //dd($e);
               return $response_arr;
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

        //get return listing
    public function getreturnlist(Request $request){

        if ($request->ajax()) {

                $data = Returntbl::select('returntbls.*');

                if(!empty($request->frmdate && $request->todate)) {
                    
                    $data = $data->where('created_at','>=',$request->frmdate." 00:00:00")->where('created_at','<=',$request->todate." 23:59:00");
                }

                $data= $data->get();

                return Datatables::of($data)
                ->addIndexColumn()
                ->addIndexColumn()
                        ->addColumn('date', function($row){
                            $date = date('d-m-Y', strtotime($row->created_at));
                            return $date;

                        })
                        ->addColumn('FBR-Invoice', function($row){
                            
                             $string = explode ("*", $row->fbr_invoice_no);
                             return $string[0];
                         })

                         ->addColumn('returnid', function($row){
                            
                            $string = "RETURN - ".$row->id;
                             return $string; 
                        })

                        ->addColumn('invid', function($row){
                            
                            $string = "INVOICE - ".$row->invoice_id;
                             return $string; 
                        })

                        // ->addColumn('action', function($row){
                        //     $btn = '
                        //     <a href="'.route('getprint',$row->id).'"  class="btn btn-primary btn-sm">Print</a>
                        //     ';
                        //         return $btn;
                        // })

                ->rawColumns(['action'])
                ->make(true);
            }

            return view('pages.returnsale.returnlist');
    }

    
}
