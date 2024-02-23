<!DOCTYPE html>
<html lang="en">

<script src="{{ asset('js/jquery.min.js')}}"></script>
<head>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'PT Sans', sans-serif;
        }

        @page {
            size: 2.8in 11in;
            margin-top: 0cm;
            margin-left: 0cm;
            margin-right: 0cm;
        }

        table {
            width: 100%;
            border: 1px solid black;
        }

        tr {
            width: 100%;

        }

        h1 {
            text-align: center;
            vertical-align: middle;
        }

        #logo {
            width: 60%;
            text-align: center;
            -webkit-align-content: center;
            align-content: center;
            padding: 5px;
            margin: 2px;
            display: block;
            margin: 0 auto;
        }

        header {
            width: 100%;
            text-align: center;
            -webkit-align-content: center;
            align-content: center;
            vertical-align: middle;
        }

        .items thead {
            text-align: center;
        }

        .center-align {
            text-align: center;
        }

        .bill-details td {
            font-size: 12px;
        }

        .receipt {
            font-size: medium;
          
        }

        .items .heading {
            font-size: 12.5px;
            text-transform: uppercase;
            border-top:1px solid black;
            margin-bottom: 4px;
            border-bottom: 1px solid black;
            vertical-align: middle;
        }

        .items thead tr th:first-child,
        .items tbody tr td:first-child {
            width: 47%;
            min-width: 47%;
            max-width: 47%;
            word-break: break-all;
            text-align: left;
        }

        .items td {
            font-size: 12px;
            text-align: right;
            vertical-align: bottom;
        }

        .price::before {
             /* content: "\20B9"; */
            font-family: Arial;
            text-align: right;
        }

        .sum-up {
            text-align: right !important;
        }
        .total {
            font-size: 13px;
            border-top:1px dashed black !important;
            border-bottom:1px dashed black !important;
        }
        .total.text, .total.price {
            text-align: right;
        }
        .total.price::before {
            /* content: "\20B9";  */
        }
        .line {
            border-top:1px solid black !important;
        }
        .heading.rate {
            width: 20%;
        }
        .heading.amount {
            width: 25%;
        }
        .heading.qty {
            width: 5%
        }
        p {
            padding: 1px;
            margin: 0;
        }
        section, footer {
            font-size: 12px;
        }
        .centered {
        text-align: center;
        align-content: center;
      }

      .bor{
        border-right: 1px solid black;
        border-bottom: 1px solid black;
      }
    </style>
</head>

<body>

    <br>
    <header>
        <img src="{{config('global.company.brandlogo')}}" height="64" width="64" style="margin-top:40px;">
       
    </header>
    
    <table class="bill-details">
        <tbody>
            <tr>
                <td><b>Invoice# : </b><span><br>{{"INV- ".$invdata->id}}</span></td> 
                <td><b>Date : </b><span><br> {{date('d-m-Y', strtotime($invdata->created_at)) }}</span></td>
            </tr>
            <tr>
                
                <td><b>STRN : </b><span>{{config('global.company.STRN')}}</span></td> 
                <td><b>NTN : </b><span>{{config('global.company.NTN')}}</span></td>
            </tr>
            
            <tr>
               
                <td><b>Customer : </b><span>
                    @php
                        if ($invdata->customer_name == NULL) {
                            
                            $customer = "Counter-Sale";
                        }

                    @endphp
                    <br>
                    {{$customer}}
                
                </span></td>
            </tr>
            
             <br>  
           
                
                <h3><span style="margin-left: 27%;">Sale Receipt</span></h3>
          
        </tbody>
    </table>
    <br>
    <table class="items">
        <thead>
            <tr>
                <th class="heading name">Item</th>
                <th class="heading qty">Qty</th>
                <th class="heading amount">Amount</th>
            </tr>
        </thead>
       
        <tbody>
            <?php $sum=0; $total=0;?>
         
            @foreach ($invdetail as $key=> $s)

            @php
                $subtotal = $s->product_price . " Rs";
                $tax = $s->taxvalue; 
                $discount = $s->discount; 
                $total+= $s->product_price * $s->qty;
            @endphp
            <tr>
                <td class="bor">{{$s->pname}} <br> [Tax ({{config('global.fbrapidata.tax')."% " . nl2br($tax)}})]</td>
                <td class="bor">{{$s->qty}}</td>
                <td class="bor">{{$subtotal}}</td>
            </tr>  
            @endforeach

            <tr>
                <td colspan="2" class="sum-up line">Subtotal</td>
                <td class="line price">{{$total." Rs"}}</td>
            </tr>
           
            <tr>
                <td colspan="2" class="sum-up">Total Tax</td>
                <td class="price">{{$invdetail->pluck('taxvalue')->sum()}}</td>
            </tr>
            <tr>
                <td colspan="2" class="sum-up">Discount</td>
                <td class="price">{{$invdata->discount . " Rs"}}</td>
            </tr>
            <tr>
                <td colspan="2" class="sum-up">
                    FBR POS Service Fee <br>
                    <small>(Not Added In Total)</small>
                </td>
                <td class="price">1</td>
            </tr>
            <tr>
                <th colspan="2" class="total text">Total</th>
                <th class="total price">{{$invdata->invoice_amount - $invdata->discount  . " Rs"}}</th>
            </tr>
        </tbody>
    </table>
    <section>
        <p style="text-align:center">
            Paid by : <span>CASH</span>
        </p>
       
      <table>
        <tr>
            <td class="centered" colspan="3">
              Your FBR Invoice# <strong>
                @php
                             $string = explode ("*", $invdata->fbr_invoice_no);
                @endphp

                {{$string[0]}}
                
            
            </strong>
              <br>
              
              {!! QrCode::size(70)->generate($string[0]) !!}
              <img src="{{config('global.company.logo')}}" height="64" width="64" style="margin:10px 0;filter: brightness(0);margin-left: 20px">
              <br><br>
              Verify this invoice through FBR TaxAsaan MobileApp or SMS at 9966 and win exciting prizes in draw.
            </td>
          </tr>    

      </table>

    </section>
    

    <script>
        window.onload = function() { window.print(); }
        setTimeout(
           function(){
             window.location = document.referrer;
           },
       1000);
      
   </script>
   
</body>

</html>