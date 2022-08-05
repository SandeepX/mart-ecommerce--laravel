@if(auth()->user()->isWarehouseAdminOrUser())
    @canany(['View List Of WH Purchase Orders'])
    <li class=" {{request()->routeIs('warehouse.warehouse-purchase-orders.*')? 'active' : ''}} treeview">
        <a href="#">
            <i class="fa fa-cart-plus"></i>
            <span>Purchase Management</span>
            <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
        </a>
        <ul class="treeview-menu">
            @can('View List Of WH Purchase Orders')
            <li class="{{request()->routeIs('warehouse.warehouse-purchase-orders.index') ? 'active' : ''}} treeview">
                <a href="{{route('warehouse.warehouse-purchase-orders.index')}}">
                    <i class="fa fa-file {{request()->routeIs('warehouse.warehouse-purchase-orders.index') ? 'fa-spin' : ''}}"></i>
                    <span>Warehouse Purchase Orders</span>
                    <span class="pull-right-container">
                 </span>
                </a>
            </li>
            @endcan
        </ul>
    </li>
    @endcanany
@endif

