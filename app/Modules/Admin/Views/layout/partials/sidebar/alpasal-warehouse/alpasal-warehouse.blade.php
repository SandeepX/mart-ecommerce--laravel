
<li class="{{request()->routeIs('admin.warehouses.*')
    || request()->routeIs('admin.demand-projection.*')
    || request()->routeIs('admin.admin.wh-rejected-item-reporting.*')
    || request()->routeIs('admin.wh-dispatch-report.*') ? 'active' : ''}}
    treeview"
>

@canany(
    ['View Warehouse List',
    'View Preorder Reporting',
    'View Warehouse Having Pre Orders',
    'View Demand Projection',
    'View Rejected Item Report'
    ])

<li class=" {{request()->routeIs('admin.warehouses.*') ? 'active' : ''}} treeview">

    <a href="#">
        <i class="fa fa-cart-plus"></i>
        <span>Warehouse Management</span>
        <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
    </a>
    <ul class="treeview-menu">

        @can('View Warehouse List')
            <li class="{{request()->routeIs('admin.warehouses.index') ? 'active' : ''}} treeview">
                <a href="{{route('admin.warehouses.index')}}">
                    <i class="fa fa-home {{request()->routeIs('admin.warehouses.index') ? 'fa-spin' : ''}}"></i>
                    <span>Alpasal Warehouses</span>
                    <span class="pull-right-container">
            </span>
                </a>
            </li>
        @endcan
        @can('View Warehouse Having Pre Orders')
            <li class="{{request()->routeIs('admin.warehouse-pre-orders.index') ? 'active' : ''}} treeview">
                <a href="{{route('admin.warehouse-pre-orders.index')}}">
                    <i class="fa fa-home {{request()->routeIs('admin.warehouse-pre-orders.index') ? 'fa-spin' : ''}}"></i>
                    <span>Warehouse Pre Orders</span>
                    <span class="pull-right-container">
            </span>
                </a>
            </li>
            @endcan
            @can('View Preorder Reporting')
            <li class="{{request()->routeIs('admin.pre-orders-reporting.getPreordersReporting') ? 'active' : ''}} treeview">
                <a href="{{route('admin.pre-orders-reporting.getPreordersReporting')}}">
                    <i class="fa fa-home {{request()->routeIs('admin.pre-orders-reporting.getPreordersReporting') ? 'fa-spin' : ''}}"></i>
                    <span>Pre Orders Report</span>
                    <span class="pull-right-container">
            </span>
                </a>
            </li>
            @endcan
            @can('View Admin Stock Transfer')
            <li class="{{request()->routeIs('admin.warehouses.stock-transfer.form') ? 'active' : ''}} treeview">
                    <a href="{{route('admin.warehouses.stock-transfer.form')}}">
                        <i class="fa fa-home {{request()->routeIs('admin.warehouses.stock-transfer.form') ? 'fa-spin' : ''}}"></i>
                        <span>Stock Transfer</span>
                        <span class="pull-right-container"></span>
                    </a>
                </li>
            @endcan

{{--            <li class="{{request()->routeIs('admin.wh-order-report.index') ? 'active' : ''}} treeview">--}}
{{--                <a href="{{route('admin.wh-order-report.index')}}">--}}
{{--                    <i class="fa fa-home {{request()->routeIs('admin.wh-order-report.index') ? 'fa-spin' : ''}}"></i>--}}
{{--                    <span>Order Report</span>--}}
{{--                    <span class="pull-right-container"></span>--}}
{{--                </a>--}}
{{--            </li>--}}

            <li class="{{request()->routeIs('admin.reporting.getReportingData') ? 'active' : ''}} treeview">
                <a href="{{route('admin.reporting.getReportingData')}}">
                    <i class="fa fa-home {{request()->routeIs('admin.reporting.getReportingData') ? 'fa-spin' : ''}}"></i>
                    <span>Reporting</span>
                    <span class="pull-right-container"></span>
                </a>

            </li>


            @can('View Demand Projection')
                <li class="{{request()->routeIs('admin.demand-projection.*') ? 'active' : ''}} treeview">
                    <a href="{{route('admin.demand-projection.index')}}">
                        <i class="fa fa-home {{request()->routeIs('admin.demand-projection.index') ? 'fa-spin' : ''}}"></i>
                        <span>Demand Projection</span>
                        <span class="pull-right-container"></span>
                    </a>
                </li>
            @endcan



            <li class="{{request()->routeIs('admin.wh-stock-report.index') ? 'active' : ''}} treeview">
                <a href="{{route('admin.wh-stock-report.index')}}">
                    <i class="fa fa-home {{request()->routeIs('admin.wh-stock-report.index') ? 'fa-spin' : ''}}"></i>
                    <span>Stock Statement</span>
                    <span class="pull-right-container"></span>
                </a>
            </li>

           @can('View Rejected Item Report')
                <li class="{{request()->routeIs('admin.wh-rejected-item-reporting.index') ? 'active' : ''}} treeview">
                    <a href="{{route('admin.wh-rejected-item-reporting.index')}}">
                        <i class="fa fa-home {{request()->routeIs('admin.wh-rejected-item-reporting.index') ? 'fa-spin' : ''}}"></i>
                        <span>Rejected Items Report</span>
                        <span class="pull-right-container"></span>
                    </a>
                </li>
           @endcan

            <li class="{{request()->routeIs('admin.wh-dispatch-report.index') ? 'active' : ''}} treeview">
                <a href="{{route('admin.wh-dispatch-report.index')}}">
                    <i class="fa fa-home {{request()->routeIs('admin.wh-dispatch-report.index') ? 'fa-spin' : ''}}"></i>
                    <span>Dispatch Report</span>
                    <span class="pull-right-container"></span>
                </a>
            </li>


       {{-- <li class="{{request()->routeIs('admin.warehouse-purchase-orders.index') ? 'active' : ''}} treeview">
            <a href="{{route('admin.warehouse-purchase-orders.index')}}">
                <i class="fa fa-file {{request()->routeIs('new-warehouse-purchase-orders') ? 'fa-spin' : ''}}"></i>
                <span>Warehouse Purchase Orders</span>
                <span class="pull-right-container">
                 </span>
            </a>
        </li>--}}

    </ul>
</li>

@endcanany


