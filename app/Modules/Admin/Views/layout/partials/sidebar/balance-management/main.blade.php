@canany([
    'View Wallet Lists',
    'View Wallet Transaction Purpose Lists',
    'View Store Balance Withdraw List',
    'View Store Balance Statement List',
    'View Store Balance Reconciliation List',
    'View Day Book'
    ])

    <li class=" {{request()->routeIs('admin.wallets.index') || request()->routeIs('admin.balance.*') || request()->routeIs('admin.daybook.*')
       || request()->routeIs('admin.stores.offline-order-payments.*') || request()->routeIs('admin.store-balance-control.*')  ? 'active' : ''}} treeview">
        <a href="#">
            <i class="fa fa-cart-plus"></i>
            <span>Balance Management</span>
            <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
        </a>

        <ul class="treeview-menu">

            @can('View Daybook')
                <li class="{{request()->routeIs('admin.daybook.index') ? 'active' : ''}} treeview">
                    <a href="{{route('admin.daybook.index')}}">
                        <i class="fa fa-home {{request()->routeIs('admin.daybook.index') ? 'fa-spin' : ''}}"></i>
                        <span>Daybook</span>
                        <span class="pull-right-container"></span>
                    </a>
                </li>
            @endcan

            @can('View Store Balance Withdraw List')
                <li class="{{request()->routeIs('admin.balance.withdraw') ? 'active' : ''}} treeview">
                    <a href="{{route('admin.balance.withdraw')}}">
                        <i class="fa fa-home {{request()->routeIs('admin.balance.withdraw') ? 'fa-spin' : ''}}"></i>
                        <span>Withdraw Request</span>
                        <span class="pull-right-container"></span>
                    </a>
                </li>
            @endcan

            @can('View Store Balance Reconciliation List')
                <li class="{{request()->routeIs('admin.balance.reconciliation') ? 'active' : ''}} treeview">
                    <a href="{{route('admin.balance.reconciliation')}}">
                        <i class="fa fa-home {{request()->routeIs('admin.balance.reconciliation') ? 'fa-spin' : ''}}"></i>
                        <span>Balance Reconciliation </span>
                        <span class="pull-right-container"> </span>
                    </a>
                </li>
            @endcan

            @can('View Wallet Lists')
                <li class="{{request()->routeIs('admin.wallets.index') ? 'active' : ''}} treeview">
                    <a href="{{route('admin.wallets.index')}}">
                        <i class="fa fa-home {{request()->routeIs('admin.wallets.index') ? 'fa-spin' : ''}}"></i>
                        <span>Wallets</span>
                        <span class="pull-right-container"></span>
                    </a>
                </li>
            @endcan
        </ul>
@endcanany
