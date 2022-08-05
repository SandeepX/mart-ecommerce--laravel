<div class="form-group">
    <label class="col-sm-2 control-label">Page Image</label>
    <div class="col-sm-6">


        @if(isset($vision->page_image))
            <img src="{{asset('uploads/contentManagement/page/'.$vision->page_image)}}"
                 alt="Vision Mission Image" width="600" height="250">
        @endif
        <input type="file" class="form-control" {{old('page_image')  }}  name="page_image"   autocomplete="off">
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-2 control-label">Vision Description</label>
    <div class="col-sm-6">
        <textarea name="vision_description" class="summernote" id="" required cols="90" rows="5"> {{ isset($vision) ? $vision->vision_description : ''}} </textarea>
    </div>
</div>
<div class="form-group">
    <label  class="col-sm-2 control-label">Mission Description</label>
    <div class="col-sm-6">
        <textarea name="mission_description" class="summernote" id="" required cols="90" rows="5"> {{ isset($vision) ? $vision->mission_description : ''}} </textarea>
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-2 control-label">Is Active</label>
    <div class="col-sm-6">
        <label class="switch">
            <input type="checkbox" value="1" {{ isset($vision) ? $vision->is_active == 1 ? 'checked' : '' : '' }} {{ old('is_active') == 1 ? 'checked' : '' }} name="is_active" >
            <span class="slider round"></span>
        </label>
    </div>
</div>
