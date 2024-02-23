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
                    <table class="table table-striped table-bordered">
                        <thead class="thead">
                            <tr>
                                <th>Vendor Name</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Closing Balance</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{$vendor->name}}</td>
                            <td>{{$vendor->contact}}</td>
                            <td>{{$vendor->address}}</td>
                            <td class="{{$closing_balance >= 0 ? "text-success" : "text-danger"}}"><b>{{$closing_balance}}</b></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h2 class="card-title mb-0">Vendor</h2>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">

                                    <table id="datatable-buttons" class="table table-striped nowrap">
                                        <thead class="thead">
                                            <tr>
                                                <th>Created At</th>
                                                <th>Stock ID</th>
                                                <th>Detail</th>
                                                <th>Payment Type</th>
                                                <th>Credit</th>
                                                <th>Debit</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                         
                                        @foreach($ledger as $l)
                                
                                        <tr>
                                        <td>{{$l->created_at}}</td>
                                      
                                        @if ($l->stock_id == null)
                                          <td>N/A</td>  
                                        @else
                                            <td><a href="{{route('getsview',$l->stock_id)}}">{{$l->stock_id}}</a></td>
                                        @endif
                                       
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
  
<script>
    $(document).ready(function(){
  
      $(document).on("click", ".editbtn", function(){
  
            var unitid = $(this).val();
  
            $('#editmodel').modal('show');  
            
                    let url = "{{route('vendor.edit',":id")}}";
                     url = url.replace(':id', unitid);
                    $.ajax({
                            type: "GET",
                            headers: {
                            'X-CSRF-Token': '{{ csrf_token() }}',
                        },
                        url: url,
                            success: function (response) {

                            $('#vendorid').val(response.vendor.id);
                            $('#editvendorname').val(response.vendor.name);
                            $('#phone').val(response.vendor.phone);
                            $('#address').val(response.vendor.address);
                            $('#status').val(response.vendor.status);

                            }
                    });
            });      
    });
  
</script>
    @endsection
</x-app-layout>
