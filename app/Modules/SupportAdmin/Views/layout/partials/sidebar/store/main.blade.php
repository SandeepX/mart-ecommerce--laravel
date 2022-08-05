{{--@can('View Store Detail')--}}
    <li class="{{request()->routeIs('support-admin.store.*') ? 'active' : ''}} treeview">
        <a href="{{route('support-admin.store.index')}}">
            <i class="fa fa-home {{request()->routeIs('support-admin.store.index') ? 'fa-spin' : ''}}"></i> <span>Store </span>
            <span class="pull-right-container">
            </span>
        </a>
    </li>
{{--@endcan--}}
