<form id="filter_form" action="#" method="GET">
    <div class="row">
        <div class="col-lg-4 col-md-4">
            <div class="form-group">
                <label for="warehouse">Warehouse</label>
                 <select id="warehouse" name="warehouse_code" class="form-control select2">
                     @foreach($warehouses as $key => $warehouse)
                         <option value="{{$warehouse->warehouse_code}}" {{($loop->index==0) ? 'selected' : ''}}>{{$warehouse->warehouse_name}}</option>
                     @endforeach
                 </select>
            </div>
        </div>
        <div class="col-lg-4 col-md-4">
            <div class="form-group">
                <label for="store">Store <button class="btn btn-danger btn-xs" id="reset-store">Reset</button></label>
                <select id="store" name="store_code" class="form-control select2">
                    <option value="" disabled selected>Select Connected Stores</option>
                </select>
            </div>
        </div>

        <div class="col-lg-4 col-md-4">
            <div class="form-group">
                <label for="vendor">Vendor <button class="btn btn-danger btn-xs" id="reset-vendor">Reset</button></label>
               <select id="vendor" name="vendor_code[]" class="form-control select2" multiple>
                </select>
            </div>
            <div class="form-group">

            </div>
        </div>
    </div>
    <div class="row">
        <div class=" col-lg-6 col-md-6">
            <div class="form-group">
                <label for="product">Product <button class="btn btn-danger btn-xs" id="reset-product">Reset</button></label>
                <select id="product" name="product_code" class="form-control select2">
                    <option value="" disabled selected>Select Product</option>
                </select>
            </div>
        </div>
        <div class="col-lg-3 col-md-3">
            <div class="form-group">
                <label for="from_date" class="control-label">Order Date From</label>
                <input type="date" class="form-control" name="from_date" id="from_date" value="{{$filterParameters['from_date']}}">
            </div>
        </div>
        <div class="col-lg-3 col-md-3">
            <div class="form-group">
                <label for="to_date">Order Date To</label>
                <input type="date" class="form-control" name="to_date" id="to_date" value="{{$filterParameters['to_date']}}">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-3">
            <div class="form-group">
                <button type="submit" class="btn btn-block btn-primary form-control" style="margin-top: 24px;">Filter</button>
            </div>
        </div>
        <div class="col-lg-3 col-md-3">
            <div class="form-group">
                <a href="{{route('admin.wh-dispatch-report.index')}}"  class="btn btn-block btn-danger form-control" style="margin-top: 24px;">Clear</a>
            </div>
        </div>
    </div>
</form>
