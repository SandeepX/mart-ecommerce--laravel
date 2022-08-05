
@canany(['View Pricing Link Lists','View Pricing Link Lead Lists'])
    <li class=" {{(request()->routeIs('admin.pricing-master.*') || request()->routeIs('admin.pricing-link-lead.*')) ? 'active' : ''}} treeview">
        <a href="#">
            <i class="fa fa-cart-plus"></i>
            <span>Pricing Link</span>
            <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
        </a>

        <ul class="treeview-menu">
            @can('View Pricing Link Lists')
                <li class="{{request()->routeIs('admin.pricing-master.index') ? 'active' : ''}} treeview">
                    <a href="{{route('admin.pricing-master.index')}}">
                        <i class="fa fa-home {{request()->routeIs('admin.pricing-master.index') ? 'fa-spin' : ''}}"></i> <span>Pricing Link</span>
                        <span class="pull-right-container">
                        </span>
                    </a>
                </li>
            @endcan

            @can('View Pricing Link Lead Lists')
                <li class="{{request()->routeIs('admin.pricing-link-lead.index') ? 'active' : ''}} treeview">
                    <a href="{{route('admin.pricing-link-lead.index')}}">
                        <i class="fa fa-home {{request()->routeIs('admin.pricing-link-lead.index') ? 'fa-spin' : ''}}"></i> <span>Lead</span>
                        <span class="pull-right-container">
                        </span>
                    </a>
                </li>
            @endcan
        </ul>
    </li>

@endcanany


