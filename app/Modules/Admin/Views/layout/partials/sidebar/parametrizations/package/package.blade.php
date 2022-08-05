<li class="{{request()->routeIs('admin.package-types.index') ? 'active' : ''}} treeview">
    <a href="{{route('admin.package-types.index')}}">
        <i class="fa fa-circle-o {{request()->routeIs('admin.package-types.index') ? 'fa-spin' : ''}}"></i> <span>Packages</span>
        <span class="pull-right-container">
            </span>
    </a>
</li>