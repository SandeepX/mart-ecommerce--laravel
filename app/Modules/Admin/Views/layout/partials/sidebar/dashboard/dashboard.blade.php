<li class="{{request()->routeIs('admin.dashboard') ? 'active' : ''}} treeview">
    <a href="{{route('admin.dashboard')}}">
        <i class="fa fa-home {{request()->routeIs('admin.dashboard') ? 'fa-spin' : ''}}"></i> <span>Dashboard</span>
        <span class="pull-right-container">
            </span>
    </a>
</li>