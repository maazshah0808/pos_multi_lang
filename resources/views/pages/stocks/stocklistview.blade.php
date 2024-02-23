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
                        <h2 class="card-title mb-0">Stock View</h2>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">

                                    <table id="datatable-buttons" class="table table-striped nowrap">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Product</th>
                                                <th>Unit</th>
                                                <th>Total Qty</th>
                                                <th>Recieved Quantity</th>
                                                <th>Purchase Price</th>
                                                <th>Sale Price</th>
                                                <th>Created By</th>
                                                <th>Created At</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($stockdetail as $no => $stockdetails)
                                            <tr>
                                                <td>{{ ++$no }}</td>
                                                <td>{{ $stockdetails->pname }}</td>
                                                <td>{{ $stockdetails->uname }}</td>
                                                <td>{{ $stockdetails->qty }}</td>
                                                <td>{{ $stockdetails->received_qty }}</td>
                                                <td>{{ $stockdetails->p_price }}</td>
                                                <td>{{ $stockdetails->s_price }}</td>
                                                <td>{{ $stockdetails->username }}</td>
                                                <td>{{ date('d-m-Y', strtotime($stockdetails->created_at)) }}</td>
                                           
                                                
                                            </tr>
                                            @endforeach
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

   



    @endsection
</x-app-layout>
