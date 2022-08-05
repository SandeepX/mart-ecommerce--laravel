<form id="filter_form" action="{{ route('admin.warehouse-pre-orders.finalized-store-orders.in-vendor',[
    'vendorCode'=>$vendors->vendor_code,
    'warehousePreOrderListingCode'=>$warehousePreOrderListingCode
]) }}" method="GET">
    <div class="row">
        <div class="col-xs-6">
            <div class="form-group">
                <label for="vendor_code">Vendor</label>
                <select name="vendor_code" class="form-control select2" id="vendor_code">
                    <option value="">Select Vendor</option>
                    <option value="{{$vendors->vendor_code}}"
                        {{$vendors->vendor_code == $filterParameters['vendor_code'] ?'selected' :''}}>
                        {{ucwords($vendors->vendor_name)}}
                    </option>

                </select>
            </div>
        </div>
        <div class="col-xs-6">
            <div class="form-group">
                <label for="product_code">Product Name</label>
                <select class="form-control select2"  id="product_code" name="product_code" onchange="productChange()">
                    <option selected value="" >--Select An Option--</option>

                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-6">
            <div class="form-group">
                <label for="product_variant_code">Product Varient</label>
                <select name="product_variant_code" class="form-control select2" id="product_variant_code" autocomplete="off">
                    <option selected value="" >--Select An Option--</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="form-group">
            <button type="submit" class="btn btn-block btn-primary form-control">Filter</button>
        </div>
    </div>
    </div>
</form>
