
<form id="filter_form" action="{{ route('admin.warehouse-wise.current-stock.detail',[
    'warehouseCode'=>$warehouse->warehouse_code,
    'vendorCode'=>$vendor->vendor_code
]) }}" method="GET">
    <div class="row">
        <div class="col-xs-12">
            <div class="form-group">
                <label for="product_name">Product</label>
                <input type="text" class="form-control" name="product_name" id="product_name"
                       value="{{$filterParameters['product_name']}}">
            </div>
        </div>
        <div class="col-xs-12">
            <div class="form-group">
                <button type="submit" class="btn btn-block btn-primary form-control">Filter</button>
            </div>
        </div>
    </div>
</form>
