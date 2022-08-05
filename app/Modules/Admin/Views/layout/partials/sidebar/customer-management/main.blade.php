
@canany(['View Customer Lists'])
    <li class=" {{request()->routeIs('admin.b2c-user.*') ? 'active' : ''}} treeview">
        <a href="#">
            <i class="fa fa-cart-plus"></i>
            <span>Customer Management </span>
            <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
        </a>

        <ul class="treeview-menu">
            @can('View Customer Lists')
                <li class="{{request()->routeIs('admin.b2c-user.index') ? 'active' : ''}} treeview">
                    <a href="{{route('admin.b2c-user.index')}}">
                        <i class="fa fa-home {{request()->routeIs('admin.b2c-user.index') ? 'fa-spin' : ''}}"></i> <span>Customers</span>
                        <span class="pull-right-container">
                        </span>
                    </a>
                </li>
            @endcan
        </ul>
    </li>

@endcanany


