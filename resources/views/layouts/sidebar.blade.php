 <!-- ========== Left Sidebar Start ========== -->
 <div class="vertical-menu">

    <div data-simplebar class="h-100">

        <div class="navbar-brand-box">
            <a href="{{route('dashboard')}}" class="logo">
                <i class="mdi mdi-album"></i>
                <span>
                    {{config('global.company.Name')}}
                </span>
            </a>
        </div>

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Menu</li>

                <li>
                    <a href="{{route('dashboard')}}" class="waves-effect"><i class="mdi mdi-home-analytics"></i><span>Dashboard</span></a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="mdi mdi-diamond-stone"></i><span>Product</span></a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{route('category.index')}}">Category</a></li>
                        <li><a href="{{route('brand.index')}}">Brand</a></li>
                        <li><a href="{{route('unit.index')}}">Units</a></li>
                        <li><a href="{{route('product.index')}}">Products</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="mdi mdi-seed"></i><span>Stock</span></a>
                    <ul class="sub-menu" aria-expanded="false">
                     
                        <li><a href="{{route('stock.index')}}">Purchase Stock</a></li>
                        <li><a href="{{route('getavlstock')}}">Available Stock</a></li>
                        <li><a href="{{route('getstocklist')}}">Stock</a></li>
                        
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="mdi mdi-seed"></i><span>Vendor</span></a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{route('vendor.index')}}">Vendor</a></li>
                       
                        <li><a href="{{route('GeneralLedger')}}">Vendor General Ledger</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="mdi mdi-seed"></i><span>Invoicing</span></a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{route('sales.index')}}">FBR POS</a></li>
                        <li><a href="{{route('getinvlist')}}">Invoice Listing</a></li>
                       
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="mdi mdi-seed"></i><span>Return</span></a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{route('getreturnlist')}}">Return listing</a></li>
                       
                    </ul>
                </li>

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->