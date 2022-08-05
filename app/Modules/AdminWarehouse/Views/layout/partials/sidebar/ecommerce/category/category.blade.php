@can('View Category List')
    <li class="{{request()->routeIs('admin.categories.index') ? 'active' : ''}} treeview">
        <a href="{{route('admin.categories.index')}}">
            <i class="fa fa-home {{request()->routeIs('admin.categories.index') ? 'fa-spin' : ''}}"></i> <span>Category</span>
            <span class="pull-right-container">
            </span>
        </a>
    </li>
@endcan
