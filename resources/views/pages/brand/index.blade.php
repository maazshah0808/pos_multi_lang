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
                        <h2 class="card-title mb-0">Brand</h2>
                    </div>

                    <button type="button" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#addmodel">
                        Add Brand
                    </button>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">

                                    <table id="datatable-buttons" class="table table-striped nowrap">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Brand Name</th>
                                                <th>Status</th>
                                                <th>Created By</th>
                                                <th>Created At</th>
                                                <th>Updated At</th>
                                                <th>Action</th>
                                               
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($brand as $no => $brands)
                                            <tr>
                                                <td>{{ ++$no }}</td>
                                                <td>{{ $brands->name }}</td>
                                                <td>{{$brands->status == "1" ? "Active" :"In-Active"}}</td>
                                                <td>{{ $brands->uname }}</td>
                                                <td>{{ date('d-m-Y', strtotime($brands->created_at)) }}</td>
                                                <td>{{ date('d-m-Y', strtotime($brands->updated_at)) }}</td>
                                                <td>
                                                    <button title="Edit" value="{{$brands->id}}" class="btn btn-primary waves-effect waves-light editbtn">Edit</button>
                                                
                                                </td>
                                                
                                                
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

    {{-- model window to add  --}}

    <div class="modal fade" id="addmodel" tabindex="-1" role="dialog" aria-labelledby="addmodel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Brand</h5>
                    <button type="button" class="close waves-effect waves-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form  method="POST" action="{{route('brand.store')}}">
                        @csrf
                        <div class="form-group">
                            <label class="control-label">Brand Name <span class="text-danger">*</span></label>
                            <div>
                                <input type="text" class="form-control input-lg" name="brand_name" required>
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
                    <h5 class="modal-title" id="exampleModalLabel">Edit Category</h5>
                    <button type="button" class="close waves-effect waves-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form  method="POST" action="{{url('brandupdate')}}">
                        @csrf
                        @method('PUT')
    
                        <input type="hidden" name="brandid" id="brandid">
                        <div class="form-group">
                            <label class="control-label">Category Name <span class="text-danger">*</span></label>
                            <div>
                                <input type="text" class="form-control input-lg" id="brand_name" name="brand_name" required>
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

    
<script>
    $(document).ready(function(){
  
      $(document).on("click", ".editbtn", function(){
  
            var brandid = $(this).val();
  
            $('#editmodel').modal('show');  
            
                    let url = "{{route('brand.edit',":id")}}";
                     url = url.replace(':id', brandid);
                    $.ajax({
                            type: "GET",
                            headers: {
                            'X-CSRF-Token': '{{ csrf_token() }}',
                        },
                        url: url,
                            success: function (response) {

                            $('#brandid').val(response.brand.id);
                            $('#brand_name').val(response.brand.name);
                            $('#status').val(response.brand.status);

                            }
                    });
            });      
    });
  
</script>
    @endsection
</x-app-layout>
