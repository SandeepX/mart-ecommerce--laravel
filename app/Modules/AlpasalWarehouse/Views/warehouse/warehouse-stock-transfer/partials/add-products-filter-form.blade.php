<div class="col-xs-12" style="margin-top: 1rem">
    <label for="warehouse"> Destination Warehouse</label>
    <span>&nbsp;&nbsp;&nbsp;{{ $warehouseStockTransfer->destinationWarehouses->warehouse_name }}</span>
</div>

<div class="col-xs-12">
    <label for="vendor_name">Vendor Name</label>
{{--    <input type="text" id="vendor_name" name="vendor_name" class="form-control"--}}
{{--           placeholder="Vendor name">--}}
    <select class="form-control select2" id="vendor_name" name="vendor_name">
        <option value="" disabled>---Select Vendor---</option>
        @foreach($vendors as $vendor)
            <option value="{{ $vendor->vendor_code }}">{{ $vendor->vendor_name }}</option>
        @endforeach
    </select>
</div>

<div class="col-xs-12" style="margin-top: 1rem">
    <label for="category_code">Category</label>
    <select class="form-control select2" id="category_code" name="category_names[]" multiple="multiple">
        <option value="" disabled>All categories</option>
        @foreach($categories as $category)
            <option value="{{$category->category_code}}">{{$category->category_name}}</option>
        @endforeach
    </select>
</div>

<div class="col-xs-12" style="margin-top: 1rem">

    <label for="brand_code">Brand</label>
    <select class="form-control select2" id="brand_code" name="brand_name">
        <option value="" selected >All Brands</option>
        @foreach($brands as $brand)
            <option value="{{$brand->brand_code}}">{{$brand->brand_name}}</option>
        @endforeach
    </select>
</div>

<div class="col-xs-12" style="margin-top: 1rem">

    <label for="product_name">Product Name</label>
    <input type="text" id="product_name" name="product_name" class="form-control"
           placeholder="Product name">
</div>


<br><br>

<div class="col-xs-12" style="margin-top: 1.5rem">
    <div class="form-group">
        <button type="submit" class="btn btn-block btn-primary form-control">Filter</button>
    </div>
</div>

