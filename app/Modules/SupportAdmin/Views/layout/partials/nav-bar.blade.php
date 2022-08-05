<header class="main-header">

    <!-- Logo -->
    <a href="{{ route('support-admin.dashboard') }}" class="logo" style="height: 52px !important;">

        Alpasal - Support Admin
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">
            ALP
        </span>
        <!-- logo for regular state and mobile devices -->
        {{--<img
            src="{{ isset($company_detail) ? url('uploads/company_logo/' . $company_detail->company_logo) : url('admin/images/logo.png') }}"
            style="width: 50px;margin-left: -20px">--}}
        <span style="font-size: 12px;font-weight:bold">

        </span>

        {{--<span class="logo-lg">OM</span>--}}

    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">
                <!-- Messages: style can be found in dropdown.less-->
                <li class="dropdown messages-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-bell-o"></i>
                        <span class="label label-success">{{ $unreadNotifications }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">You have {{ $unreadNotifications }} new notifications</li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">

                                @foreach ($latestLimitedNotifications as $notification)
                                    <li>
                                        <a href="{{ $notification->data['url'] }}">
                                            <i class="fa fa-globe text-aqua"></i>
                                                @if (isset($notification->read_at))
                                                    {{ $notification->data['message'] }}
                                                @else
                                                    <b> {{ $notification->data['message'] }} </b>
                                                @endif
                                            <small class="pull-right"><i class="fa fa-clock-o"></i> {{ $notification->created_at }} </small>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                        <li class="footer"><a href="{{route('support-admin.notifications.index')}}">See All</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">

                {{--@haspermission('order.index')--}}
                {{--@include('admin.partials.order_due_reminder')--}}
                {{--@endhaspermission--}}

                {{--@haspermission('product.index')--}}
                {{--@include('admin.partials.low_stock_alert')--}}
                {{--@endhaspermission--}}


                <li class="dropdown" style="margin-right: 10px;">
                    <a href="#" class="dropdown-toggle avatar" data-toggle="dropdown"><i class="fa fa-user"></i>
                        {{ auth()->check() ? auth()->user()->name : '' }}
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu">

                       <li class="m_2">
                            <a href="{{ route('support-admin.changePassword') }}">
                                <i class="fa fa-user"></i> Change Password
                            </a>
                        </li>

                        <li class="m_2">

                            <a href="{{ route('support-admin.logout') }}" onclick="event.preventDefault();
                                  document.getElementById('logout-form').submit();">
                                <i class="fa fa-lock"></i> Logout
                            </a>
                            <form id="logout-form" action="{{ route('support-admin.logout') }}" method="POST"
                                style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
