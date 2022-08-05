<form id="filter_form" action="{{ route('warehouse.store.orders.lists', $storeCode) }}" method="GET">

    <div class="row">
        <div class="col-lg-3 col-md-3">
            <div class="form-group">
                <label for="store_order_code">Store Order Code</label>
                <input type="text" class="form-control" name="store_order_code" id="store_order_code"
                       value="{{$filterParameters['store_order_code']}}">
            </div>
        </div>
        <div class="col-lg-3 col-md-3">
            <div class="form-group">
                <label for="delivery_status">Delivery Status</label>
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
        <div class="col-lg-3 col-md-3">
            <div class="form-group">
                <label for="payment_status">Payment Status</label>
                <select name="payment_status" class="form-control select2" id="payment_status">
                    <option value="" {{$filterParameters['payment_status'] == ''}}>All</option>
                    @foreach($paymentStatuses as $paymentStatus)
                        <option value="{{$paymentStatus}}"
                                {{$paymentStatus == $filterParameters['payment_status'] ?'selected' :''}}>
                            {{ucwords($paymentStatus)}}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

    </div>
    <div class="row">


        <div class="col-lg-3 col-md-3">
            <div class="form-group">
                <label for="order_date_from">Order Date From</label>
                <input type="date" class="form-control" name="order_date_from" id="order_date_from"
                       value="{{$filterParameters['order_date_from']}}">
            </div>

        </div>

        <div class="col-lg-3 col-md-3">
            <div class="form-group">
                <label for="order_date_to">Order Date To</label>
                <input type="date" class="form-control" name="order_date_to" id="order_date_to"
                       value="{{$filterParameters['order_date_to']}}">
            </div>
        </div>

        <div class="col-lg-3 col-md-3">
            <div class="form-group">
                <label for="price_condition">Price Condition</label>
                <select name="price_condition" class="form-control select2" id="price_condition">
                    <option value="" {{$filterParameters['price_condition'] == ''}}>All</option>
                    @foreach($priceConditions as $key=>$priceCondition)
                        <option value="{{$priceCondition}}"
                                {{$priceCondition == $filterParameters['price_condition'] ?'selected' :''}}>
                            {{ucwords($key)}}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-lg-3 col-md-3">
            <div class="form-group">
                <label for="total_price">Price</label>
                <input type="number" min="0" class="form-control" name="total_price" id="total_price"
                       value="{{$filterParameters['total_price']}}">
            </div>
        </div>
        <div class="col-lg-3 col-md-3">
            <div class="form-group">
                <button type="submit" class="btn btn-block btn-primary form-control">View Orders</button>
            </div>
        </div>
    </div>
</form>
