<aside class="main-sidebar" >
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar"  id="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{url('admin/images/user.png')}}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ isset(Auth::user()->name) ? Auth::user()->name : '' }}</p>
            </div>
        </div>

        <ul class="sidebar-menu">

            <li class="header" style="color: white;">MAIN NAVIGATION</li>

            @include('SupportAdmin::layout.partials.sidebar.dashboard.dashboard')
            @include('SupportAdmin::layout.partials.sidebar.store.main')

        </ul>

    </section>
    <!-- /.sidebar -->
</aside>
