<div class="col-xs-12 col-md-3">
    <div class="form-group">
        <label for="warehouse_name">Warehouse Name</label>
        <input type="text" class="form-control" name="source_warehouse_name" id="warehouse_name" value="{{ $filterParameters['source_warehouse_name'] }}">
    </div>
</div>
<div class="col-xs-12 col-md-3">
    <div class="form-group">
        <label for="delivery_status">Delivery Status</label>
        <input type="text" class="form-control" name="delivery_status" id="delivery_status" value="{{$filterParameters['delivery_status']}}">
    </div>
</div>
<div class="col-xs-12 col-md-3">
    <div class="form-group">
        <label for="transaction_date_from">Transaction Date From</label>
        <input type="date" class="form-control" name="transaction_date_from" id="transaction_date_from" value="{{$filterParameters['transaction_date_from']}}">
    </div>
</div>
<div class="col-xs-12 col-md-3">
    <div class="form-group">
        <label for="transaction_date_to">Transaction Date From</label>
        <input type="date" class="form-control" name="transaction_date_to" id="transaction_date_to" value="{{$filterParameters['transaction_date_to']}}">
    </div>
</div>