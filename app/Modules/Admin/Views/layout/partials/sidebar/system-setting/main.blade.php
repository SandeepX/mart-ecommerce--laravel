@canany(['View General Setting',
'View Seo Setting',
'Update Mail Setting',
'Update Passport Setting',
'Update Site Url Setting',
'View Ip Access List'
])
<li class="
{{
   request()->routeIs('admin.general-settings.*')||
   request()->routeIs('admin.seo-settings.*') ||
   request()->routeIs('admin.mail-settings.*') ||
   request()->routeIs('admin.passport-settings.*') ||
   request()->routeIs('admin.url-settings.*') ||
   request()->routeIs('admin.ip-access-settings.*') ||
   request()->routeIs('admin.mobile-app-deployment-version.*')
? 'active' : ''
}}
        treeview
">
    <a href="#">
        <i class="fa fa-home"></i>
        <span>System Setting</span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        @can('View General Setting')
            <li class="{{request()->routeIs('admin.general-settings.show') ? 'active' : ''}} treeview">
                <a href="{{route('admin.general-settings.show')}}">
                    <i class="fa fa-circle-o {{request()->routeIs('admin.general-settings.show') ? 'fa-spin' : ''}}"></i>
                    <span>General Settings</span>
                    <span class="pull-right-container">
                </span>
                </a>
            </li>
        @endcan

        @can('View Seo Setting')
            <li class="{{request()->routeIs('admin.seo-settings.show') ? 'active' : ''}} treeview">
                <a href="{{route('admin.seo-settings.show')}}">
                    <i class="fa fa-circle-o {{request()->routeIs('admin.seo-settings.show') ? 'fa-spin' : ''}}"></i>
                    <span>Seo Settings</span>
                    <span class="pull-right-container">
                </span>
                </a>
            </li>
        @endcan


        @can('Update Mail Setting')
            <li class="{{request()->routeIs('admin.mail-settings.edit') ? 'active' : ''}} treeview">
                <a href="{{route('admin.mail-settings.edit')}}">
                    <i class="fa fa-circle-o {{request()->routeIs('admin.mail-settings.edit') ? 'fa-spin' : ''}}"></i>
                    <span>Mail Settings</span>
                    <span class="pull-right-container">
                </span>
                </a>
            </li>

        @endcan

        @can('Update Passport Setting')
            <li class="{{request()->routeIs('admin.passport-settings.edit') ? 'active' : ''}} treeview">
                <a href="{{route('admin.passport-settings.edit')}}">
                    <i class="fa fa-circle-o {{request()->routeIs('admin.passport-settings.edit') ? 'fa-spin' : ''}}"></i>
                    <span>Passport Settings</span>
                    <span class="pull-right-container">
                </span>
                </a>
            </li>
        @endcan


        @can('Update Site Url Setting')
            <li class="{{request()->routeIs('admin.url-settings.edit') ? 'active' : ''}} treeview">
                <a href="{{route('admin.url-settings.edit')}}">
                    <i class="fa fa-circle-o {{request()->routeIs('admin.url-settings.edit') ? 'fa-spin' : ''}}"></i>
                    <span>Site Url Settings</span>
                    <span class="pull-right-container">
                </span>
                </a>
            </li>
        @endcan

        @can('View Ip Access List')
            <li class="{{request()->routeIs('admin.ip-access-settings.*') ? 'active' : ''}} treeview">
                <a href="{{route('admin.ip-access-settings.index')}}">
                    <i class="fa fa-circle-o {{request()->routeIs('admin.ip-access-settings.*') ? 'fa-spin' : ''}}"></i>
                    <span>Ip Access Settings</span>
                    <span class="pull-right-container">
                </span>
                </a>
            </li>
        @endcan

        @can('Force Logout Store')
            <li class="{{request()->routeIs('admin.force-logout-store.*') ? 'active' : ''}} treeview">
                <a href="{{route('admin.force-logout-store.index')}}">
                    <i class="fa fa-circle-o {{request()->routeIs('admin.force-logout-store.*') ? 'fa-spin' : ''}}"></i>
                    <span>Force Logout Store</span>
                    <span class="pull-right-container">
            </span>
                </a>
            </li>
        @endcan

            <li class="{{request()->routeIs('admin.mobile-app-deployment-version.*') ? 'active' : ''}} treeview">
                <a href="{{route('admin.mobile-app-deployment-version.show')}}">
                    <i class="fa fa-circle-o {{request()->routeIs('admin.mobile-app-deployment-version.*') ? 'fa-spin' : ''}}"></i>
                    <span>Mobile App Deployment Version</span>
                    <span class="pull-right-container">
            </span>
                </a>
            </li>
   </ul>

</li>
@endcanany
