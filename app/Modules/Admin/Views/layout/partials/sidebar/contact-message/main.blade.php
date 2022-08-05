@canany(['View Contact Message List','View Store Enquiry Message List'])
    <li class="{{request()->is('admin/contact-messages/*')? "active" : ""}} treeview">
        <a href="#">
            <i class="fa fa-file"></i>
            <span>Messages</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
        </a>
        <ul class="treeview-menu">
            @can('View Contact Message List')
            <li class="{{request()->routeIs('admin.contact-messages.index') ? 'active' : ''}} treeview">
                <a href="{{route('admin.contact-messages.index')}}">
                    <i class="fa fa-file {{request()->routeIs('admin.contact-messages.index') ? 'fa-spin' : ''}}"></i>
                    <span>Contact Messages</span>
                    <span class="pull-right-container">
                </span>
                </a>
            </li>
            @endcan
            @can('View Store Enquiry Message List')
            <li class="{{request()->routeIs('admin.enquiry-messages.index') ? 'active' : ''}} treeview">
                <a href="{{route('admin.enquiry-messages.index')}}">
                    <i class="fa fa-file {{request()->routeIs('admin.enquiry-messages.index') ? 'fa-spin' : ''}}"></i>
                    <span>Store Enquiry Messages</span>
                    <span class="pull-right-container">
                </span>
                </a>
            </li>
            @endcan
        </ul>
    </li>
@endcanany
