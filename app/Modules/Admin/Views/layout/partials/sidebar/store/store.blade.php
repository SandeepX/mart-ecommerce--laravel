

@canany(['View Store List',
'View Store Warehouse List',
'View Store Miscellaneous Payment List',
'View Store Order Offline Payment List',
'View Store Individual Kyc List',
'View Store Firm Kyc List',
'View Store Type List'
])


{{--<li class=" {{request()->routeIs('admin.stores.*') || request()->routeIs('admin.stores.warehouses.*')--}}
{{--|| request()->routeIs('admin.store.orders.*')  || request()->routeIs('admin.stores-kyc.*') || request()->routeIs('admin.balance.*')--}}
{{--|| request()->routeIs('admin.stores.misc-payments.*') || request()->routeIs('admin.stores.offline-order-payments.*') || request()->routeIs('admin.store-balance-control.*')  ? 'active' : ''}} treeview">--}}
{{--    <a href="#">--}}
{{--        <i class="fa fa-cart-plus"></i>--}}
{{--        <span>Store Management</span>--}}
{{--        <span class="pull-right-container">--}}
{{--                    <i class="fa fa-angle-left pull-right"></i>--}}
{{--                    </span>--}}
{{--    </a>--}}

<li class=" {{request()->routeIs('admin.stores.*') || request()->routeIs('admin.stores.warehouses.*')
|| request()->routeIs('admin.store.orders.*')  || request()->routeIs('admin.stores-kyc.*')
|| request()->routeIs('admin.stores.misc-payments.*') || request()->routeIs('admin.stores.offline-order-payments.*')
   || request()->routeIs('admin.offline-payment.payment-holder-type.payment-for.lists')
   || request()->routeIs('admin.online-payments.payment-holder-type.payment-for.lists')
   ? 'active' : ''}} treeview">
    <a href="#">
        <i class="fa fa-cart-plus"></i>
        <span>Store Management</span>
        <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
    </a>
    <ul class="treeview-menu">

        @can('View Store List')
            <li class="{{request()->routeIs('admin.stores.store-registration.unapproved') ? 'active' : ''}} treeview">
                <a href="{{route('admin.stores.store-registration.unapproved')}}">
                    <i class="fa fa-home {{request()->routeIs('admin.stores.store-registration.unapproved') ? 'fa-spin' : ''}}"></i> <span>Unapproved Stores</span>
                    <span class="pull-right-container">
            </span>
                </a>
            </li>
        @endcan

        @can('View Store List')
            <li class="{{request()->routeIs('admin.stores.index') ? 'active' : ''}} treeview">
                <a href="{{route('admin.stores.index')}}">
                    <i class="fa fa-home {{request()->routeIs('admin.stores.index') ? 'fa-spin' : ''}}"></i> <span>Approved Stores</span>
                    <span class="pull-right-container">
            </span>
                </a>
            </li>
        @endcan


            @can('View Store Type List')
                <li class="{{request()->routeIs('admin.store-types.index') ? 'active' : ''}} treeview">
                    <a href="{{route('admin.store-types.index')}}">
                        <i class="fa fa-home {{request()->routeIs('admin.store-types.index') ? 'fa-spin' : ''}}"></i> <span>Store Types</span>
                        <span class="pull-right-container">
            </span>
                    </a>
                </li>
            @endcan

    {{--    @can('View Store Warehouse List')
            @includeIf('Admin::layout.partials.sidebar.store.store-warehouses')
        @endcan
--}}

        @can('View Store Order List')
            <li class="{{request()->routeIs('admin.store.orders.index') ? 'active' : ''}} treeview">
                <a href="{{route('admin.store.orders.index')}}">
                    <i class="fa fa-home {{request()->routeIs('admin.store.orders.index') ? 'fa-spin' : ''}}"></i>
                    <span>Store Orders</span>
                    <span class="pull-right-container">
            </span>
                </a>
            </li>
        @endcan


{{--        @can('View Store Miscellaneous Payment List')--}}
{{--            <li class="{{request()->routeIs('admin.stores.misc-payments.index') ? 'active' : ''}} treeview">--}}
{{--                <a href="{{route('admin.stores.misc-payments.index')}}">--}}
{{--                    <i class="fa fa-home {{request()->routeIs('admin.stores.misc-payments.index') ? 'fa-spin' : ''}}"></i>--}}
{{--                    <span>Miscellaneous Payments</span>--}}
{{--                    <span class="pull-right-container">--}}
{{--            </span>--}}
{{--                </a>--}}
{{--            </li>--}}
{{--        @endcan--}}


{{--        @can('View Store Order Offline Payment List')--}}
{{--            <li class="{{request()->routeIs('admin.stores.offline-order-payments.index') ? 'active' : ''}} treeview">--}}
{{--                <a href="{{route('admin.stores.offline-order-payments.index')}}">--}}
{{--                    <i class="fa fa-home {{request()->routeIs('admin.stores.offline-order-payments.index') ? 'fa-spin' : ''}}"></i>--}}
{{--                    <span>Offline Order Payments</span>--}}
{{--                    <span class="pull-right-container">--}}
{{--            </span>--}}
{{--                </a>--}}
{{--            </li>--}}
{{--        @endcan--}}

        @canany(['View Store Individual Kyc List','View Store Firm Kyc List'])
                <li class="{{request()->routeIs('admin.stores-kyc.listings') ? 'active' : ''}} treeview">
                    <a href="{{route('admin.stores-kyc.listings')}}">
                        <i class="fa fa-home {{request()->routeIs('admin.stores-kyc.listings') ? 'fa-spin' : ''}}"></i>
                        <span>Store Kyc</span>
                        <span class="pull-right-container">
            </span>
                    </a>
                </li>
        @endcanany

{{--            store offline and offline payments lists--}}

            <li class="{{request()->routeIs('admin.offline-payment.payment-holder-type.payment-for.lists')
                        || request()->routeIs('admin.online-payments.payment-holder-type.payment-for.lists') ? 'active' : ''}} treeview">
                <a href="#">
                    <i class="fa fa-home"></i>
                    <span>Store Load Balance</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>

                <ul class="treeview-menu">
                    <li class="{{request()->routeIs('admin.offline-payment.payment-holder-type.payment-for.lists') ? 'active' : ''}} treeview">
                        <a href="{{route('admin.offline-payment.payment-holder-type.payment-for.lists',['payment_holder_type'=>'store','payment_for'=>'load_balance'])}}">
                            <i class="fa fa-home {{request()->routeIs('admin.offline-payment.payment-holder-type.payment-for.lists') ? 'fa-spin' : ''}}"></i>
                            <span>Offline</span>
                            <span class="pull-right-container"> </span>
                        </a>
                    </li>

                    <li class="{{request()->routeIs('admin.online-payments.payment-holder-type.payment-for.lists') ? 'active' : ''}} treeview">
                        <a href="{{route('admin.online-payments.payment-holder-type.payment-for.lists',['payment_holder_code'=>'store','payment_for'=>'load_balance'])}}">
                            <i class="fa fa-home {{request()->routeIs('admin.online-payments.payment-holder-type.payment-for.lists') ? 'fa-spin' : ''}}"></i>
                            <span>Online</span>
                            <span class="pull-right-container"> </span>
                        </a>
                    </li>
                </ul>
            </li>

{{--            @canany([--}}
{{--    'View Store Balance Management',--}}
{{--    'View Store Balance Reconciliation List'--}}
{{--])--}}
{{--                <li class=" {{request()->routeIs('admin.balance.*') || request()->routeIs('admin.store-balance-control.*')? 'active' : ''}} treeview">--}}
{{--                    <a href="#">--}}
{{--                        <i class="fa fa-home "></i>--}}
{{--                        <span>Balance Management</span>--}}
{{--                        <span class="pull-right-container">--}}
{{--                    <i class="fa fa-angle-left pull-right"></i>--}}
{{--                    </span>--}}
{{--                    </a>--}}
{{--                    <ul class="treeview-menu">--}}

{{--                        @can('View Store Balance Withdraw List')--}}
{{--                            <li class="{{request()->routeIs('admin.balance.withdraw') ? 'active' : ''}} treeview">--}}
{{--                                <a href="{{route('admin.balance.withdraw')}}">--}}
{{--                                    <i class="fa fa-home {{request()->routeIs('admin.balance.withdraw') ? 'fa-spin' : ''}}"></i>--}}
{{--                                    <span>Withdraw Request</span>--}}
{{--                                    <span class="pull-right-container"></span>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                        @endcan--}}


{{--                        @can('View Store Balance Statement List')--}}
{{--                            <li class="{{request()->routeIs('admin.store.balance.list') ? 'active' : ''}} treeview">--}}
{{--                                <a href="{{route('admin.store.balance.list')}}">--}}
{{--                                    <i class="fa fa-home {{request()->routeIs('admin.store.balance.list') ? 'fa-spin' : ''}}"></i>--}}
{{--                                    <span>Balance</span>--}}

{{--                                    <span class="pull-right-container"> </span>--}}

{{--                                </a>--}}
{{--                            </li>--}}
{{--                        @endcan--}}


{{--                          @can('View Store Balance Reconciliation List')--}}
{{--                            <li class="{{request()->routeIs('admin.balance.reconciliation') ? 'active' : ''}} treeview">--}}
{{--                                <a href="{{route('admin.balance.reconciliation')}}">--}}
{{--                                    <i class="fa fa-home {{request()->routeIs('admin.balance.reconciliation') ? 'fa-spin' : ''}}"></i>--}}
{{--                                    <span>Balance Reconciliation </span>--}}
{{--                                    <span class="pull-right-container"> </span>--}}

{{--                                </a>--}}
{{--                            </li>--}}
{{--                            @endcan--}}



{{--                    </ul>--}}
{{--                </li>--}}
{{--            @endcanany--}}




    </ul>


</li>
@endcanany
