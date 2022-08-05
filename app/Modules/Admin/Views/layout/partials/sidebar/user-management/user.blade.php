@can('View Admin List')
    <li class=" {{request()->routeIs('admin.users.*')? 'active' : ''}} treeview">
        <a href="#">
            <i class="fa fa-cart-plus"></i>
            <span>Users Management</span>
            <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
        </a>
        <ul class="treeview-menu">

            <li class="{{request()->routeIs('admin.users.index') ? 'active' : ''}} treeview">
                <a href="{{route('admin.users.index')}}">
                    <i class="fa fa-home {{request()->routeIs('admin.users.index') ? 'fa-spin' : ''}}"></i>
                    <span>Users</span>
                    <span class="pull-right-container">
            </span>
                </a>
            </li>

        </ul>
    </li>
@endcan