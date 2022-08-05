@if(auth()->user()->isWarehouseAdminOrUser())
    <li class=" {{request()->routeIs('warehouse.warehouse-purchase-orders.list.*')? 'active' : ''}} treeview">
        <a href="#">
            <i class="fa fa-cart-plus"></i>
            <span>Order Management</span>
            <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
        </a>
        <ul class="treeview-menu">

            <li class="{{request()->routeIs('warehouse.warehouse-purchase-orders.list') ? 'active' : ''}} treeview">
                <a href="{{route('warehouse.warehouse-purchase-orders.list')}}">
                    <i class="fa fa-file {{request()->routeIs('warehouse.warehouse-purchase-orders.list') ? 'fa-spin' : ''}}"></i>
                    <span>Warehouse Purchase Order List</span>
                    <span class="pull-right-container">
                 </span>
                </a>
            </li>

        </ul>
    </li>
@endif

