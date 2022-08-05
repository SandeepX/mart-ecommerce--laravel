<form id="filter_form" action="{{ route($base_route.'.index',['warehousePreOrderListingCode'=>$warehousePreOrder->warehouse_preorder_listing_code]) }}" method="GET">

    <div class="row">
        <div class="col-lg-3 col-md-3">
            <div class="form-group">
                <label for="store_preorder_code">Store Order Code</label>
                <input type="text" class="form-control" name="store_preorder_code" id="store_preorder_code"
                       value="{{$filterParameters['store_preorder_code']}}">
            </div>
        </div>
        <div class="col-lg-3 col-md-3">
            <div class="form-group">
                <label for="store_name">Store</label>
                <input type="text" class="form-control" name="store_name" id="store_name"
                       value="{{$filterParameters['store_name']}}">
            </div>
        </div>
        <div class="col-lg-3 col-md-3">
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" class="form-control select2" id="status">
                    <option value="" {{$filterParameters['status'] == ''}}>All</option>
                    @foreach($storePreOrderStatuses as $key=>$status)
                        <option value="{{$status}}"
                            {{$status == $filterParameters['status'] ?'selected' :''}}>
                            {{ucwords($status)}}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-lg-3 col-md-3">
            <div class="form-group">
                <label for="payment_status">Payment Status</label>
                <select name="payment_status" class="form-control select2" id="payment_status">
                    <option value="">All</option>
                   <option value='unpaid'  {{($filterParameters['payment_status']===0 ? 'selected' : '')}}>Unpaid</option>
                   <option value='paid' {{($filterParameters['payment_status'] ? 'selected' : '')}}>Paid</option>
                </select>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-lg-3 col-md-3">
            <div class="form-group">
                <label for="start_time">Start Time</label>
                <input type="date" class="form-control" name="start_time" id="start_time"
                       value="{{$filterParameters['start_time']}}">
            </div>
        </div>

        <div class="col-lg-3 col-md-3">
            <div class="form-group">
                <label for="end_time">End Time</label>
                <input type="date" class="form-control" name="end_time" id="end_time"
                       value="{{$filterParameters['start_time']}}">
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
                <button type="submit" class="btn btn-block btn-primary form-control">View Pre Orders</button>
            </div>
        </div>
    </div>
</form>
