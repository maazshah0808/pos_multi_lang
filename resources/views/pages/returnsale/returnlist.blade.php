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
                        <h2 class="card-title mb-0">Return Listing</h2>
                    </div>
                    <div class="row">

                        <div class="col-md-2">
                            <br>    
                            <button type="button" class="btn btn-primary waves-effect waves-light mt-2" data-toggle="modal" data-target="#addmodel">
                                Add Return
                            </button>

                         </div>

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
                                    <table id="data-table" class="table table-striped nowrap">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Return ID</th>
                                                <th>Invoice ID</th>
                                                <th>FBR Invoice</th>
                                                <th>Invoice Amount</th>
                                                <th>Date</th>
                                                {{-- <th>Action</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
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

    
     {{-- model window to add  --}}

     <div class="modal fade" id="addmodel" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addmodel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Invoice Return</h5>
                    <button type="button" class="close waves-effect waves-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form  method="GET" action="{{route('salereturn.index')}}">
                        @csrf

                       <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Enter Invoice ID<span class="text-danger">*</span></label>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon1">INV-</span>
                                    <input type="number" name="return" class="form-control" step="0.0" min="0" oninput="validity.valid||(value='');">
                                  </div>
                            </div>
                       </div>
                   
                    <br>
                    <div class="form-group">
                        <div>
                            <button class="btn btn-primary w-24">Search </button>

                            <button type="button" class="btn btn-secondary waves-effect waves-light" data-dismiss="modal">Close</button>

                        </div>
                    </div>
                </form>


                </div>
            </div>
        </div>
    </div>
    {{-- add model window  --}}

  

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
               url: "{{ route('getreturnlist') }}",
               data:{
                    frmdate:frmdate,
                    todate:todate,
            
                 }
            },

          
            columns: [
                
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: true, searchable: false },
               
                {data: 'returnid', name: 'returnid'},
                {data: 'invid', name: 'invid'},
                {data: 'FBR-Invoice', name: 'FBR-Invoice'},
                {data: 'total_amount', name: 'total_amount'},
                {data: 'date', name: 'date'},

                // {
                //     data: 'action', name: 'action', 
                //     orderable: false, searchable: false
                // },
            ], 
            initComplete: function (data) {
                $('#total').text(addCommas(data.json.total_val));
            //console.log(data.json.total_val)
        
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
                        
                    if(from > to){
                       alert('From date must be greater than To date');
                    }
                    else{
                            if(from != '' && to != ''){
                            $('#data-table').DataTable().destroy();
                            getdata(from,to);
                        }
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
