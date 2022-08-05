@can('View Global Notification List')
    <li class="{{request()->routeIs('admin.notification.*') ? 'active' : ''}} treeview">
        <a href="{{route('admin.notification.index')}}">
            <i class="fa fa-home {{request()->routeIs('admin.notification.index') ? 'fa-spin' : ''}}"></i> <span>Global Notification</span>
            <span class="pull-right-container">
            </span>
        </a>
    </li>
@endcan
