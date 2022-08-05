<form id="filter_form" action="{{ route('warehouse.store.orders.index') }}" method="GET">

    <div class="row">
        <div class="col-lg-6 col-md-6">
            <div class="form-group">
                <label for="store_name_code">Store Name/Code</label>
                <input type="text" class="form-control" name="store_name_code" id="store_name_code"
                       value="{{$filterParameters['store_name_code']}}">
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="form-group">
                <label for="delivery_status">Last Order Status</label>
                <select name="delivery_status" class="form-control select2" id="delivery_status">
                    <option value="" {{$filterParameters['delivery_status'] == ''}}>All</option>
                    @foreach($storeOrderDeliveryStatuses as $key=>$deliveryStatus)
                    <option value="{{$deliveryStatus}}"
                            {{$deliveryStatus == $filterParameters['delivery_status'] ?'selected' :''}}>
                    {{$key}}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-lg-6 col-md-6">
            <div class="form-group">
                <label for="order_date_from">Order Date From</label>
                <input type="date" class="form-control" name="order_date_from" id="order_date_from"
                       value="{{$filterParameters['order_date_from']}}">
            </div>

        </div>

        <div class="col-lg-6 col-md-6">
            <div class="form-group">
                <label for="order_date_to">Order Date To</label>
                <input type="date" class="form-control" name="order_date_to" id="order_date_to"
                       value="{{$filterParameters['order_date_to']}}">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-6">
                <div class="form-group">
                    <button type="submit" class="btn btn-block btn-primary form-control">View Stores</button>
                </div>
        </div>
    </div>
</form>
