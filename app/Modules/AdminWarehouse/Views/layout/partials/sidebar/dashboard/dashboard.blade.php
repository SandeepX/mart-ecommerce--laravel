@can(['View Warehouse Dashboard'])
<li class="{{request()->routeIs('warehouse.dashboard') ? 'active' : ''}} treeview">
    <a href="{{route('warehouse.dashboard')}}">
        <i class="fa fa-home {{request()->routeIs('warehouse.dashboard') ? 'fa-spin' : ''}}"></i> <span>Dashboard</span>
        <span class="pull-right-container">
            </span>
    </a>
</li>
@endcan
