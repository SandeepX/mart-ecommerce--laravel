<li class="{{request()->routeIs('admin.leads.*') ? 'active' : ''}} treeview">
    <a href="{{route('admin.leads.index')}}">
        <i class="fa fa-users {{request()->routeIs('admin.leads.index') ? 'fa-spin' : ''}}"></i> 
        <span>Leads</span>
        <span class="pull-right-container">
            </span>
    </a>
</li>