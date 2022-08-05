@canany(['View Store Pre Orders In Pre Order','View List Of Store Pre Orders'])
<li class="
{{
   request()->routeIs('warehouse.warehouse-pre-orders.*')? 'active' : ''
}}
    treeview
">
    <a href="#">
        <i class="fa fa-file"></i>
        <span>Pre-Orders</span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        @can('View List Of WH Pre Orders')
        <li class="{{request()->routeIs('warehouse.warehouse-pre-orders.index') ? 'active' : ''}} treeview">
            <a href="{{route('warehouse.warehouse-pre-orders.index')}}">
                <i class="fa fa-file {{request()->routeIs('warehouse.warehouse-pre-orders.index') ? 'fa-spin' : ''}}"></i>
                <span>Pre-Orders</span>
                <span class="pull-right-container">
                </span>
            </a>
        </li>
        @endcan
        @can('View Store Pre Orders In Pre Order')
        <li class="{{request()->routeIs('warehouse.warehouse-pre-orders.stores') ? 'active' : ''}} treeview">
            <a href="{{route('warehouse.warehouse-pre-orders.stores')}}">
                <i class="fa fa-file {{request()->routeIs('warehouse.warehouse-pre-orders.stores') ? 'fa-spin' : ''}}"></i>
                <span>Store With Pre-Orders</span>
                <span class="pull-right-container">
                </span>
            </a>
        </li>
        @endcan
    </ul>
</li>
@endcanany
