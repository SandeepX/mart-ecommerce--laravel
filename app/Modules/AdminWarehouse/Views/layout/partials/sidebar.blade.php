<aside class="main-sidebar" >
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar"  id="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{url('admin/images/user.png')}}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ isset(Auth::user()->name) ? Auth::user()->name : '' }}</p>
            </div>
        </div>

        <ul class="sidebar-menu">

            <li class="header" style="color: white;">MAIN NAVIGATION</li>


            @include('AdminWarehouse::layout.partials.sidebar.dashboard.dashboard')
            @include('AdminWarehouse::layout.partials.sidebar.dispatch-routes.main')
          {{--  @include('Admin::layout.partials.sidebar.user-management.user')--}}
            @include('AdminWarehouse::layout.partials.sidebar.alpasal-warehouse.alpasal-warehouse')
            @include('AdminWarehouse::layout.partials.sidebar.user-management.user')
            @include('AdminWarehouse::layout.partials.sidebar.products.main')
            @include('AdminWarehouse::layout.partials.sidebar.store.main')
            @include('AdminWarehouse::layout.partials.sidebar.warehouse-pre-order.main')
{{--            @include('AdminWarehouse::layout.partials.sidebar.purchase-order-list.purchase-order-list')--}}
            @include('AdminWarehouse::layout.partials.sidebar.wh-product-collection.wh-product-collection')
            @include('AdminWarehouse::layout.partials.sidebar.stock_transfer.stock_transfer')
            @include('AdminWarehouse::layout.partials.sidebar.bill-management.bill')
            @include('AdminWarehouse::layout.partials.sidebar.settings.settings')

           {{-- @include('Admin::layout.partials.sidebar.ecommerce.main')--}}


        </ul>

    </section>
    <!-- /.sidebar -->
</aside>
