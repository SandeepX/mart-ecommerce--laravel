@canany([
  'View Purchased Stock Lists',
  'View Sales Stock Lists'
])
    <li class="
{{
   request()->routeIs('admin.inventory.*') ||
   request()->routeIs('admin.inventory.sales.*')||
   request()->routeIs('admin.inventory.current-stock.*')

? 'active' : ''
}}
        treeview
">
        <a href="#">
            <i class="fa fa-cart-plus"></i>
            <span>Inventory Management </span>
            <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
        </a>
        <ul class="treeview-menu">
            @can('View Purchased Stock Lists')
                <li class="{{request()->routeIs('admin.inventory.purchased-stock.*') ? 'active' : ''}} treeview">
                    <a href="{{route('admin.inventory.purchased-stock.index')}}">
                        <i class="fa fa-file {{request()->routeIs('admin.inventory.purchased-stock.*') ? 'fa-spin' : ''}}"></i> <span>Store Purchase Record</span>
                        <span class="pull-right-container">
                        </span>
                    </a>
                </li>
            @endcan

            @can('View Sales Stock Lists')
                <li class="{{request()->routeIs('admin.inventory.sales.*') ? 'active' : ''}} treeview">
                    <a href="{{route('admin.inventory.sales.index')}}">
                        <i class="fa fa-file {{request()->routeIs('admin.inventory.sales.*') ? 'fa-spin' : ''}}"></i> <span>Store Sales Record</span>
                        <span class="pull-right-container">
                    </span>
                    </a>
                </li>
            @endcan

                @can('View Current Stock Lists')
                <li class="{{request()->routeIs('admin.inventory.current-stock.*') ? 'active' : ''}} treeview">
                    <a href="{{route('admin.inventory.current-stock.index')}}">
                        <i class="fa fa-file {{request()->routeIs('admin.inventory.current-stock.*') ? 'fa-spin' : ''}}"></i> <span>Current Stock Record</span>
                        <span class="pull-right-container">
                    </span>
                    </a>
                </li>
            @endcan
        </ul>
    </li>
@endcanany




