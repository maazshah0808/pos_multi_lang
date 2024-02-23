<x-app-layout>
@section('content')


<br><br><br><br>
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">Dashboard</h4>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-md-6 col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="mb-4">
                        <span class="badge badge-soft-primary float-right">Total</span>
                        <h5 class="card-title mb-0">Total Sales</h5>
                    </div>
                    <div class="row d-flex align-items-center mb-4">
                        <div class="col-8">
                            <h2 class="d-flex align-items-center mb-0">
                              RS {{$totalsale}}
                            </h2>
                        </div>
                        <div class="col-4 text-right">
                            {{-- <span class="text-muted">12.5% <i
                                    class="mdi mdi-arrow-up text-success"></i></span> --}}
                        </div>
                    </div>

                    <div class="progress shadow-sm" style="height: 5px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 57%;">
                        </div>
                    </div>
                </div>
                <!--end card body-->
            </div><!-- end card-->
        </div> <!-- end col-->

        <div class="col-md-6 col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="mb-4">
                        <span class="badge badge-soft-primary float-right">Total</span>
                        <h5 class="card-title mb-0">Sale Return</h5>
                    </div>
                    <div class="row d-flex align-items-center mb-4">
                        <div class="col-8">
                            <h2 class="d-flex align-items-center mb-0">
                               RS {{$return}}
                            </h2>
                        </div>
                        <div class="col-4 text-right">
                            {{-- <span class="text-muted">18.71% <i
                                    class="mdi mdi-arrow-down text-danger"></i></span> --}}
                        </div>
                    </div>

                    <div class="progress shadow-sm" style="height: 5px;">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: 57%;">
                        </div>
                    </div>
                </div>
                <!--end card body-->
            </div><!-- end card-->
        </div> <!-- end col-->

        <div class="col-md-6 col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="mb-4">
                        <span class="badge badge-soft-primary float-right">Total</span>
                        <h5 class="card-title mb-0">Sales Tax Reported To FBR </h5>
                    </div>
                    <div class="row d-flex align-items-center mb-4">
                        <div class="col-8">
                            <h2 class="d-flex align-items-center mb-0">
                                RS {{$fbrtax}}
                            </h2>
                        </div>
                        <div class="col-4 text-right">
                           
                        </div>
                    </div>

                    <div class="progress shadow-sm" style="height: 5px;">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: 57%;">
                        </div>
                    </div>
                </div>
                <!--end card body-->
            </div>
            <!--end card-->
        </div> <!-- end col-->
    </div>

    <div class="row">
        <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-4">
                            <h2 class="card-title mb-0">Latest Sale</h2>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <table id="datatable-buttons" class="table table-striped nowrap text-center">
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
                                                
                                                @foreach ($inv as $no => $invs)
                                                <tr>
                                                    <td>{{ "INV-".$invs->id }}</td>
                                                    <td>{{ date('d-m-Y', strtotime($invs->created_at)) }}</td>
                                                    <td>{{$invs->customer_name == NULL ? "Counter-Sale" :""}}</td>
                                                    <td>{{ $invs->invoice_amount }}</td>
                                                    <td>{{ $invs->tax }}</td>
                                                    <td>{{  $invs->tax * $invs->invoice_amount/100 }}</td> 
                                                    
                                                    <td>
                                                        <a href="{{route('getprint',$invs->id)}}" class="btn btn-primary btn-sm">Print</a>

                                                    
                                                    </td>
                                                </tr>
                                                @endforeach

                                            </tbody>
                                            
                                        </table>
                                        
                                    </div> <!-- end card body-->
                                </div> <!-- end card -->
                            </div><!-- end col-->
                            <div class="col-md-12 text-center">
                                <a href="{{route('getinvlist')}}"><button type="button" class="btn btn-primary">View All</button></a>
                            </div>
                        </div>

                    </div>
                </div>
        </div>
    </div>
    <!-- end row-->
</div> <!-- container-fluid -->

@endsection
</x-app-layout>
