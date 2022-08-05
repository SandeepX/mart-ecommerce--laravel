<form id="filter_form" action="{{ route('warehouse.warehouse-pre-orders.store-orders',$filterParameters['warehouse_preorder_listing_code']) }}" method="GET">

    <div class="row">
        <div class="col-lg-3 col-md-3">
            <div class="form-group">
                <label for="store_preorder_code">Store pre-order Code</label>
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
                    <option value="" {{$filterParameters['status'] == '' ? 'selected' : ''}}>All</option>
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
                    <option value="" {{$filterParameters['payment_status'] == " " ? 'selected' : ''}}>All</option>
                    <option value="1" {{isset($filterParameters['payment_status']) && $filterParameters['payment_status'] == 1 ? 'selected' : ''}}>Paid</option>
                    <option value="0" {{isset($filterParameters['payment_status']) && $filterParameters['payment_status'] == 0 ? 'selected' : ''}}>Unpaid</option>

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
                <button type="submit" class="btn btn-block btn-primary form-control">View Orders</button>
            </div>
        </div>
    </div>
</form>
