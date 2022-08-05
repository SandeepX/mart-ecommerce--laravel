@canany(['View Vendor List', 'View Vendor Admin List'])
<li class=" {{request()->routeIs('admin.vendors.*') || request()->routeIs('admin.vendorTarget.*')  || request()->routeIs('admin.vendor-users.*')? 'active' : ''}} treeview">
    <a href="#">
        <i class="fa fa-cart-plus"></i>
        <span>Vendor Management</span>
        <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
    </a>
    <ul class="treeview-menu">

        @can('View Vendor List')
            <li class="{{request()->routeIs('admin.vendors.index') ? 'active' : ''}} treeview">
                <a href="{{route('admin.vendors.index')}}">
                    <i class="fa fa-home {{request()->routeIs('admin.vendors.index') ? 'fa-spin' : ''}}"></i> <span>Vendors</span>
                    <span class="pull-right-container">
            </span>
                </a>
            </li>
        @endcan

        @can('View Vendor Admin List')
            <li class="{{request()->routeIs('admin.vendor-users.index') ? 'active' : ''}} treeview">
                <a href="{{route('admin.vendor-users.index')}}">
                    <i class="fa fa-home {{request()->routeIs('admin.vendor-users.index') ? 'fa-spin' : ''}}"></i>
                    <span>Vendor Admins</span>
                    <span class="pull-right-container">
            </span>
                </a>
            </li>
        @endcan

{{--            @can('View Vendor Target List')--}}
                <li class="{{request()->routeIs('admin.vendorTarget.index') ? 'active' : ''}} treeview">
                    <a href="{{route('admin.vendorTarget.index')}}">
                        <i class="fa fa-home {{request()->routeIs('admin.vendorTarget.index') ? 'fa-spin' : ''}}"></i>
                        <span>Vendor Targets</span>
                        <span class="pull-right-container"></span>
                    </a>
                </li>
{{--            @endcan--}}


    </ul>
</li>
@endcanany
