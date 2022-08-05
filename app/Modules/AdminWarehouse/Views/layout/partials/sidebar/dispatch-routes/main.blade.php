@if(auth()->user()->isWarehouseAdminOrUser())
        <li class=" {{request()->routeIs('warehouse.dispatch-route.lists')? 'active' : ''}}">
            <a href="{{route('warehouse.dispatch-route.lists')}}">
                <i class="fa fa-cart-plus"></i>
                <span>Dispatch Routes</span>
            </a>
        </li>
@endif

