@can('View Product List')
    <li class="{{request()->routeIs('admin.products.index') ? 'active' : ''}} treeview">
        <a href="{{route('admin.products.index')}}">
            <i class="fa fa-circle-o {{request()->routeIs('admin.products.index') ? 'fa-spin' : ''}}"></i> <span>Products</span>
            <span class="pull-right-container">
            </span>
        </a>
    </li>
@endcan
