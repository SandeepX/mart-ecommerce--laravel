@canany(['View Vendor Type List',
'View Store Size List',
'View Company Type List',
'View Registration Type List',
'View Category Type List',
'View Cancellation Parameter List',
'View Rejection Parameter List',
'View Package Type List',
'View Product Sensitivity List',
'View Product Warranty List',
'View Wallet Transaction Purpose Lists'
])
<li class="
{{
   request()->routeIs('admin.user-types.*') ||
   request()->routeIs('admin.vendor-types.*') ||
   request()->routeIs('admin.company-types.*') ||
   request()->routeIs('admin.registration-types.*') ||
   request()->routeIs('admin.category-types.*') ||
   request()->routeIs('admin.cancellation-params.*') ||
   request()->routeIs('admin.rejection-params.*') ||
   request()->routeIs('admin.store-sizes.*') ||
   request()->routeIs('admin.package-types.*') ||
   request()->routeIs('admin.product-sensitivities.*') ||
   request()->routeIs('admin.product-warranties.*') ||
   request()->routeIs('admin.wallets.transactions-purpose.*')
? 'active' : ''
}}
        treeview
">
    <a href="#">
        <i class="fa fa-cart-plus"></i>
        <span>Parametrizations</span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">


        {{-- <li class="{{request()->routeIs('admin.user-types.index') ? 'active' : ''}} treeview">
             <a href="{{route('admin.user-types.index')}}">
                 <i class="fa fa-circle-o {{request()->routeIs('admin.user-types.index') ? 'fa-spin' : ''}}"></i> <span>User Types</span>
                 <span class="pull-right-container">
                 </span>
             </a>
         </li>--}}


        @can('View Wallet Transaction Purpose Lists')
            <li class="{{request()->routeIs('admin.wallets.transactions-purpose.*') ? 'active' : ''}} treeview">
                <a href="{{route('admin.wallets.transactions-purpose.index')}}">
                    <i class="fa fa-circle-o {{request()->routeIs('admin.wallets.transactions-purpose.*') ? 'fa-spin' : ''}}"></i>
                    <span>Transaction Purpose</span>
                    <span class="pull-right-container">
            </span>
                </a>
            </li>
        @endcan



        @can('View Vendor Type List')
            <li class="{{request()->routeIs('admin.vendor-types.index') ? 'active' : ''}} treeview">
                <a href="{{route('admin.vendor-types.index')}}">
                    <i class="fa fa-circle-o {{request()->routeIs('admin.vendor-types.index') ? 'fa-spin' : ''}}"></i> <span>Vendor Types</span>
                    <span class="pull-right-container">
                </span>
                </a>
            </li>
        @endcan

        @can('View Store Size List')
            <li class="{{request()->routeIs('admin.store-sizes.index') ? 'active' : ''}} treeview">
                <a href="{{route('admin.store-sizes.index')}}">
                    <i class="fa fa-circle-o {{request()->routeIs('admin.store-sizes.index') ? 'fa-spin' : ''}}"></i> <span>Store Sizes</span>
                    <span class="pull-right-container">
                </span>
                </a>
            </li>
        @endcan

        @can('View Company Type List')
            <li class="{{request()->routeIs('admin.company-types.index') ? 'active' : ''}} treeview">
                <a href="{{route('admin.company-types.index')}}">
                    <i class="fa fa-circle-o {{request()->routeIs('admin.company-types.index') ? 'fa-spin' : ''}}"></i> <span>Company Types</span>
                    <span class="pull-right-container">
                </span>
                </a>
            </li>
        @endcan


        @can('View Registration Type List')
            <li class="{{request()->routeIs('admin.registration-types.index') ? 'active' : ''}} treeview">
                <a href="{{route('admin.registration-types.index')}}">
                    <i class="fa fa-circle-o {{request()->routeIs('admin.registration-types.index') ? 'fa-spin' : ''}}"></i> <span>Registration Types</span>
                    <span class="pull-right-container">
                </span>
                </a>
            </li>
        @endcan

        @can('View Category Type List')
            <li class="{{request()->routeIs('admin.category-types.index') ? 'active' : ''}} treeview">
                <a href="{{route('admin.category-types.index')}}">
                    <i class="fa fa-circle-o {{request()->routeIs('admin.category-types.index') ? 'fa-spin' : ''}}"></i> <span>Category Types</span>
                    <span class="pull-right-container">
                </span>
                </a>
            </li>
        @endcan


        @can('View Cancellation Parameter List')
            <li class="{{request()->routeIs('admin.cancellation-params.index') ? 'active' : ''}} treeview">
                <a href="{{route('admin.cancellation-params.index')}}">
                    <i class="fa fa-circle-o {{request()->routeIs('admin.cancellation-params.index') ? 'fa-spin' : ''}}"></i> <span>Cancellation Params</span>
                    <span class="pull-right-container">
                </span>
                </a>
            </li>
        @endcan


        @can('View Rejection Parameter List')
            <li class="{{request()->routeIs('admin.rejection-params.index') ? 'active' : ''}} treeview">
                <a href="{{route('admin.rejection-params.index')}}">
                    <i class="fa fa-circle-o {{request()->routeIs('admin.rejection-params.index') ? 'fa-spin' : ''}}"></i> <span>Rejection Params</span>
                    <span class="pull-right-container">
                </span>
                </a>
            </li>
        @endcan


        @can('View Package Type List')
            @includeIf('Admin::layout.partials.sidebar.parametrizations.package.package')
        @endcan

        @can('View Product Sensitivity List')
            @includeIf('Admin::layout.partials.sidebar.parametrizations.sensitivity.sensitivity')
        @endcan

        @can('View Product Warranty List')
            @includeIf('Admin::layout.partials.sidebar.parametrizations.warranty.warranty')
        @endcan
    </ul>
</li>
@endcanany
