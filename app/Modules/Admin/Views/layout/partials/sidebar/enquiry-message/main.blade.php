@can('View Contact Message List')
    <li class="{{request()->is('admin/enquiry-messages/*')? "active" : ""}} treeview">
        <a href="#">
            <i class="fa fa-file"></i>
            <span>Enquiry Messages</span>
            <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
        </a>
        <ul class="treeview-menu">
            <li class="{{request()->routeIs('admin.enquiry-messages.index') ? 'active' : ''}} treeview">
                <a href="{{route('admin.enquiry-messages.index')}}">
                    <i class="fa fa-file {{request()->routeIs('admin.enquiry-messages.index') ? 'fa-spin' : ''}}"></i>
                    <span>Enquiry Messages</span>
                    <span class="pull-right-container">
                </span>
                </a>
            </li>

        </ul>
    </li>
@endcan
