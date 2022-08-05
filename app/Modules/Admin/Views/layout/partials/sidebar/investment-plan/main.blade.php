@canany([
  'View Investment Plan Lists',
  'View Investment Plan Subscription Lists',
  'View Investment Plan Type List',
])
    <li class="
{{
   request()->routeIs('admin.investment.*') ||
   request()->routeIs('admin.investment-type.*') ||
   request()->routeIs('admin.investment-subscription.*')

? 'active' : ''
}}
        treeview
">
        <a href="#">
            <i class="fa fa-cart-plus"></i>
            <span>Investment Management </span>
            <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
        </a>
        <ul class="treeview-menu">
            @can('View Investment Plan Type List')
                <li class="{{request()->routeIs('admin.investment-type.index') ? 'active' : ''}} treeview">
                    <a href="{{route('admin.investment-type.index')}}">
                        <i class="fa fa-home {{request()->routeIs('admin.investment-type.index') ? 'fa-spin' : ''}}"></i> <span>Investment Plan Type</span>
                        <span class="pull-right-container">
                        </span>
                    </a>
                </li>
            @endcan


            @can('View Investment Plan Lists')
                <li class="{{request()->routeIs('admin.investment.index') ? 'active' : ''}} treeview">
                    <a href="{{route('admin.investment.index')}}">
                        <i class="fa fa-home {{request()->routeIs('admin.investment.index') ? 'fa-spin' : ''}}"></i> <span>Investment Plan</span>
                        <span class="pull-right-container">
                        </span>
                    </a>
                </li>
            @endcan



            @can('View Investment Plan Subscription Lists')
                <li class="{{request()->routeIs('admin.investment-subscription.index') ? 'active' : ''}} treeview">
                    <a href="{{route('admin.investment-subscription.index')}}">
                        <i class="fa fa-home {{request()->routeIs('admin.investment-subscription.index') ? 'fa-spin' : ''}}"></i> <span>Investment Plan Subscription</span>
                        <span class="pull-right-container">
                        </span>
                    </a>
                </li>
            @endcan
        </ul>
    </li>
@endcanany




