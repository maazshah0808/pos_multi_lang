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
                        <h2 class="card-title mb-0">General Ledger</h2>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">

                                    <table id="datatable-buttons" class="table table-striped nowrap">
                                        <thead class="thead">
                                            <tr>
                                                <th>Stock</th>
                                                <th>Created At</th>
                                                <th>Vendor Name</th>
                                                <th>Detail</th>
                                                <th>Payment Type</th>
                                                <th>Credit</th>
                                                <th>Debit</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($ledger as $l)
                                            <tr>
                                                <td>{{$l->stock_id == NULL ? 'N/A' : '-'}}</td>
                                                <td>{{$l->created_at}}</td>
                                                <td>{{$l->vname}}</td>
                                                <td>{{$l->detail}}</td>
                                                <td>{{$l->payment_type == 0 ? "Cash" : "Bank"}}</td>
                                                <td>{{$l->amount >= 0 ? $l->amount : 0}}</td>
                                                <td>{{$l->amount < 0 ? $l->amount : 0}}</td>
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
