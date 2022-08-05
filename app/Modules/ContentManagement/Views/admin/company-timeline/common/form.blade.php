<div class="form-group">
    <label class="col-sm-2 control-label">Year</label>
    <div class="col-sm-6">
        <input type="text" name="year" class="form-control yearpicker" value="{{isset($companyTimeline) ? $companyTimeline->year : old('year')  }}"  required autocomplete="off">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">Title</label>
    <div class="col-sm-6">
        <input type="text" name="title" class="form-control" value="{{isset($companyTimeline) ? $companyTimeline->title : old('title')  }}"  required autocomplete="off">
    </div>
</div>
<div class="form-group">
    <label  class="col-sm-2 control-label">Description</label>
    <div class="col-sm-6">
        <textarea name="description" class="summernote" id="" required cols="90" rows="5"> {{ isset($companyTimeline) ? $companyTimeline->description : ''}} </textarea>
    </div>
</div>


<div class="form-group">
    <label  class="col-sm-2 control-label">Is Active</label>
    <div class="col-sm-6">
        <label class="switch">
            <input type="checkbox" value="1" {{ isset($companyTimeline) ? $companyTimeline->is_active == 1 ? 'checked' : '' : '' }} {{ old('is_active') == 1 ? 'checked' : '' }} name="is_active" >
            <span class="slider round"></span>
        </label>
    </div>
</div>
