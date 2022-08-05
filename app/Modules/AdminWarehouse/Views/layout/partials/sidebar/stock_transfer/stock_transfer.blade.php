@canany(['View WH Stock Transfer List','View Received WH Stock Transfer List','View WH Vendor Wise Current Stock List'])
<li class="
{{
   request()->routeIs('warehouse.stock-transfer.*')? 'active' : ''
}}
        treeview
">
    <a href="#">
        <i class="fa fa-file"></i>
        <span>Stock Management</span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        @can('View WH Stock Transfer List')
        <li class="{{request()->routeIs('warehouse.stock-transfer.index') ? 'active' : ''}} treeview">
            <a href="{{route('warehouse.stock-transfer.index')}}">
                <i class="fa fa-file {{request()->routeIs('warehouse.stock-transfer.index') ? 'fa-spin' : ''}}"></i>
                <span>Stock Product Transfer</span>
                <span class="pull-right-container">
                </span>
            </a>
        </li>
        @endcan
        @can('View Received WH Stock Transfer List')
        <li class="{{request()->routeIs('warehouse.stock-transfer.received-stocks') ? 'active' : ''}} treeview">
            <a href="{{route('warehouse.stock-transfer.received-stocks')}}">
                <i class="fa fa-file {{request()->routeIs('warehouse.stock-transfer.received-stocks') ? 'fa-spin' : ''}}"></i>
                <span>Received Stock Transfer</span>
                <span class="pull-right-container">
                </span>
            </a>
        </li>
         @endcan
         @can('View WH Vendor Wise Current Stock List')
        <li class="{{request()->routeIs('warehouse.vendor-wise.current-stock.index') ? 'active' : ''}} treeview">
            <a href="{{route('warehouse.vendor-wise.current-stock.index')}}">
                <i class="fa fa-file {{request()->routeIs('warehouse.vendor-wise.current-stock.index') ? 'fa-spin' : ''}}"></i>
                <span>View Current Stock</span>
                <span class="pull-right-container">
                </span>
            </a>
        </li>
        @endcan
    </ul>
</li>
@endcanany


