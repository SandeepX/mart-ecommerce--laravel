@if(auth()->user()->isWarehouseAdminOrUser())
    @canany(['View WH Product Collection List'])
    <li class=" {{request()->routeIs('warehouse.warehouse-product-collections.*')? 'active' : ''}} treeview">
        <a href="#">
            <i class="fa fa-cart-plus"></i>
            <span>Collection Management</span>
            <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
        </a>
        <ul class="treeview-menu">
            @can('View WH Product Collection List')
            <li class="{{request()->routeIs('warehouse.warehouse-product-collections.index') ? 'active' : ''}} treeview">
                <a href="{{route('warehouse.warehouse-product-collections.index')}}">
                    <i class="fa fa-file {{request()->routeIs('warehouse.warehouse-product-collections.index') ? 'fa-spin' : ''}}"></i>
                    <span>Product Collection</span>
                    <span class="pull-right-container">
                 </span>
                </a>
            </li>
            @endcan
        </ul>
    </li>
    @endcanany
@endif

