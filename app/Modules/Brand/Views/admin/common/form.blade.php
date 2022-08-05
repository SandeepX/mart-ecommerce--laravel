<div class="form-group">
    <label class="col-sm-2 control-label">Brand Name</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{isset($brand) ? $brand->brand_name : old('brand_name')  }}" placeholder="Enter the Brand Name" name="brand_name" required autocomplete="off">
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-2 control-label">Brand Logo</label>
    <div class="col-sm-6">
        <input type="file" class="form-control" name="brand_logo" {{ !isset($brand) ? 'required' : ''  }} >
    </div>
</div>


<div class="form-group">
    <label  class="col-sm-2 control-label">Brand Remarks</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{ isset($brand) ? $brand->remarks : old('remarks')  }}" placeholder="Enter the Brand Remarks" name="remarks"  autocomplete="off">
    </div>
</div>
<div class="form-group">
    <label  class="col-sm-2 control-label">Is Featured</label>
    <div class="col-sm-6">
        <input type="checkbox" value="1" {{isset($brand)? $brand->is_featured == 1 ? 'checked': '':''}} {{ old('is_active') == 1 ? 'checked' : '' }} name="is_featured" >
    </div>
</div>
