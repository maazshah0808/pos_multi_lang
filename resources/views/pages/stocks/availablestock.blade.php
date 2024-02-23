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
                        <h2 class="card-title mb-0">Available Stock</h2>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        
                                        <div class="col-md-4">
                                            <label for="">Product Name</label>
                                            <select class="form-control" name="filterpname" id="filterpname">
                                                <option value="">-- Product --</option>
                                                @foreach ($product as $no => $products)
                                                    <option value="{{$products->id}}">{{$products->name}}</option>
                                                
                                                 @endforeach
                                        
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                           <br>
                                            <button type="button" name="filter" id="filter" class="btn btn-primary">Filter</button>
                                            <button type="button"  id="reset" class="btn btn-primary">Reset</button>

                                        </div>
                                           
                                            
                                      
                                    </div>
                                    <br>
                                    <table id="datatable" class="table table-striped nowrap datatable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Product Name</th>
                                                <th>Unit</th>
                                                <th>Qty</th>
                                                <th>Action</th>
                                               
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

    

<script> 

$(document).ready(function(){
   // var table = $('#datatable-buttons').DataTable();
//datatable

getdata();

});

    function getdata(productid = ''){
       
        var table = $('#datatable').DataTable({
            destroy:true,
        "order": [[ 0, "desc" ]],
       
    processing: true,
    serverSide: true,
    ajax: 
    {
       url: "{{ route('getavlstock') }}",
       data:{productid:productid}
    },
    
    columns: [
        
    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: true, searchable: false },
        
        {data: 'pname', name: 'pname'},
        {data: 'uname', name: 'uname'},
        {data: 'tqty', name: 'tqty'},
        {
            data: 'action', name: 'action', 
            orderable: false, searchable: false
        },
    ],
    buttons: ['copy', 'print', 'pdf'],


    });
    }

    $('#filter').click(function(){
            var product = $('#filterpname').val();

            if(product != ''){
                $('#data-table').DataTable().destroy();
                getdata(product);
            }
        });

        $('#reset').click(function(){
            
                $('#data-table').DataTable().destroy();
                getdata();
        });

   
</script>
    @endsection
</x-app-layout>
