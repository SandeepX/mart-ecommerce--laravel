
<li class=" {{request()->routeIs('admin.promotion-links.*')   ? 'active' : ''}} treeview">
    <a href="#">
        <i class="fa fa-cart-plus"></i>
        <span>Digital Marketing</span>
        <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
    </a>
    <ul class="treeview-menu">
        <li class="{{request()->routeIs('admin.promotion-links.*') ? 'active' : ''}} treeview">
            <a href="{{route('admin.promotion-links.index')}}">
                <i class="fa fa-home {{request()->routeIs('admin.promotion-links.index') ? 'fa-spin' : ''}}"></i> <span>Promotion Links</span>
                <span class="pull-right-container">
                       </span>
            </a>
        </li>

    </ul>


</li>
