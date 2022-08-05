<div class="form-group">
    <label class="col-sm-3 control-label">Title</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{isset($productCollection) ? $productCollection->product_collection_title : old('product_collection_title')  }}" placeholder="Enter the Product Collection Name" name="product_collection_title" required autocomplete="off">
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-3 control-label"> Subtitle</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{ isset($productCollection) ? $productCollection->product_collection_subtitle : old('product_collection_title')  }}" placeholder="Enter the Product Collection Subtitle" name="product_collection_subtitle" autocomplete="off">
    </div>
</div>


<div class="form-group">
    <label  class="col-sm-3 control-label">
      Collection Image <br>
       <small>[Dimension : (width x height) : 1125 x 240]</small>
     </label>
    <div class="col-sm-6">
        <input type="file" class="form-control" name="product_collection_image" {{ !isset($productCollection) ? 'required' : ''  }} >
        @if(isset($productCollection))
        <br>
        <div class="col-sm-6">
        <img src="{{asset($productCollection->uploadFolder.$productCollection->product_collection_image)}}" alt="{{$productCollection->product_collection_title}}" width="100%" height="100%">
        </div>
        @endif
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-3 control-label"> Collection Remarks</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{ isset($productCollection) ? $productCollection->remarks : old('remarks')  }}" placeholder="Enter the Product Collection Remarks" name="remarks" autocomplete="off">
    </div>
</div>