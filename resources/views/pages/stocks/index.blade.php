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
    @if(session()->has('message'))
    <div class="alert alert-success">
        <strong>Success!</strong> {{ session()->get('message') }}
      </div>
    @endif
    
    @if(session()->has('error'))

    <div class="alert alert-success">
        <strong>Success!</strong> {{ session()->get('error') }}
      </div>
    @endif
   
    <form method="post" action="{{route('stock.store')}}">
        {{csrf_field()}}
        @method('POST')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary">
                <div class="row">
                    <div class="col-md-2">
                      
                        <a href="{{route('dashboard')}}" class="btn btn-info">Dashboard</a>
                    </div>
                    <div class="col-md-8">
                        <select class="form-control" name="vendor" required>
                            <option value="">Select Vendor</option>
                            @foreach ($vendors as $v)
                            <option value="{{$v->id}}">{{$v->name}}</option>
                             @endforeach
                        </select>
                    </div>
                    
                </div>
                </div>

                <div class="card-body">
                
                     {{-- <form method="post" action="">
                        {{csrf_field()}}
                        @method('POST') --}}
                   <div class="row">
                    <div class="col-md-4">
                        <label>Category</label>
                        <select onchange="getProducts()" id="category" class="form-control">
                            <option value="">Select Category</option>
                            @foreach ($categories as $c)
                            <option value="{{$c->id}}">{{$c->name}}</option>
                             @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Brand</label>
                        <select onchange="getProducts()" id="brand" class="form-control">
                            <option value="">Select Brand</option>
                            @foreach ($brands as $b)
                            <option value="{{$b->id}}">{{$b->name}}</option>
                             @endforeach
                        </select>
                    </div>
                   </div>
                   <div class="row">
                    <div class="col-md-6">
                        <label>Product</label>
                        <select id="product" class="form-control">
                        
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Select Unit</label>
                        <select id="unit" class="form-control">
                        
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Enter Quantity</label>
                        <input class="form-control" value="1" id="qty" required/>
                    </div>
                    <div class="col-md-1">
                        <label>GST</label>
                        <select class="form-control" id="gst">
                            <option value="0">N0</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <br>
                        <button type="button" onclick="addProduct()" style="margin-top:7px;" class="btn btn-primary">+Add</button>
                    </div>
                   </div>
                    {{-- </form> --}}
                </div>
            </div>
        </div>
    </div>
   
    <div class="row justify-content-center">
         
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">{{ __('Products List') }}</div>

                <div class="card-body">
                    <div style="height:400px !important;overflow:scroll;" class="row justify-content-center table-responsive">
                        <table id="customer-table" class="table table-bordered table-stripped">
                            <thead>
                                <tr>
                                   
                                    <th>NO</th>
                                    <th>Product</th>
                                    <th>Unit</th>
                                    <th>Purchase Price</th>
                                    <th>Sale Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="result">
                              
                            </tbody>
                        </table>
                        
                    </div>
                   

                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-8">
                            <select name="payment_type" class="form-control">
                                <option value="0">Cash</option>
                                <option value="1">Bank</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary float-right">Save</button>
                        </div>
                    </div>
                    
                </div>

                <div class="card-body">
                    <div class="row justify-content-center table-responsive">
                        <table id="customer-table" class="table table-bordered table-stripped">
                          <tr>
                            <th>Total Quantity</th>
                            <td><input type="text" class="form-control" value="0" id="total_qty" name="total_qty" readonly/></td>
                          </tr>
                          <tr>
                            <th>Total</th>
                            <td><input type="text" class="form-control" value="0" id="total_amount" name="total_amount" readonly/></td>
                          </tr>
                          <tr>
                            <th>Discount</th>
                            <td><input onkeyup="calculateTotal()" type="number" value="0" name="discount" id="discount" class="form-control"/></td>
                          </tr>
                          <tr>
                            <th>Grand Total</th>
                            <td><input type="text" class="form-control" value="0" id="grand_total" name="grand_total" readonly/></td>
                          </tr>
                          <tr>
                            <td>
                                <select onchange="checkpartial(this.value)" name="payment_status" id="payment_status" class="form-control">
                                    <option value="0">Paid</option>
                                    <option value="1">Unpaid</option>
                                    <option value="2">Partial</option>
                                </select>
                            </td>
                            <td>
                              <input class="form-control" style="display:none;" type="number" value="0" name="partial_amount" id="partial_amount"/> 
                            </td>
                          </tr>
                          <tr>
                            <td colspan="12">
                                <textarea class="form-control" name="detail"></textarea>
                            </td>
                          </tr>
                        </table>
                        
                    </div>
                    

                </div>
            </div>
        </div>
        
    </div>
</form>
</div>
 
<script>
    let products = [];
    let units = [];
    let product_check=[];
    getProducts();
    getProductUnits();
    function getProducts()
    {
        let category = $('#category').val();
        let brand = $('#brand').val();
        $.ajax({
            url : "{{route('getProduct')}}",
            method : "GET",
            data : { _token: $("meta[name='csrf-token']").attr("content"),"category": category,"brand": brand},
            success:function(data){
               if(data.status == 1)
               {
                let result = data.data;
                
                if(result.length > 0){
                    let product_list = '<option value="">Select Product</option>';
                    products = [];
                    result.forEach(function(result,index) {
                        product_list +=`<option value="${result.id}">${result.name}</option>`;
                       
                        products.push({"id":result.id,"name":result.name});
                    });
                    $('#product').html(product_list);
                    console.log(products);
                }
               }
            }
        })
    }
    
    function getProductUnits()
    {
        $.ajax({
            url : "{{route('getProductUnit')}}",
            method : "GET",
            data : { _token: $("meta[name='csrf-token']").attr("content")},
            success:function(data){
               if(data.status == 1)
               {
                let result = data.data;
                units = [];
                if(result.length > 0){
                    let unit_list = '';//'<option value="">Select Unit</option>';
                    result.forEach(function(result,index) {
                        unit_list +=`<option value="${result.id}">${result.name}</option>`;
                        units.push({"id":result.id,"name":result.name});
                    });
                    $('#unit').html(unit_list);
                    console.log(units);
                }
               }
            }
        })
    }
    //adding products in to list of table
    let count = 0;
    function addProduct()
    {
        let product_id = $('#product').val();
        
        let unit_id = $('#unit').val();
        let qty = $('#qty').val();
        let gst = $('#gst').val();
        if(product_id != "" && product_id != null && unit_id != "" && qty > 0)
        {
            if(product_check.indexOf(product_id)!== -1)
            {
                let temp = $('#qty_'+product_id).val();
                temp++;
                $('#qty_'+product_id).val(temp);
                calculateRowTotal(product_id);
                calculateTotal();
                return
            }
            let products_index = getindexofarr(product_id,"product");
            let units_index = getindexofarr(unit_id,"unit");
            count++;
            product_check.push(product_id);
            let html = ``;
            html+=`<tr id="row_${products[products_index].id}">
                <input type="hidden" name="product_id[]" value="${products[products_index].id}"/>
                <input type="hidden" name="unit_id[]" value="${units[units_index].id}"/>
                <input type="hidden" name="gst[]" value="${gst}"/>
                <td>${count}</td>
                <td>${products[products_index].name}</td>
                <td>${units[units_index].name}</td>
                <td><input onkeyup="calculateRowTotal('${products[products_index].id}')" type="number" name="p_price[]" id="p_price_${products[products_index].id}" class="form-control" value="0"/></td>
                <td><input type="number"  class="form-control" name="s_price[]" value="0"/></td>
                <td><input onkeyup="calculateRowTotal('${products[products_index].id}')" type="number" name="qty[]" id="qty_${products[products_index].id}" class="form-control quantity" value="${qty}"/></td>
                <td><input readonly name="row_total[]" id="row_total_${products[products_index].id}"  type="number" class="form-control total" value="0"/></td>
                <td><button type="button" onclick="removeRow('${products[products_index].id}')" class="btn btn-danger btn-sm">X</button></td>
                </tr>`;
                $('#result').append(html);
        }else{
            alert("Please Select all value");
        }
    }
    //getting array index by value
    function getindexofarr(value,type)
    {
        let elementIndex = -1;
        if(type == "product"){
            products.forEach((element, index, array) => {
                if (element["id"] == value) {
                    elementIndex = index;
                }
                });
        }
        if(type == "unit"){
            units.forEach((element, index, array) => {
                if (element["id"] == value) {
                    elementIndex = index;
                }
                });
        }
       
        return elementIndex;
    }
    function removeRow(id)
    {
        if(count > 0)
        {
            $('#row_'+id).remove();
            count--;
        }
        
    }
    //calculate row total
    function calculateRowTotal(id)
    {
      let p_price = parseInt($('#p_price_'+id).val());
      let qty =  parseInt($('#qty_'+id).val());
      let total_of_row = p_price * qty;
      $('#row_total_'+id).val(total_of_row);
      calculateTotal();
    }
    function calculateTotal(){
        let total = 0;
        let total_qty = 0;
        $(".quantity").each(function() {
            total_qty=total_qty+parseInt($(this).val());
        });
        $('#total_qty').val(total_qty);

        $(".total").each(function() {
            total=total+parseInt($(this).val());
        });
        $('#total_amount').val(total);
        let discount = parseInt($('#discount').val());
        let grand_total = total - discount;
        $('#grand_total').val(grand_total);
    }
    function checkpartial(value)
    {
        $("#partial_amount").hide();
        if(value == 2){
            $("#partial_amount").show();
        }
    }
    $(document).ready(function(){

    })
</script>
@endsection
