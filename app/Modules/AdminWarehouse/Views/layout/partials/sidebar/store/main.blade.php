@canany(['View WH Store Connection','View WH Store Order List'])
<li class="
{{
   request()->routeIs('warehouse.store.orders.*')
   ||
   request()->routeIs('warehouse.store.connections.*')
? 'active' : ''
}}
    treeview
">
    <a href="#">
        <i class="fa fa-file"></i>
        <span>WH - Store Section</span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        @can('View WH Store Order List')
        <li class="{{request()->routeIs('warehouse.store.orders.index') ? 'active' : ''}} treeview">
            <a href="{{route('warehouse.store.orders.index')}}">
                <i class="fa fa-file {{request()->routeIs('warehouse.store.orders.index') ? 'fa-spin' : ''}}"></i>
                <span>Store Orders</span>
                <span class="pull-right-container">
                </span>
            </a>
        </li>
        @endcan
        @can('View WH Store Connection')
        <li class="{{request()->routeIs('warehouse.store.connections') ? 'active' : ''}} treeview">
            <a href="{{route('warehouse.store.connections')}}">
                <i class="fa fa-file {{request()->routeIs('warehouse.store.connections') ? 'fa-spin' : ''}}"></i>
                <span>Store Connections</span>
                <span class="pull-right-container">
                </span>
            </a>
        </li>
        @endcan
    </ul>
</li>
@endcanany
