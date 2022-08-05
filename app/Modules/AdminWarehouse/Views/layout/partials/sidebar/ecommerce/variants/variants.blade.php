@can('View Variant List')
    <li class="{{request()->routeIs('admin.variants.index') ? 'active' : ''}} treeview">
        <a href="{{route('admin.variants.index')}}">
            <i class="fa fa-home {{request()->routeIs('admin.variants.index') ? 'fa-spin' : ''}}"></i> <span>Variants</span>
            <span class="pull-right-container">
            </span>
        </a>
    </li>
@endcan
