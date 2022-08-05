@can('View Category Brand List')
    <li class="{{request()->routeIs('admin.categories.brands.index') ? 'active' : ''}} treeview">
        <a href="{{route('admin.categories.brands.index')}}">
            <i class="fa fa-home {{request()->routeIs('admin.categories.brands.index') ? 'fa-spin' : ''}}"></i> <span>Category Brands</span>
            <span class="pull-right-container">
            </span>
        </a>
    </li>
@endcan




