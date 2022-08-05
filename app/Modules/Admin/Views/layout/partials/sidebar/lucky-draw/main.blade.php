
    <li class=" {{request()->routeIs('admin.store-lucky-draws.*')   ? 'active' : ''}} treeview">
        <a href="#">
            <i class="fa fa-cart-plus"></i>
            <span>Lucky Draw</span>
            <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
        </a>
        <ul class="treeview-menu">

                <li class="{{request()->routeIs('admin.store-lucky-draws.index') ? 'active' : ''}} treeview">
                    <a href="{{route('admin.store-lucky-draws.index')}}">
                        <i class="fa fa-home {{request()->routeIs('admin.store-lucky-draws.index') ? 'fa-spin' : ''}}"></i> <span>Store LuckyDraw</span>
                        <span class="pull-right-container">
                       </span>
                    </a>
                </li>

        </ul>


    </li>
