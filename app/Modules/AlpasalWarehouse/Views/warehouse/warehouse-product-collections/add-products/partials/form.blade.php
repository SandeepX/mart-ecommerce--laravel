<!-- <div class="form-group">
    <label class="col-sm-2 control-label">Brand</label>
    <div class="col-sm-6">
       <select class="form-control" name="brand_code">
         <option value="" selected disabled>- Select a brand -</option>
         {{--@foreach($brands as $brand)
         <option value="{{$brand->brand_code}}">{{$brand->brand_name}}</option>
         @endforeach--}}
       </select>
    </div>
</div> -->

<div class="form-group">
    <label  class="col-sm-2 control-label"> Products</label>
    <div class="col-sm-6">
    <select required class="select2 form-control" name="product_codes[]" multiple>

         @foreach($warehouseproducts as $warehouseproduct)
         <option value="{{$warehouseproduct->warehouse_product_master_code}}">{{$warehouseproduct->product->product_name}}</option>
         @endforeach
       </select>
    </div>
</div>

