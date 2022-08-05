<li class=" {{request()->routeIs('admin.warehouse-purchase-orders.index') ||
request()->routeIs('admin.store.orders.index') ? 'active' : ''}} treeview">
    <a href="#">
        <i class="fa fa-bar-chart"></i>
        <span>Reports</span>
        <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
    </a>
    <ul class="treeview-menu">

        <li class="{{request()->is('admin/warehouse-purchase-orders/*')? "active" : ""}} treeview">
            <a href="{{route('admin.warehouse-purchase-orders.index')}}">
                <i class="fa fa-file {{request()->is('admin/warehouse-purchase-orders/*') ? 'fa-spin' : ''}}"></i>
                <span>Purchase</span>
                <span class="pull-right-container">
                </span>
            </a>
        </li>

        <li class="{{request()->is('admin/store/orders')? "active" : ""}} treeview">
            <a href="{{route('admin.store.orders.index')}}">
                <i class="fa fa-file {{request()->is('admin/store/orders') ? 'fa-spin' : ''}}"></i>
                <span>Sales</span>
                <span class="pull-right-container">
                </span>
            </a>
        </li>
    </ul>
</li>

