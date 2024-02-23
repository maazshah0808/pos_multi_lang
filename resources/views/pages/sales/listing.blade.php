<x-app-layout>

 
    @section('content')
 
<br><br><br><br>
<div class="container-fluid">
   
{{-- error message  --}}
@if ($errors->any())
<div  class="alert alert-danger show mb-2" role="alert">
        @foreach ($errors->all() as $error)
        {{ $error }}
        @endforeach
</div>
@endif

@if(session()->has('message'))
<div class="alert alert-success show flex items-center mb-2" role="alert"> 
    <i data-lucide="alert-circle" class="w-6 h-6 mr-2"></i> 
    {{ session()->get('message') }}
</div>
@endif

@if(session()->has('error'))
<div class="alert alert-danger show flex items-center mb-2" role="alert"> <i data-lucide="alert-circle" class="w-6 h-6 mr-2"></i> 
    {{ session()->get('error') }}
</div>
@endif

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="mb-4">
                        <h2 class="card-title mb-0">Invoice Listing</h2>
                    </div>
                    <div class="row">

                        {{-- <div class="col-md-4">
                            <label for="">FBR Invoice</label>
                            <select class="form-control" id="filtername">
                                <option value="">-- FBR INVOICE --</option>
                              
                            @foreach ($inv as $no => $invs)
                            @php
                             $string = explode ("*", $invs->fbr_invoice_no);
                            @endphp
                                <option value="{{$invs->fbr_invoice_no}}">{{$invs->fbr_invoice_no}}</option>
                             @endforeach
                        
                            </select>
                        </div> --}}

                        
                        <div class="col-md-2">
                            <label for="">From Date</label>
                            <input type="date" class="form-control" id="frmdate" value="{{date('Y-m-d')}}">
                        </div>
                        <div class="col-md-2">
                            <label for="">To Date</label>
                            <input type="date" class="form-control" id="todate" value="{{date('Y-m-d')}}">
                        </div>
                        <div class="col-md-4">
                            <br>
                             <button type="button" name="filter" id="filter" class="btn btn-primary mt-2">Filter</button>
                             <button type="button"  id="reset" class="btn btn-primary mt-2">Reset</button>

                         </div>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <br>
                                    <table id="data-table" class="table table-striped nowrap text-center">
                                        <thead>
                                            <tr>
                                                <th>Invoice #</th>
                                                <th>Date</th>
                                                <th>Customer</th>
                                                <th>Invoice Amount</th>
                                                <th>Tax Rate (%)</th>
                                                <th>Taxable Value</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                          
                                        </tbody>
                                        <tfoot style="border-top:2px solid black; text-align:center;">
                                            <tr>
                                                <th></th>
                                                <th>Total</th>
                                                <th>-</th>
                                                <th id="total"></th>
                                                <th>-</th>
                                                <th>-</th>
                                                <th>-</th>
                                            </tr>
                                            {{-- <th></th>
                                            <th>Total</th>
                                            
                                           
                                            <th colspan=4 ></th>
                                            <th colspan=4 id="total"></th>
                                            <th colspan=4 id="">- </th> --}}
                                            
                                        </tfoot>
                                    </table>
                                    
                                </div> <!-- end card body-->
                            </div> <!-- end card -->
                        </div><!-- end col-->
                    </div>
                    <!-- end row-->

                 
                </div>
                <!--end card body-->
            </div><!-- end card-->
        </div> <!-- end col-->

    </div>

    

<script> 

    $(document).ready(function(){
    
    //datatable
    
    getdata();
    
    });
    
        function getdata(frmdate = '', todate = ''){
    
         $('#data-table').DataTable({
            "order": [[ 0, "desc" ]],
        processing: true,
        serverSide: true,
        ajax: 
        {
           url: "{{ route('getinvlist') }}",
           data:{
                frmdate:frmdate,
                todate:todate,
        
             }
        },
        dom: 'Bfrtip',
        buttons: [
            { extend: 'copyHtml5', footer: true },
            { extend: 'excelHtml5', footer: true },
            { extend: 'csvHtml5', footer: true },
            { extend: 'pdfHtml5', footer: true }
        ],
        columns: [
            
        { data:  'id', name: 'id', orderable: true, searchable: false },
        
            {data: 'date', name: 'date'},
            {data: 'Customer', name: 'Customer'},
            {data: 'invoice_amount', name: 'invoice_amount'},
            {data: 'tax', name: 'tax'},
            {data: 'taxvalue', name: 'taxvalue'},
            {
                data: 'action', name: 'action', 
                orderable: false, searchable: false
            },
        ], 
        initComplete: function (data) {
            $('#total').text(addCommas(data.json.total_val));
            // $('#tax').text(addCommas(data.json.total_val));
            console.log(data.json.total_tax)  
        }
            
         });
        }
    
        $('#filter').click(function(){

               // var inv = $('#filtername').val();
                var from = $('#frmdate').val();
                var to = $('#todate').val();

                // if(inv != '' || from != '' || to != '' ){
                //     $('#data-table').DataTable().destroy();
                //     getdata(inv);
                // }

                if(from != '' && to != ''){
                    $('#data-table').DataTable().destroy();
                    getdata(from,to);
                }

            });
            

            $('#reset').click(function(){
                
                    $('#data-table').DataTable().destroy();
                  //  $('#filtername').val('');
                    $('#frmdate').val('');
                    $('#todate').val('');
                    getdata();
            });
    
function addCommas(nStr) {
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}

        </script>
 

 

     @endsection
</x-app-layout>
