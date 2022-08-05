@if(auth()->user()->isWarehouseAdminOrUser())
    @can('View Invoice Setting Lists')
    <li class=" {{request()->routeIs('warehouse.warehouse.settings')? 'active' : ''}}">
        <a href="{{route('warehouse.settings')}}">
            <i class="fa fa-cart-plus"></i>
            <span>Settings</span>
        </a>
    </li>
    @endcan
@endif

