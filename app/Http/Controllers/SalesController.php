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
use App\Models\Invoice;
use App\Models\Invoicedetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DataTables;

class SalesController extends Controller
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
        return view('pages.sales.index',compact('categories','brands'));
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
          // dd($request);
           $user_id = \Auth::user()->id;
           try{
            DB::beginTransaction();
              //Invoice insertion
                $invoice = new Invoice;
                $invoice->created_by = $user_id;
                $invoice->invoice_amount = $request->total_amount;
                $invoice->discount = $request->discount == "" ? 0 : $request->discount;
                $invoice->total_qty = $request->total_qty;
                $invoice->customer_name = $request->customer_name;
                $invoice->customer_contact = $request->customer_contact;
                $invoice->detail = "Invoice Generated";
                $invoice->ref_no = $request->ref_no;
                $invoice->tax = $request->tax_price;
                $invoice->save();
                $invoice_id = $invoice->id;
              //Invoice insertion
              $data_arr = [];
              //Invoice detail insertion
            $totaltaxvalue = 0;
            $total_price = 0;

            for($i=0;$i<count($request->product_id);$i++)
            {
                $data_arr[] = [
                    "unit_id" => $request->unit_id[$i],
                    "product_id" => $request->product_id[$i],
                    "qty" => $request->qty[$i],
                    "product_price" => $request->s_price[$i],
                    "price" => $request->newprice[$i],
                    "created_at" => date('Y-m-d H:i:s'),
                    "updated_at" => date('Y-m-d H:i:s'),
                    "taxvalue" => $request->tax_value[$i]*$request->qty[$i],
                    "inv_id" => $invoice_id
                ];

                $item[] = [
                    "ItemCode" => $request->product_id[$i],//productid
                    "ItemName" => $request->product_name[$i],//product name
                    "Quantity" => $request->qty[$i],//total qty
                    "PCTCode"=> config('global.fbrapidata.PCTCode'),//"11001010",
                    "TaxRate"=> $request->tax_price,//item tax rate 17
                    "SaleValue"=> $request->s_price[$i],//product price 250
                    "TotalAmount"=> $request->row_total[$i],//row total
                    "TaxCharged"=> $request->tax_value[$i]*$request->qty[$i],//tax value
                    "Discount"=> 0.0,
                    "FurtherTax"=> 0.0,
                    "InvoiceType"=> 1,
                    "RefUSIN"=> null
                ];
                $totaltaxvalue+=$request->tax_value[$i]*$request->qty[$i];
                $total_price+=$request->s_price[$i]*$request->qty[$i];
                //managing stock
                $this->deductstock($request['product_id'][$i],$request['qty'][$i],$request->unit_id[$i]);
            }
            
            if(count($data_arr) > 0)
            {
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
                    "TotalBillAmount"=> $request->grand_total,//grand total
                    "TotalQuantity"=> $request->total_qty,//total qty
                    "TotalSaleValue"=> $total_price,//$request->total_amount,//total amount
                    "TotalTaxCharged"=> $totaltaxvalue,//total tax charged
                    "PaymentMode"=> 1,//cash
                    "InvoiceType"=> 1//new
                ];
                //dd($data);
             if(count($data_arr) > 0)
             {
                Invoicedetail::insert($data_arr);
                $response = $this->generatingfbrinvoice($data);
                if($response["status"] == true)
                {
                    //get FBR invoice id
                    $data = $response["data"];
                    $this->insertFbrinvoiceno($invoice_id,$data->InvoiceNumber);
                    DB::commit();
                     return redirect(route('getprint',$invoice_id));
                }else{
                   // dd($response);
                   dd("Something went wrong!");
                }
             }
            }
              //Invoice detail insertion
      
            
            }catch(\Exception $e){
            DB::rollback();
            dd($e);
            }
      
    }
    //Deduct Stock
    function deductstock($product_id,$qty,$unit_id)
    {
        //deduct from earliest stock
        $stock = Stockdetail::select('id')->where('product_id',$product_id)->where('unit_id',$unit_id)->where('qty','>=',$qty)->orderby('id','asc')->limit(1)->get();
        if(count($stock) > 0)
        {
            $this->updatestockqty($stock[0]->id,$qty);
        }else{
            $quantity = $qty;
            $count = 0;
            //iteration when qty meets to zero
            while($quantity > 0)
            {
                $count++;
                $stock = Stockdetail::select('id','qty')->where('product_id',$product_id)->where('unit_id',$unit_id)->orderby('qty','desc')->limit(1)->get();
                
                if(count($stock) > 0)
                {
                
                    if($quantity > $stock[0]->qty){
                        $update_stock = Stockdetail::where('id',$stock[0]->id)->update(['qty' => 0]);
                    }else{
                        $update_stock = Stockdetail::where('id',$stock[0]->id)->update(['qty' => ($stock[0]->qty - $quantity)]);
                    }
                    $quantity = $quantity - $stock[0]->qty;
                     
                }else
                {
                    $quantity = 0;
                }
               
            }
            
        }
        
    }
    function updatestockqty($stock_id,$qty)
    {
        $stock=Stockdetail::find($stock_id);
        $deduct_qty=$stock->qty-$qty;
        $stock->qty=$deduct_qty;
        $stock->save();
    }  
    function insertFbrinvoiceno($invoice_id,$value)
    {
        $in=Invoice::find($invoice_id);
        $in->fbr_invoice_no=$value;
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
    //getting products from stock
    public function getstockproduct(Request $request)
    {
        $stockproduct = Stockdetail::select('stockdetails.product_id','stockdetails.unit_id','products.name','units.name as uname')->selectRaw('sum(stockdetails.qty) as a_qty')->join('products','stockdetails.product_id','=','products.id')->join('units','stockdetails.unit_id','=','units.id');
        if($request->has('category') && $request->category != ""){
            $stockproduct = $stockproduct->where('products.category_id',$request->category);
        }
        if($request->has('brand') && $request->brand != ""){
            $stockproduct = $stockproduct->where('products.brand_id',$request->brand);  
        }
        $stockproduct = $stockproduct->groupby('stockdetails.product_id')->groupby('stockdetails.unit_id')->get();
        return response()->json([
            "status" => 1,
            'data' => $stockproduct,
        ]);
    }
    //add product in sale screen listing
    public function addproductsalelist(Request $request)
    {
         //\DB::enableQueryLog();
        $product = Stockdetail::where('stockdetails.product_id',$request->product_id)->where('unit_id',$request->unit_id)->orderby('s_price','desc')->limit(1)->get(); 
       
         //dd(\DB::getQueryLog());
        return response()->json([
            "status" => 1,
            'data' => $product,
        ]);
    }


    public function getinvlistin(Request $request){

        if ($request->ajax()) {

            $data = Invoice::select('invoices.*');

            //   if(!empty($request->invid)) {

            //         $data = $data->where('fbr_invoice_no',$request->invid);

            //     }

                //get the date filter
                if(!empty($request->frmdate && $request->todate)) {
                    
                    //$data = $data->where('created_at','>=',$request->frmdate." 00:00:00")->where('created_at','<=',$request->todate." 23:59:00");
                   // $data = $data->where('created_at',$request->frmdate);
                }else{
                    $data= $data->where('invoice_amount','>',0)->whereRaw('DATE(created_at)="'.date('Y-m-d').'"')->get();
                }

                
               

               
            
                return Datatables::of($data)
                        ->addIndexColumn()

                        ->addColumn('date', function($row){
                            
                            $date = date('d-m-Y', strtotime($row->created_at));
                            return $date;

                        })

                        ->addColumn('id', function($row){
                            
                            $date = "INV-".$row->id;
                            return $date;

                        })

                        // ->addColumn('FBR-Invoice#', function($row){
                            
                        //      //$string = explode ("*", $row->fbr_invoice_no);
                        //      $string =  $row->fbr_invoice_no;
                        //      return $string;

                        //  })

                        ->addColumn('Customer', function($row){
                            
                             if($row->customer_name == NULL){
                                return "Walkin-Customer";
                             }

                         })

                         ->addColumn('taxvalue', function($row){
                            
                            $calculation = $row->tax * $row->invoice_amount / 100; 
                            return $calculation;

                        })

                        ->addColumn('action', function($row){
                            $btn = '
                            
                            <a href="'.route('getprint',$row->id).'"  class="btn btn-primary btn-sm">Print</a>
                            
                            ';
                                return $btn;
                        })
                        ->rawColumns(['action'])
                        ->rawColumns(['action'])->with('total_val', function() use ($data) {
                            return $data->sum('invoice_amount');
                       })
                       ->rawColumns(['action'])->with('total_tax', function() use ($data) {
                        return $data->sum('tax');
                   })
                        ->make(true);
            }
            else{

                $inv = Invoice::get();
                

                return view('pages.sales.listing',compact('inv'));
            }

        

    }

    public function getprint($id){


        $inv = Invoice::where('id',$id)->get();

        $invdata = $inv[0];

        
        $invdetail = Invoicedetail::select('invoicedetails.*','products.name as pname')
                                    ->join('products','invoicedetails.product_id','=','products.id')
                                    ->where('inv_id',$invdata->id)->get();

        

        return view('pages.sales.print',compact('invdata','invdetail'));

    }
}
