<div class="form-group">
    <label class="col-sm-2 control-label">Image</label>
    <div class="col-sm-6">


        @if(isset($brandSlider->image))
            <img src="{{asset('uploads/brand/slider/'.$brandSlider->image)}}"
                 alt=BrandSlider width="400" height="100">
        @endif
        <input type="file" class="form-control" {{old('image')  }}  name="image"   autocomplete="off">
    </div>
</div>
<div class="form-group">
    <div class="col-sm-6">
        <input type="hidden" class="form-control" value="{{$brand->brand_code}}"  name="brand_code"   autocomplete="off">
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-2 control-label">Description</label>
    <div class="col-sm-6">
        <textarea name="description" class="summernote" id="" required cols="90" rows="5"> {{ isset($brandSlider) ? $brandSlider->description : ''}} </textarea>
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-2 control-label">Is Active</label>
    <div class="col-sm-6">
        <label class="switch">
            <input type="checkbox" value="1" {{ isset($brandSlider) ? $brandSlider->is_active == 1 ? 'checked' : '' : '' }} {{ old('is_active') == 1 ? 'checked' : '' }} name="is_active" >
            <span class="slider round"></span>
        </label>
    </div>
</div>
