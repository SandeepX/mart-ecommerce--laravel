{{--@canany(['View Wallet Lists','View Wallet Transaction Purpose Lists'])--}}
{{--    <li class="{{request()->routeIs('admin.wallets.*')? "active" : ""}} treeview">--}}
{{--        <a href="#">--}}
{{--            <i class="fa fa-file"></i>--}}
{{--            <span>Wallet</span>--}}
{{--            <span class="pull-right-container">--}}
{{--            <i class="fa fa-angle-left pull-right"></i>--}}
{{--        </span>--}}
{{--        </a>--}}
{{--        <ul class="treeview-menu">--}}
{{--            @can('View Wallet Lists')--}}
{{--            <li class="{{request()->routeIs('admin.wallets.*') ? 'active' : ''}} treeview">--}}
{{--                <a href="{{route('admin.wallets.index')}}">--}}
{{--                    <i class="fa fa-file {{request()->routeIs('admin.wallets.index') ? 'fa-spin' : ''}}"></i>--}}
{{--                    <span>Wallets</span>--}}
{{--                    <span class="pull-right-container">--}}
{{--                </span>--}}
{{--                </a>--}}
{{--            </li>--}}
{{--            @endcan--}}
{{--        </ul>--}}
{{--        <ul class="treeview-menu">--}}
{{--            @can('View Wallet Transaction Purpose Lists')--}}
{{--            <li class="{{request()->routeIs('admin.wallets.transactions-purpose.*') ? 'active' : ''}} treeview">--}}
{{--                <a href="{{route('admin.wallets.transactions-purpose.index')}}">--}}
{{--                    <i class="fa fa-file {{request()->routeIs('admin.wallets.transactions-purpose.*') ? 'fa-spin' : ''}}"></i>--}}
{{--                    <span>Transaction Purpose</span>--}}
{{--                    <span class="pull-right-container">--}}
{{--                </span>--}}
{{--                </a>--}}
{{--            </li>--}}
{{--            @endcan--}}
{{--        </ul>--}}
{{--    </li>--}}
{{--@endcanany--}}
