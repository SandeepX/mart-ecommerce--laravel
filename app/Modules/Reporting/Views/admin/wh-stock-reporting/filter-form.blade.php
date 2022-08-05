<form id="filter_form" method="get">

    <div class="col-md-3">
        <div class="form-group">
            <label for="warehouse">Warehouses</label>
            <select name="warehouse_code" class="form-control select2" id="warehouse">
                @foreach($warehouses as $warehouse)
                    <option value="{{$warehouse->warehouse_code}}" {{$warehouse->warehouse_code == $filterParameters['warehouse_code'] ? 'selected' :''}}>
                        {{ucwords($warehouse->warehouse_name)}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-lg-4 col-md-4">
        <div class="form-group">
            <label for="vendor">Vendor <button class="btn btn-danger btn-xs" id="reset-vendor">Reset</button></label>
            <select id="vendor" name="vendor_code" class="form-control select2" multiple>
            </select>
        </div>
    </div>

    <div class=" col-lg-5 col-md-5">
        <div class="form-group">
            <label for="product">Product <button class="btn btn-danger btn-xs" id="reset-product">Reset</button></label>
            <select id="product" name="product_code" class="form-control select2">
                <option value="" disabled selected>Select Product</option>
            </select>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="stock_action">Stock Action</label>
            <select name="stock_action" class="form-control select2" id="stock_action">
                <option value="">All</option>
                @foreach($stockActions as $stockAction)
                    <option value="{{$stockAction}}" {{$stockAction == $filterParameters['stock_action'] ? 'selected' :''}}>
                        {{ucwords($stockAction)}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="start_date">Start Date</label>
            <input type="date" class="form form-control" name="start_date" id="start_date" value="{{$filterParameters['start_date']}}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="end_date">End Date</label>
            <input type="date" class="form form-control" name="end_date" id="end_date" value="{{$filterParameters['end_date']}}">
        </div>
    </div>
    <div class="col-md-3" style="margin-top: 25px">
        <button type="submit" class="btn btn-sm btn-primary form-control">Filter</button>
    </div>
</form>
