@can('View List Of Wh Products')
<li class=" {{request()->routeIs('warehouse.warehouse-products.*')? 'active' : ''}} treeview">
    <a href="#">
        <i class="fa fa-cart-plus"></i>
        <span>Product Management</span>
        <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
    </a>
    <ul class="treeview-menu">

        <li class="{{request()->routeIs('warehouse.warehouse-products.index') ? 'active' : ''}} treeview">
            <a href="{{route('warehouse.warehouse-products.index')}}">
                <i class="fa fa-file {{request()->routeIs('warehouse.warehouse-products.index') ? 'fa-spin' : ''}}"></i>
                <span>All Products</span>
                <span class="pull-right-container">
                 </span>
            </a>
        </li>

    </ul>
</li>
@endcan

