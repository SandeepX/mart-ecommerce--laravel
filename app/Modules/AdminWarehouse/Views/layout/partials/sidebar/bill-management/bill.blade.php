@if(auth()->user()->isWarehouseAdminOrUser())
    @can(['View Bill Merge Master List'])
    <li class=" {{request()->routeIs('warehouse.bill-merge.index')? 'active' : ''}}">
        <a href="{{route('warehouse.bill-merge.index')}}">
            <i class="fa fa-cart-plus"></i>
            <span>Bill Management</span>
        </a>
    </li>
    @endcan
@endif

