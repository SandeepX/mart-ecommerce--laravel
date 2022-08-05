@can('View Bank List')
    <li class="{{request()->routeIs('admin.banks.*') ? 'active' : ''}} treeview">
        <a href="{{route('admin.banks.index')}}">
            <i class="fa fa-home {{request()->routeIs('admin.banks.index') ? 'fa-spin' : ''}}"></i> <span>Banks</span>
            <span class="pull-right-container">
            </span>
        </a>
    </li>
@endcan
