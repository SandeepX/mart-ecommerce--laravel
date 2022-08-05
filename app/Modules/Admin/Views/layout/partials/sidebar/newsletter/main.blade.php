@canany(['View Subscriber List'])
<li class=" {{request()->routeIs('admin.subscribers.*')? 'active' : ''}} treeview">
    <a href="#">
        <i class="fa fa-cart-plus"></i>
        <span>Newsletter</span>
        <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
    </a>
    <ul class="treeview-menu">

        @can('View Subscriber List')
            <li class="{{request()->is('admin/subscribers/*')? "active" : ""}} treeview">
                <a href="{{route('admin.subscribers.index')}}">
                    <i class="fa fa-file {{request()->is('admin/subscribers/*') ? 'fa-spin' : ''}}"></i>
                    <span>Subscribers</span>
                    <span class="pull-right-container">
                </span>
                </a>
            </li>
        @endcan
    </ul>
</li>
@endcanany

