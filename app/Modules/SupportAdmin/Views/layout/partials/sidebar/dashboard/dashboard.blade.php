{{--@can(['View Support Admin Dashboard'])--}}
<li class="{{request()->routeIs('support-admin.dashboard') ? 'active' : ''}} treeview">
    <a href="{{route('support-admin.dashboard')}}">
        <i class="fa fa-home {{request()->routeIs('support-admin.dashboard') ? 'fa-spin' : ''}}"></i> <span>Dashboard</span>
        <span class="pull-right-container">
            </span>
    </a>
</li>
{{--@endcan--}}
