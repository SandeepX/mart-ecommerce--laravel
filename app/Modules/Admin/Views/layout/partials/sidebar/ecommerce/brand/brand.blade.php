
@can('View Brand List')
    <li class="{{request()->routeIs('admin.brands.index') ? 'active' : ''}} treeview">
        <a href="{{route('admin.brands.index')}}">
            <i class="fa fa-home {{request()->routeIs('admin.brands.index') ? 'fa-spin' : ''}}"></i> <span>Brands</span>
            <span class="pull-right-container">
            </span>
        </a>
    </li>
@endcan
