@canany(['View Site Page List','View Faq List','View static Page Image'])
<li class=" {{request()->routeIs('admin.site-pages.*') || request()->routeIs('admin.faqs.*') ? 'active' : '' || request()->routeIs('admin.static-page-images.*') }} treeview">
    <a href="#">
        <i class="fa fa-cart-plus"></i>
        <span>Content Management</span>
        <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
    </a>
    <ul class="treeview-menu">
        @can('View About Page')
            <li class="{{request()->routeIs('admin.about-us.index') ? 'active' : ''}} treeview">
                <a href="{{route('admin.about-us.index')}}">
                    <i class="fa fa-home {{request()->routeIs('admin.about-us.index') ? 'fa-spin' : ''}}"></i>
                    <span>About US</span>
                    <span class="pull-right-container">
                    </span>
                </a>
            </li>
        @endcan
            @can('View Vision Mission Page')
                <li class="{{request()->routeIs('admin.vision-mission.index') ? 'active' : ''}} treeview">
                    <a href="{{route('admin.vision-mission.index')}}">
                        <i class="fa fa-home {{request()->routeIs('admin.vision-mission.index') ? 'fa-spin' : ''}}"></i>
                        <span>Vision Mission</span>
                        <span class="pull-right-container">
                    </span>
                    </a>
                </li>
            @endcan
            @can('View Company Timeline')
                <li class="{{request()->routeIs('admin.company-timeline.index') ? 'active' : ''}} treeview">
                    <a href="{{route('admin.company-timeline.index')}}">
                        <i class="fa fa-home {{request()->routeIs('admin.company-timeline.index') ? 'fa-spin' : ''}}"></i>
                        <span>Company Timeline</span>
                        <span class="pull-right-container">
                    </span>
                    </a>
                </li>
            @endcan
            @can('View Our Team')
                <li class="{{request()->routeIs('admin.our-teams.index') ? 'active' : ''}} treeview">
                    <a href="{{route('admin.our-teams.index')}}">
                        <i class="fa fa-home {{request()->routeIs('admin.our-teams.index') ? 'fa-spin' : ''}}"></i>
                        <span>Our Team</span>
                        <span class="pull-right-container">
                    </span>
                    </a>
                </li>
            @endcan
            @can('View Gallery Team')
                <li class="{{request()->routeIs('admin.our-teams.index') ? 'active' : ''}} treeview">
                    <a href="{{route('admin.team-gallery.index')}}">
                        <i class="fa fa-home {{request()->routeIs('admin.team-gallery.index') ? 'fa-spin' : ''}}"></i>
                        <span>Team Gallery</span>
                        <span class="pull-right-container">
                    </span>
                    </a>
                </li>
            @endcan

        @can('View Site Page List')
            <li class="{{request()->routeIs('admin.site-pages.index') ? 'active' : ''}} treeview">
                <a href="{{route('admin.site-pages.index')}}">
                    <i class="fa fa-home {{request()->routeIs('admin.site-pages.index') ? 'fa-spin' : ''}}"></i>
                    <span>Pages</span>
                    <span class="pull-right-container">
                    </span>
                </a>
            </li>
        @endcan


        @can('View Faq List')
            <li class="{{request()->routeIs('admin.faqs.index') ? 'active' : ''}} treeview">
                <a href="{{route('admin.faqs.index')}}">
                    <i class="fa fa-home {{request()->routeIs('admin.faqs.index') ? 'fa-spin' : ''}}"></i>
                    <span>Faqs</span>
                    <span class="pull-right-container">
            </span>
                </a>
            </li>
        @endcan

        @can('View static Page Image')
            <li class="{{request()->routeIs('admin.static-page-images.index') ? 'active' : ''}} treeview">
                <a href="{{route('admin.static-page-images.index')}}">
                    <i class="fa fa-home {{request()->routeIs('admin.static-page-images.index') ? 'fa-spin' : ''}}"></i>
                    <span>Site Page Images</span>
                    <span class="pull-right-container">
                    </span>
                </a>
            </li>
        @endcan



    </ul>
</li>
@endcanany
