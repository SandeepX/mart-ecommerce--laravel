
@canany(['View Manager Lists',
'View Manager SMI Setting Lists',
'View Manager SMI Lists',
'View Social Media Setting'
])
    <li class="
{{
   request()->routeIs('admin.salesmanager.*') ||
   request()->routeIs('admin.manager-smi-setting.*') ||
   request()->routeIs('admin.manager-smi.*') ||
   request()->routeIs('admin.social-media.*') ||
   request()->routeIs('admin.manager-pay-per-visits.*') ||
   request()->routeIs('admin.visit-claim-scan-redirection.*') ||
   request()->routeIs('admin.store-visit-claim-requests.*')||
   request()->routeIs('admin.salesManager.mangerStoreLocation.*')

? 'active' : ''
}}
        treeview
">
        <a href="#">
            <i class="fa fa-home"></i>
            <span>Manager Management</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
        </a>
        <ul class="treeview-menu">
            @can('View Manager Lists')
                <li class="{{request()->routeIs('admin.salesmanager.index') ? 'active' : ''}} treeview">
                    <a href="{{route('admin.salesmanager.index')}}">
                        <i class="fa fa-file {{request()->routeIs('admin.salesmanager.index') ? 'fa-spin' : ''}}"></i>
                        <span>Sales Manager</span>
                        <span class="pull-right-container">
                </span>
                    </a>
                </li>
            @endcan

                @can('View Manager SMI Lists')
                    <li class="{{request()->routeIs('admin.manager-smi.*') ? 'active' : ''}} treeview">
                        <a href="{{route('admin.manager-smi.index')}}">
                            <i class="fa fa-file {{request()->routeIs('admin.manager-smi.*') ? 'fa-spin' : ''}}"></i>
                            <span>Manager SMI </span>
                            <span class="pull-right-container">
            </span>
                        </a>
                    </li>
                @endcan



            @can('View Manager SMI Setting Lists')
                <li class="{{request()->routeIs('admin.manager-smi-setting.*') ? 'active' : ''}} treeview">
                    <a href="{{route('admin.manager-smi-setting.index')}}">
                        <i class="fa fa-file {{request()->routeIs('admin.manager-smi-setting.index') ? 'fa-spin' : ''}}"></i>
                        <span>Manager SMI Setting</span>
                        <span class="pull-right-container">
            </span>
                    </a>
                </li>
            @endcan

                @can('View Social Media Setting')
                    <li class="{{request()->routeIs('admin.social-media.*') ? 'active' : ''}} treeview">
                        <a href="{{route('admin.social-media.index')}}">
                            <i class="fa fa-file {{request()->routeIs('admin.social-media.*') ? 'fa-spin' : ''}}"></i>
                            <span>Social Media</span>
                            <span class="pull-right-container">
            </span>
                        </a>
                    </li>
                @endcan

                <li class="{{request()->routeIs('admin.store-visit-claim-requests.*') ? 'active' : ''}} treeview">
                    <a href="{{route('admin.store-visit-claim-requests.index')}}">
                        <i class="fa fa-file {{request()->routeIs('admin.store-visit-claim-requests.*') ? 'fa-spin' : ''}}"></i>
                        <span>Visit Claims</span>
                        <span class="pull-right-container">
            </span>
                    </a>
                </li>

                <li class="{{request()->routeIs('admin.manager-pay-per-visits.*') ? 'active' : ''}} treeview">
                    <a href="{{route('admin.manager-pay-per-visits.index')}}">
                        <i class="fa fa-file {{request()->routeIs('admin.manager-pay-per-visits.*') ? 'fa-spin' : ''}}"></i>
                        <span>Pay Per Visits</span>
                        <span class="pull-right-container">
            </span>
                    </a>
                </li>

                <li class="{{request()->routeIs('admin.visit-claim-scan-redirection.*') ? 'active' : ''}} treeview">
                    <a href="{{route('admin.visit-claim-scan-redirection.index')}}">
                        <i class="fa fa-file {{request()->routeIs('admin.visit-claim-scan-redirection.*') ? 'fa-spin' : ''}}"></i>
                        <span>Scan Redirections</span>
                        <span class="pull-right-container">
            </span>
                    </a>
                </li>
                <li class="{{request()->routeIs('admin.salesManager.mangerStoreLocation.*') ? 'active' : ''}} treeview">
                    <a href="{{route('admin.salesManager.mangerStoreLocation')}}">
                        <i class="fa fa-file {{request()->routeIs('admin.salesManager.mangerStoreLocation.*') ? 'fa-spin' : ''}}"></i>
                        <span>Store Locations</span>
                        <span class="pull-right-container">
</span>
                    </a>
                </li>

        </ul>
    </li>
@endcanany



