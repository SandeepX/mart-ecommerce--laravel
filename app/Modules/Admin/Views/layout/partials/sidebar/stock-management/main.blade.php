@canany(['View Warehouse Wise Current Stock List'])
    <li class=" {{request()->routeIs('admin.warehouse-wise.current-stock.*') ? 'active' : ''}} treeview">
        <a href="#">
            <i class="fa fa-cart-plus"></i>
            <span>Stock Management </span>
            <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
        </a>

        <ul class="treeview-menu">
            @can('View Warehouse Wise Current Stock List')
                <li class="{{request()->routeIs('admin.warehouse-wise.current-stock.index') ? 'active' : ''}} treeview">
                    <a href="{{route('admin.warehouse-wise.current-stock.index')}}">
                        <i class="fa fa-home {{request()->routeIs('admin.warehouse-wise.current-stock.index') ? 'fa-spin' : ''}}"></i> <span>View Current Stock</span>
                        <span class="pull-right-container">
                        </span>
                    </a>
                </li>
            @endcan
        </ul>
    </li>
@endcanany


