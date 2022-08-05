@canany(['View List Of Wh Users'])
<li class=" {{request()->routeIs('warehouse.warehouse-users.*')? 'active' : ''}} treeview">
        <a href="#">
            <i class="fa fa-cart-plus"></i>
            <span>Users Management</span>
            <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
        </a>
        <ul class="treeview-menu">
            <li class="{{request()->routeIs('warehouse.warehouse-users.index') ? 'active' : ''}} treeview">
                <a href="{{route('warehouse.warehouse-users.index')}}">
                    <i class="fa fa-home {{request()->routeIs('warehouse.warehouse-users.index') ? 'fa-spin' : ''}}"></i>
                    <span>Users</span>
                    <span class="pull-right-container">
            </span>
                </a>
            </li>
        </ul>
    </li>
@endcanany
