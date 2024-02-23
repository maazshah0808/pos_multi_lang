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
                        <h2 class="card-title mb-0">Vendor</h2>
                    </div>

                    <button type="button" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#addmodel">
                        Add Vendor
                    </button>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body table-responsive">

                                    <table id="datatable-buttons" class="table table-striped nowrap">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Vendor Name</th>
                                                <th>Phone</th>
                                                <th>Address</th>
                                                <th>Balance</th>
                                                <th>Status</th>
                                                <th>Created At</th>
                                                <th>Updated At</th>
                                                <th>Action</th>
                                               
                                            </tr>
                                        </thead>
                                        <tbody>
                                        
                                            @for($i=0;$i<count($data_arr);$i++)
                                            <tr>
                                                <td>{{ $i+1 }}</td>
                                                <td>{{ $data_arr[$i]["vendor"]->name }}</td>
                                                <td>{{ $data_arr[$i]["vendor"]->phone }}</td>
                                                <td>{{ $data_arr[$i]["vendor"]->address }}</td>
                                                <td>{{$data_arr[$i]["balance"]}}</td>
                                                <td>{{$data_arr[$i]["vendor"]->status == "1" ? "Active" :"In-Active"}}</td>
                                                <td>{{ date('d-m-Y', strtotime($data_arr[0]["vendor"]->created_at)) }}</td>
                                                <td>{{ date('d-m-Y', strtotime($data_arr[0]["vendor"]->updated_at)) }}</td>
                                                <td>
                                                    <button title="Edit" value="{{$data_arr[$i]["vendor"]->id}}" class="btn btn-primary btn-sm waves-effect waves-light editbtn">Edit</button>
                                                    <a href="{{route('getLedgerList')}}?v_id={{$data_arr[$i]["vendor"]->id}}"><button title="View Ledger" class="btn btn-primary btn-sm waves-effect waves-light">Ledger</button></a>
                                                    <button title="Edit" value="{{$data_arr[$i]["vendor"]->id}}" class="btn btn-primary btn-sm waves-effect waves-light pbtn">Payment</button>
                                                </td>
                                                
                                                
                                            </tr>
                                            @endfor
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

    <div class="modal fade" id="addmodel" tabindex="-1" role="dialog" aria-labelledby="addmodel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Vendor</h5>
                    <button type="button" class="close waves-effect waves-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form  method="POST" action="{{route('vendor.store')}}">
                        @csrf
                        <div class="form-group">
                            <label class="control-label">Vendor Name <span class="text-danger">*</span></label>
                            <div>
                                <input type="text" class="form-control input-lg" name="vendorname" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Vendor Phone <span class="text-danger">*</span></label>
                            <div>
                                <input type="number" class="form-control input-lg" name="phone" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Vendor Address <span class="text-danger">*</span></label>
                            <div>
                                <input type="text" class="form-control input-lg" name="address" required>
                            </div>
                        </div>
                   
                    <br>
                    <div class="form-group">
                        <div>
                            <button class="btn btn-primary w-24">Save</button>

                            <button type="button" class="btn btn-secondary waves-effect waves-light" data-dismiss="modal">Close</button>

                        </div>
                    </div>
                </form>


                </div>
            </div>
        </div>
    </div>
    {{-- add model window  --}}



    {{-- model window to Edit  --}}

    <div class="modal fade" id="editmodel" tabindex="-1" role="dialog" aria-labelledby="addmodel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Vendor</h5>
                    <button type="button" class="close waves-effect waves-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form  method="POST" action="{{url('vendorupdate')}}">
                        @csrf
                        @method('PUT')
    
                        <input type="hidden" name="vendorid" id="vendorid">
                        <div class="form-group">
                            <label class="control-label">Vendor Name <span class="text-danger">*</span></label>
                            <div>
                                <input type="text" class="form-control input-lg" id="editvendorname" name="editvendorname" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Vendor Phone <span class="text-danger">*</span></label>
                            <div>
                                <input type="number" class="form-control input-lg" id="phone" name="phone" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Vendor Address <span class="text-danger">*</span></label>
                            <div>
                                <input type="text" class="form-control input-lg" id="address" name="address" required>
                            </div>
                        </div>
                   
                        <div class="form-group">
                            <label class="control-label">Status <span class="text-danger">*</span></label>
                            <div>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="1">Active</option>
                                    <option value="0">In-Active</option>
                                    </select>
                            </div>
                        </div>
                   
                    <br>
                    <div class="form-group">
                        <div>
                            <button class="btn btn-primary w-24">Update</button>
                            <button type="button" class="btn btn-secondary waves-effect waves-light" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>

                </div>
            </div>
        </div>
    </div>
    {{-- add model window  --}}


     {{-- Payment model window to add  --}}

     <div class="modal fade" id="paymentmodel" tabindex="-1" role="dialog" aria-labelledby="addmodel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Payment</h5>
                    <button type="button" class="close waves-effect waves-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form  method="POST" action="{{url('savepayment')}}">
                        
                        @csrf
                        <input type="hidden" id="vid" name="vendorid" value="">

                        <div class="form-group">
                            <label class="control-label">Payment <span class="text-danger">*</span></label>
                            <div>
                               
                                <input type="number" min="0" class="form-control input-lg" step="0.01" name="vendoramt" oninput="validity.valid||(value='');" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label"> Payment Mode<span class="text-danger">*</span></label>

                            <div>
                                
                                    <select name="paymenttype" id="paymenttype" class="form-control">
                                        <option value="">-- Payment Mode -- </option>
                                        <option value="0">Cash</option>
                                        <option value="1">Bank</option>

                                    </select>
                                </div>
                        </div>

                   
                    <br>
                    <div class="form-group">
                        <div>
                            <button class="btn btn-primary w-24">Save</button>

                            <button type="button" class="btn btn-secondary waves-effect waves-light" data-dismiss="modal">Close</button>

                        </div>
                    </div>
                </form>


                </div>
            </div>
        </div>
    </div>
    {{--Payment add model window  --}}

    
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
  

    $(document).on("click", ".pbtn", function(){
  
  var unitid = $(this).val();

  $('#paymentmodel').modal('show');  
  
          let url = "{{route('vendor.edit',":id")}}";
           url = url.replace(':id', unitid);
          $.ajax({
                  type: "GET",
                  headers: {
                  'X-CSRF-Token': '{{ csrf_token() }}',
              },
              url: url,
                  success: function (response) {

                  $('#vid').val(response.vendor.id);
                  }
          });
  });      

</script>
    @endsection
</x-app-layout>
