<div class="form-group">
    <label class="col-sm-3 control-label">Title</label>
    <div class="col-sm-6">
        <input type="text" required class="form-control" value="{{isset($warehouseproductCollection) ? $warehouseproductCollection->product_collection_title : old('product_collection_title')  }}" placeholder="Enter the Product Collection Name" name="product_collection_title" required autocomplete="off">
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-3 control-label"> Subtitle</label>
    <div class="col-sm-6">
        <input type="text" required class="form-control" value="{{ isset($warehouseproductCollection) ? $warehouseproductCollection->product_collection_subtitle : old('product_collection_title')  }}" placeholder="Enter the Product Collection Subtitle" name="product_collection_subtitle" autocomplete="off">
    </div>
</div>


<div class="form-group">
    <label  class="col-sm-3 control-label">
        Collection Image <br>
        <small>[Dimension : (width x height) : 1125 x 240]</small>
    </label>
    <div class="col-sm-6">
        <input type="file" class="form-control" name="product_collection_image" {{ !isset($warehouseproductCollection) ? 'required' : ''  }} >
        @if(isset($warehouseproductCollection))
            <br>
            <div class="col-sm-6">
                <img src="{{asset($warehouseproductCollection->uploadFolder.$warehouseproductCollection->product_collection_image)}}" alt="{{$warehouseproductCollection->product_collection_title}}" width="100%" height="100%">
            </div>
        @endif
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-3 control-label"> Collection Remarks</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{ isset($warehouseproductCollection) ? $warehouseproductCollection->remarks : old('remarks')  }}" placeholder="Enter the Product Collection Remarks" name="remarks" autocomplete="off">
    </div>
</div>
