
         <!-- Plugins css -->
         <link href="{{ asset('plugins/datatables/dataTables.bootstrap4.css')}}" rel="stylesheet" type="text/css" />
         <link href="{{ asset('plugins/datatables/responsive.bootstrap4.css')}}" rel="stylesheet" type="text/css" />
         <link href="{{ asset('/plugins/datatables/buttons.bootstrap4.css')}}" rel="stylesheet" type="text/css" />
         <link href="{{ asset('/plugins/datatables/select.bootstrap4.css')}}" rel="stylesheet" type="text/css" />

           <!-- App css -->
        <link href="{{ asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/css/theme.min.css')}}" rel="stylesheet" type="text/css" />

        <script src="{{ asset('js/jquery.min.js')}}"></script>

<body>
    
		
    <!-- Begin page -->
    <div id="layout-wrapper">
        <div class="header-border"></div>

        @include('layouts.topbar');
        @include('layouts.sidebar');
            <div class="main-content">
            
                @yield('content');
                
                
            </div>


{{-- 
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                           {{date('y-m-d')}}
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-right d-none d-sm-block">
                                Design & Develop by <a href="https://evolvingtech.pk/">EvolvingTech.pk</a>
                            </div>
                        </div>
                    </div>
                </div>
            </footer> --}}

        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

    <!-- Overlay-->
    <div class="menu-overlay"></div>


    <!-- jQuery  -->
    <script src="{{ asset('assets/js/jquery.min.js')}}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{ asset('assets/js/metismenu.min.js')}}"></script>
    <script src="{{ asset('assets/js/waves.js')}}"></script>
    <script src="{{ asset('assets/js/simplebar.min.js')}}"></script>


    <!-- third party js -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap4.js')}}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.responsive.min.js')}}"></script>
    <script src="{{ asset('plugins/datatables/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.buttons.min.js')}}"></script>
    <script src="{{ asset('plugins/datatables/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('plugins/datatables/buttons.html5.min.js')}}"></script>
    <script src="{{ asset('plugins/datatables/buttons.flash.min.js')}}"></script>
    <script src="{{ asset('plugins/datatables/buttons.print.min.js')}}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.keyTable.min.js')}}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.select.min.js')}}"></script>
    <script src="{{ asset('plugins/datatables/pdfmake.min.js')}}"></script>
    <script src="{{ asset('plugins/datatables/vfs_fonts.js')}}"></script>
    <!-- third party js ends -->

    <!-- Datatables init -->
    <script src="{{ asset('assets/pages/datatables-demo.js')}}"></script>

    <!-- Sparkline Js-->
    <script src="{{ asset('plugins/jquery-sparkline/jquery.sparkline.min.js')}}"></script>

    <!-- Chart Js-->
    <script src="{{ asset('plugins/jquery-knob/jquery.knob.min.js')}}"></script>

    <!-- Chart Custom Js-->
    <script src="{{ asset('assets/pages/knob-chart-demo.js')}}"></script>


    <!-- Morris Js-->
    <script src="{{ asset('plugins/morris-js/morris.min.js')}}"></script>

    <!-- Raphael Js-->
    <script src="{{ asset('plugins/raphael/raphael.min.js')}}"></script>

    <!-- Custom Js -->
    <script src="{{ asset('assets/pages/dashboard-demo.js')}}"></script>

    <!-- App js -->
    <script src="{{ asset('assets/js/theme.js')}}"></script>

</body>


</html>

