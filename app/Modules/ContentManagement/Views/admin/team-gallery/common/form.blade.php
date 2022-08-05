<div class="form-group">
    <label class="col-sm-2 control-label">Image</label>
    <div class="col-sm-6">


        @if(isset($teamGallery->image))
            <img src="{{asset('uploads/contentManagement/team-gallery/'.$teamGallery->image)}}"
                 alt="Team Gallery Image" width="50" height="50">
        @endif
        <input type="file" class="form-control" {{old('image')  }}  name="image"   autocomplete="off">
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-2 control-label">Description</label>
    <div class="col-sm-6">
        <textarea name="description" class="summernote" required id="" cols="90" rows="5"> {{ isset($teamGallery) ? $$teamGallery->department : ''}} </textarea>
    </div>
</div>
<div class="form-group">
    <label  class="col-sm-2 control-label">Is Active</label>
    <div class="col-sm-6">
        <label class="switch">
            <input type="checkbox" value="1" {{ isset($teamGallery) ? $teamGallery->is_active == 1 ? 'checked' : '' : '' }} {{ old('is_active') == 1 ? 'checked' : '' }} name="is_active" >
            <span class="slider round"></span>
        </label>
    </div>
</div>
