@extends('layouts.pos_header')

@section('poscontent')
<style>
    /* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
</style>
<div class="container-fluid">

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    @if(session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
    @endif
   
    <form onsubmit="checkform(event)" id="form" method="post" action="{{route('salereturn.store')}}">
        {{csrf_field()}}
        @method('POST')
        <input type="hidden" name="invoice_id" value="{{$invoice->id}}"/>
        <input type="hidden" name="fbr_invoice_no" value="{{$invoice->fbr_invoice_no}}"/>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary">
                <div class="row">
                    <div class="col-md-2">
                      
                        <a href="{{route('dashboard')}}" class="btn btn-info">Dashboard</a>
                    </div>                    
                </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="text-white bg-primary">
                                <tr>
                                    <th>Ref No</th>
                                    <th>Name</th>
                                    <th>Contact</th>
                                    <th>Detail</th>
                                    <th>Invoice Amount</th>
                                    <th>Quantity</th>
                                    <th>Discount</th>
                                    <th>Tax</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{$invoice->ref_no == "" ? "N/A" : $invoice->ref_no}}</td>
                                    <td>{{$invoice->customer_name == "" ? "Walking Customer" : $invoice->customer_name}}</td>
                                    <td>{{$invoice->customer_contact}}</td>
                                    <td>{{$invoice->detail}}</td>
                                    <td>{{$invoice->invoice_amount}}</td>
                                    <td>{{$invoice->total_qty}}</td>
                                    <td>{{$invoice->discount}}</td>
                                    <td>{{$invoice->tax}}</td>
                                    <td>{{$invoice->created_at}}</td>
                                </tr>
                            </tbody>
                        </table>    
                    </div>   
                </div>
                
            </div>
            <div class="card">
                <div class="card-header bg-primary">
                <div class="row">
                    <div class="col-md-2">
                      
                        <h5 class="text-white">Invoice Detail</h5>
                    </div>                    
                </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="text-white bg-primary">
                                <tr>
                                    <th>No</th>
                                    <th>Product Name</th>
                                    <th>unit</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Return Quantity</th>
                                  
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoicedetail as $key => $in)
                                @php
                                    $taxvalue = ($in->product_price*config('global.fbrapidata.tax'))/100;
                                @endphp
                                <input type="hidden" name="product_id[]" value="{{$in->product_id}}"/>
                                <input type="hidden" name="product_name[]" value="{{$in->name}}"/>
                                <input type="hidden" name="unit_id[]" value="{{$in->unit_id}}"/>
                                <input type="hidden" name="product_price[]" value="{{$in->product_price}}"/>
                                <input type="hidden" name="price[]" value="{{$in->price}}"/>
                                <input type="hidden" name="qty[]" value="{{$in->qty}}"/>
                                <input type="hidden" name="tax_value[]" value="{{$taxvalue}}"/>
                                    <tr id="row_{{$in->product_id."_".$in->unit_id}}">
                                        <td>{{++$key}}</td>
                                        <td>{{$in->name}}</td>
                                        <td>{{$in->uname}}</td>
                                        <td>{{$in->price}}</td>
                                        <td>{{$in->qty}}</td>
                                        <td><input name="r_qty[]" max="{{$in->qty}}" id="qty_{{$in->product_id."_".$in->unit_id}}" style="width:100px;" type="number" value="0" class="form-control rqty"/></td>
                                       
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>    
                    </div> 
                    <div class="row">
                        <div class="col-md-6">
                            <label>Detail</label>
                            <textarea name="detail" class="form-control"></textarea>
                        </div>    
                    </div>  
                </div>
                <div class="card-body">
                    <button class="btn btn-primary float-right">Return</button>
                </div>
            </div>
        </div>
    </div>
 
</form>
</div>
 
<script>
    function checkform(e){
        let total_qty = 0;
        $(".rqty").each(function() {
            total_qty=total_qty+parseInt($(this).val());
        });
        if(total_qty == 0)
        {
            e.preventDefault();
            alert("There is no thing to return");
        }
    }
    $(document).ready(function(){

    })
</script>
@endsection
