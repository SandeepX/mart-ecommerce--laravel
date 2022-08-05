@canany(['View List of WH Purchase Orders'])
<li class="
{{
   request()->routeIs('new-warehouse-purchase-orders')
? 'active' : ''
}}
 treeview
 ">
    <a href="#">
        <i class="fa fa-file"></i>
        <span>Orders</span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        <li class="{{request()->routeIs('admin.warehouse-purchase-orders.index') ? 'active' : ''}} treeview">
            <a href="{{route('admin.warehouse-purchase-orders.index')}}">
                <i class="fa fa-file {{request()->routeIs('admin.warehouse-purchase-orders.index') ? 'fa-spin' : ''}}"></i>
                <span>Warehouse Purchase Order</span>
                <span class="pull-right-container">
                </span>
            </a>
        </li>
    </ul>
</li>
@endcanany
