@can('View Role List')
    <li class="{{request()->is('admin/roles/*')? "active" : ""}} treeview">
        <a href="#">
            <i class="fa fa-file"></i>
            <span>Roles</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
        </a>
        <ul class="treeview-menu">
            <li class="{{request()->routeIs('admin.roles.index') ? 'active' : ''}} treeview">
                <a href="{{route('admin.roles.index')}}">
                    <i class="fa fa-file {{request()->routeIs('admin.roles.index') ? 'fa-spin' : ''}}"></i>
                    <span>Manage Roles</span>
                    <span class="pull-right-container">
                </span>
                </a>
            </li>

        </ul>
    </li>
@endcan
