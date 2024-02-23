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
                        <h2 class="card-title mb-0">سامان</h2>
                    </div>

                    <button type="button" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#addmodel">
                        سامان کا اندراج
                    </button>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body table-responsive">

                                    <table id="" class="table table-striped nowrap data-table">
                                        <thead>
                                            <tr>
                                                <th>سامان کا نمبر</th>
                                                <th>سامان کا نام</th>
                                                {{-- <th>Unit</th> --}}
                                                {{-- <th>Brand</th>
                                                <th>Category</th>
                                                <th>Status</th>
                                                <th>Created By</th> --}}
                                                {{-- <th>Created At</th>
                                                <th>Updated At</th>  --}}
                                                <th>فی کارٹن تعداد</th>
                                                <th>قیمت خرید</th>
                                                <th>قیمت فروخت</th>
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

    {{-- model window to add  --}}

    <div class="modal fade" id="addmodel" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addmodel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">سامان</h5>
                    <button type="button" class="close waves-effect waves-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form  method="POST" action="{{route('product.store')}}">
                        @csrf

                       <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">سامان کا نام <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control input-lg" name="productname" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">فی کارٹن تعداد<span class="text-danger">*</span></label>
                                    <input value="0" type="text" class="form-control input-lg" name="productname" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">قیمت خرید<span class="text-danger">*</span></label>
                                    <input value="0" type="text" class="form-control input-lg" name="productname" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">قیمت فروخت<span class="text-danger">*</span></label>
                                    <input value="0" type="text" class="form-control input-lg" name="productname" required>
                                </div>
                            </div>
                            {{-- <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Brand <span class="text-danger">*</span></label>
                                    <select  class="form-control" name="brand" required>
                                        <option value="">-- Select Brand</option>
                                        @foreach ($brand as $no => $brands)
                                        <option value="{{$brands->id}}">{{$brands->name}}</option>
                                            
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Category <span class="text-danger">*</span></label>
                                    <select  class="form-control" name="category" required>
                                        <option value="">-- Select Category</option>
                                        @foreach ($category as $no => $categorys)
                                        <option value="{{$categorys->id}}">{{$categorys->name}}</option>
                                            
                                        @endforeach
                                    </select>
                                </div>
                            </div> --}}
                       </div>
                   
                    <br>
                    <div class="form-group">
                        <div>
                            <button class="btn btn-primary w-24">محفوظ کریں</button>

                            <button type="button" class="btn btn-secondary waves-effect waves-light" data-dismiss="modal">بند کریں</button>

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
                    <h5 class="modal-title" id="exampleModalLabel">Edit Product</h5>
                    <button type="button" class="close waves-effect waves-light" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form  method="POST" action="{{url('produtupdate')}}">
                        @csrf
                        @method('PUT')
    
                        <input type="hidden" name="productid" id="productid">
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Product Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control input-lg" id="productname" name="productname" required>
                                </div>
                            </div>
                           
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Brand <span class="text-danger">*</span></label>
                                    <select  class="form-control" id="brand" name="brand" required>
                                        <option value="">-- Select Brand</option>
                                        @foreach ($brand as $no => $brands)
                                        <option value="{{$brands->id}}">{{$brands->name}}</option>
                                            
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Category <span class="text-danger">*</span></label>
                                    <select  class="form-control" id="category" name="category" required>
                                        <option value="">-- Select Category</option>
                                        @foreach ($category as $no => $categorys)
                                        <option value="{{$categorys->id}}">{{$categorys->name}}</option>
                                            
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Status <span class="text-danger">*</span></label>
                                    <select  class="form-control" id="status" name="status" required>
                                        <option value="1">Active</option>
                                        <option value="">In-Active</option>
                                    </select>
                                </div>
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

        //datatable
       
        var i = 1;
        $('.data-table').DataTable({
            "order": [[ 0, "desc" ]],
        processing: true,
        serverSide: true,
        ajax: "{{ route('product.index') }}",
        columns: [

            
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: true, searchable: false },
               
            {data: 'name', name: 'name'},
           
            {data: 'bname', name: 'bname'},
            {data: 'cname', name: 'cname'},
            {data: 'status', name: 'status'},
            {data: 'uname', name: 'uname'},
            {
                data: 'action', name: 'action', 
                orderable: false, searchable: false
            },
        ]


    });

    
        //edit model window data 
      $(document).on("click", ".editbtn", function(){
  
            var productid = $(this).val();
  
            $('#editmodel').modal('show');  
            
                    let url = "{{route('product.edit',":id")}}";
                     url = url.replace(':id', productid);
                    $.ajax({
                            type: "GET",
                            headers: {
                            'X-CSRF-Token': '{{ csrf_token() }}',
                        },
                        url: url,
                            success: function (response) {

                                $('#productid').val(response.product.id);
                                $('#productname').val(response.product.name);
                                $('#brand').val(response.product.brand_id);
                                $('#category').val(response.product.category_id);
                                $('#status').val(response.product.status);

                            }
                    });
            });      
    });
  
</script>
    @endsection
</x-app-layout>
