@can('View SMS List')
    <li class="{{request()->routeIs('admin.sms.*') ? 'active' : ''}} treeview">
        <a href="{{route('admin.sms.index')}}">
            <i class="fa fa-home {{request()->routeIs('admin.sms.index') ? 'fa-spin' : ''}}"></i> <span>SMS</span>
            <span class="pull-right-container">
            </span>
        </a>
    </li>
@endcan

