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
.loader {
                  border: 16px solid #f3f3f3;
                  border-radius: 50%;
                  border-top: 16px solid #3498db;
                  width: 120px;
                  height: 120px;
                  -webkit-animation: spin 2s linear infinite; /* Safari */
                  animation: spin 2s linear infinite;
                  position: absolute;
                  top: 40%;
                    left: 60%;
                }
                
                /* Safari */
                @-webkit-keyframes spin {
                  0% { -webkit-transform: rotate(0deg); }
                  100% { -webkit-transform: rotate(360deg); }
                }
                
                @keyframes spin {
                  0% { transform: rotate(0deg); }
                  100% { transform: rotate(360deg); }
                }

</style>

    <div class="container-fluid">
        
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">

                        <form method="post" action="{{route('sales.store')}}" >
                            {{csrf_field()}}
                            @method('POST')
                            <input type="hidden" value="{{config('global.fbrapidata.tax')}}" name="tax_price" id="tax_price"/> 
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Ref No <span style="font-size: 9px;color: red;"><b>(Optional)</b></span></label>
                                                <input type="text" id="ref_no" name="reference_no" class="form-control" placeholder="Type reference number"> </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Customer Name  <span style="font-size: 9px;color: red;"><b>(Optional)</b></span></label>
                                                <input placeholder="xyz" type="text" name="customer_name" class="form-control"/>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Contact <span style="font-size: 9px;color: red;"><b>(Optional)</b></span></label>
                                                <input placeholder="03xxxxxxxxx" type="text" name="customer_contact" class="form-control"/>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="search-box form-group">
                                                <input type="text" name="product_code_name" id="lims_productcodeSearch" placeholder="Scan/Search product by name/code" class="form-control ui-autocomplete-input" autocomplete="off" fdprocessedid="4x5gjh"> </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div style="display:none;" class="loader"></div>
                                        <div class="col-md-12">
                                            <div style="height:450px;">
                                       
                                                <table class="table table-hover table-striped" >
                                                    <thead>
                                                        <tr>
                                                            
                                                            <th class="col-sm-4">Product</th>
                                                            <th class="col-sm-2">Price</th>
                                                            <th class="col-sm-2">Tax</th>
                                                            <th class="col-sm-2">Tax Value</th>
                                                            <th class="col-sm-3">Quantity</th>
                                                            <th class="col-sm-3">SubTotal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="result">
                                                            
                                                            
                                                    </tbody>
                                                </table>
                                               
                                                            
                                                
                                            </div>
                                    </div>
                                    
                                    <div class="col-12 totals" style="border-top: 2px solid #e4e6fc; padding-top: 10px;">
                                        <div class="row">
                                            <div class="col-sm-2"> <span class="totals-title">Items</span> = <span id="item">0</span> </div>
                                            <div class="col-sm-2"> <span class="totals-title">Total</span> = <span id="total_amount"> 0.00</span>
                                            <input type="hidden" name="total_amount" id="total_amount_in"/> 
                                            </div>
                                            
                                            <div class="col-sm-2"> <span class="totals-title">Tax</span> = <span id="tax"> 17%</span></div>
                                            <div class="col-sm-2"> <span class="totals-title">Qty</span> = <span id="total_qty"> 0</span>
                                                <input type="hidden" name="total_qty" id="total_qty_in"/> 
                                            </div>
                                            <div class="col-sm-4"> 
                                                <div class="row">
                                                    <div class="col-sm-4"> <span class="totals-title">Discount</span></div> 
                                                    <div class="col-sm-8"><input onkeyup="calculateTotal()" value="0" type="number" class="form-control" name="discount" id="discount"/></div> 
                                                </div>
                                            </div>
                                           
                                           
                                        </div>
                                    </div>
                                       
                                    
                                </div>

                                <div style="
                                background-color: #d6deff;
                                margin-top: 10px;
                                text-align: center;
                            ">

                                <h2>Grand Total <span id="grand_total">0.00</span></h2>
                                <input type="hidden" name="grand_total" id="grand_total_in"/>
                            </div>


                            <div class="col-md-12">

                                <button class="btn btn-primary" style="width:200px;"> Cash</button>
                                <button class="btn btn-danger" style="width:200px;"> Cancel</button>
                                <a  href="{{route('dashboard')}}" class="btn btn-info" style="width:200px;"> Dashboard</a>
                            </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
            



            {{-- products --}}

             <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <a href="{{route('dashboard')}}" class="btn btn-info">Dashboard</a>
                                <hr>
                            </div>
                            <div class="col-md-6">
                                <label>Category</label>
                                <select onchange="getProductFromStock()" id="category" class="form-control">
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $c)
                                    <option value="{{$c->id}}">{{$c->name}}</option>
                                     @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Brand</label>
                                <select onchange="getProductFromStock()" id="brand" class="form-control">
                                    <option value="">Select Brand</option>
                                    @foreach ($brands as $b)
                                    <option value="{{$b->id}}">{{$b->name}}</option>
                                     @endforeach
                                </select>
                            </div>
                           </div>
                           <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <div style="height:670px;">
                           
                                    <table class="table">

                                        <tbody>
                                             <tr>
                                                <div id="product_list" class="row">
                                                </div>
                                                </tr> 
                                        </tbody>
                                    </table>
                                </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
<script>
   
    let products=[];
    let product_check = [];
    let tax_price = parseInt($('#tax_price').val());
    $(".loader").show();
    window.onload = function()
    {
        $(".loader").hide();
    }
    getProductFromStock();
    function play() {
            var audio = new Audio('{{ asset("assets/media/beep.mp3")}}');
            audio.play();
        }
      
    function getProductFromStock()
    {
        
        let category = $('#category').val();
        let brand = $('#brand').val();
        $.ajax({
            url : "{{route('getStockProduct')}}",
            method : "GET",
            data : { _token: $("meta[name='csrf-token']").attr("content"),"category":category,"brand":brand},
            success:function(data){
               if(data.status == 1)
               {
                let result = data.data;
                //console.log(result);
                if(result.length > 0){
                    let product_list = '';
                    products = [];
                    result.forEach(function(result,index) {
                        product_list += `
                        <div onclick="generateRow('${result.product_id}','${result.product_id}_${result.unit_id}')" style="cursor: pointer;" class="col-md-4">
                         <td>
                            <div class="card card-body text-center" style="background: rgb(214 222 255);">
                             <p class="card-text">
                               <b> ${result.name}</b><br>
                               <span style="color:red;"><b>(${result.uname})</b></span>
                             </p>
                            
                            </div>
                         </td>
                        </div>
                        `;
                        products.push({"id":result.product_id,"name":result.name,"a_qty":result.a_qty,"unit_id":result.unit_id,"index":result.product_id+"_"+result.unit_id,"uname":result.uname});
                    });
                    $('#product_list').html(product_list);
                    //console.log(products);
                    // $(".loader").hide();
                }
               }
            }
        })
    }
    let count = 0;
    function generateRow(product_id,index)
    {
        play();
        $(".loader").show();
        if(product_check.indexOf(index)!== -1)
        {
            let temp = $('#qty_'+index).val();
            temp++;
            $('#qty_'+index).val(temp);
            calculateRowTotal(index);
            calculateTotal();
            $(".loader").hide();
            return
        }
        count++;
        let products_index = getindexofarr(index,"product");
                let unit_id = products[products_index].unit_id;
        $.ajax({
            url : "{{route('addProductSaleList')}}",
            method : "GET",
            data : { _token: $("meta[name='csrf-token']").attr("content"),"product_id":product_id,"unit_id":unit_id},
            success:function(data){
               if(data.status == 1)
               {
                let result = data.data;
                
                if(result.length > 0){
                    product_check.push(index);
                    let html = '';
                    result.forEach(function(result,indexx) {
                        let s_price = parseFloat(result.s_price);
                        let new_price = (s_price*tax_price)/100;
                        let tax_value = new_price;
                        //alert(typeof new_price);
                        new_price = s_price + new_price;
                        html+=`<tr id="row_${index}">
                        <input type="hidden" name="product_id[]" value="${product_id}"/>
                        <input type="hidden" name="unit_id[]" value="${products[products_index].unit_id}"/>
                        <td>${products[products_index].name}<span style="color:red;"><b> (${products[products_index].uname})</b></span></td>
                        <input type="hidden" value="${products[products_index].name}" name="product_name[]"/>
                        <input type="hidden" value="${tax_value}" name="tax_value[]" id="tax_value_${index}"/>
                        <td><input id="s_price_${index}" onkeyup="calculateRowTotal('${index}')" type="number" name="s_price[]" value="${result.s_price}" class="form-control" value="0"/></td>
                        <td>${tax_price}%</td>
                        <td><input type="text" readonly value="${new_price}" name="newprice[]" id="newprice_${index}" class="form-control"/> </td>
                        <td><input id="qty_${index}" onkeyup="calculateRowTotal('${index}')" type="number" name="qty[]" class="form-control quantity" value="1"/></td>
                        <td><input readonly name="row_total[]" id="row_total_${index}"  type="number" class="form-control total" value="0"/></td>
                        <td><button type="button" onclick="removeRow('${index}')" class="btn btn-danger btn-sm">X</button></td>
                        </tr>`;
                    });
                    $('#result').append(html);
                    console.log(products);
                    calculateRowTotal(index);
                    calculateTotal();
                    $(".loader").hide();
                }
               }
            }
        })   
    }
       //getting array index by value
    function getindexofarr(value,type)
    {
        let elementIndex = -1;
        if(type == "product"){
            products.forEach((element, index, array) => {
                if (element["index"] == value) {
                    elementIndex = index;
                }
                });
        }
       
        return elementIndex;
    }
      //calculate row total
    function calculateRowTotal(id)
    {
        
      let products_index = getindexofarr(id,"product");
      //alert(products_index);
      let qty =  parseInt($('#qty_'+id).val());
      let a_qty = parseInt(products[products_index].a_qty);
      if(a_qty > 0){
            if(qty > a_qty){
            alert("Quantity Exceeded ,Available Quantity is = "+a_qty);
            qty = a_qty;
            $('#qty_'+id).val(a_qty);
        
        }
      }else{
        alert("Quantity Exceeded ,Available Quantity is = "+a_qty);
        $('#row_'+id).remove();
      }

      
      let s_price = parseFloat($('#s_price_'+id).val());
    
      let s_price_tax = (s_price*tax_price)/100;
      $('#tax_value_'+id).val(s_price_tax);
      s_price = s_price + s_price_tax;
      $('#newprice_'+id).val(s_price);
      let total_of_row = s_price * qty;
      $('#row_total_'+id).val(total_of_row);
      calculateTotal();
    }
    function removeRow(id)
    {
        if(count > 0)
        {
            $('#row_'+id).remove();
            count--;
            calculateTotal();
            let index = product_check.indexOf(id);
            product_check.splice(index, 1);
        }
        
    }
    function calculateTotal(){
        let total = 0;
        let total_qty = 0;
        $(".quantity").each(function() {
            total_qty=total_qty+parseInt($(this).val());
        });
        $('#total_qty').html(total_qty);
        $('#total_qty_in').val(total_qty);

        $(".total").each(function() {
            total=total+parseFloat($(this).val());
        });
        $('#total_amount').html(total);
        $('#total_amount_in').val(total);
        let discount = parseInt($('#discount').val());
        let grand_total = total - discount;
        $('#grand_total').html(grand_total);
        $('#grand_total_in').val(grand_total);
        $('#item').html(count);
    }
    $(document).ready(function(){

    });
</script>
    @endsection
