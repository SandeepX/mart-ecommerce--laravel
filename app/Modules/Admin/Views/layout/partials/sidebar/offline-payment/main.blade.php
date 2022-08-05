{{--@canany([--}}
{{--  'View Offline Payments Lists',--}}
{{--  'View Online Payment Lists'--}}
{{--])--}}
{{--    <li class="{{ (request()->routeIs('admin.offline-payment.*') || request()->routeIs('admin.online-payments.*')) &&--}}
{{--!request()->routeIs('admin.offline-payment.payment-holder-type.payment-for.lists') && !request()->routeIs('admin.online-payments.payment-holder-type.payment-for.lists')--}}
{{--        ? 'active' : '' }} treeview"--}}
{{--    >--}}

{{--        <a href="#">--}}
{{--            <i class="fa fa-cart-plus"></i>--}}
{{--            <span>Payment Management </span>--}}
{{--            <span class="pull-right-container">--}}
{{--                    <i class="fa fa-angle-left pull-right"></i>--}}
{{--                    </span>--}}
{{--        </a>--}}

{{--        <ul class="treeview-menu">--}}
{{--            @can('View Offline Payments Lists')--}}
{{--                <li class="{{request()->routeIs('admin.offline-payment.index') ? 'active' : ''}} treeview">--}}
{{--                    <a href="{{route('admin.offline-payment.index')}}">--}}
{{--                        <i class="fa fa-home {{request()->routeIs('admin.offline-payment.index') ? 'fa-spin' : ''}}"></i> <span>Offline Payments</span>--}}
{{--                        <span class="pull-right-container">--}}
{{--                        </span>--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--            @endcan--}}

{{--            @can('View Online Payment Lists')--}}
{{--                <li class="{{request()->is('admin/online-payments/lists')? "active" : ""}} treeview">--}}
{{--                    <a href="{{route('admin.online-payments.lists')}}">--}}
{{--                        <i class="fa fa-home {{request()->is('admin/online-payments/lists') ? 'fa-spin' : ''}}"></i>--}}
{{--                        <span>Online Payment Logs</span>--}}
{{--                        <span class="pull-right-container">--}}
{{--                        </span>--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--            @endcan--}}
{{--        </ul>--}}
{{--    </li>--}}
{{--@endcanany--}}

{{--@can('View Offline Miscellaneous Payments Lists')--}}
{{--    <li class="{{request()->routeIs('admin.offline-payment.*') ? 'active' : ''}} treeview">--}}
{{--        <a href="{{route('admin.offline-misc-payment.index')}}">--}}
{{--            <i class="fa fa-home {{request()->routeIs('admin.offline-misc-payment.index') ? 'fa-spin' : ''}}"></i> <span>Offline Miscellaneous Payment</span>--}}
{{--            <span class="pull-right-container">--}}
{{--            </span>--}}
{{--        </a>--}}
{{--    </li>--}}
{{--@endcan--}}





