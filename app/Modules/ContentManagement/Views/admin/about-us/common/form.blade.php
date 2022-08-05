<div class="form-group">
    <label class="col-sm-2 control-label">Page Image</label>
    <div class="col-sm-6">


        @if(isset($aboutUs->page_image))
            <img src="{{asset('uploads/contentManagement/page/'.$aboutUs->page_image)}}"
                 alt="Vendor Logo" width="50" height="50">
        @endif
        <input type="file" class="form-control" {{old('page_image')  }}  name="page_image"   autocomplete="off">
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Company Name</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{isset($aboutUs) ? $aboutUs->company_name : old('company_name')  }}" placeholder="Enter Company Name" name="company_name" required autocomplete="off">
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-2 control-label">Company Description</label>
    <div class="col-sm-6">
        <textarea name="company_description" class="summernote" id="" required cols="90" rows="5"> {{ isset($aboutUs) ? $aboutUs->company_description : ''}} </textarea>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Ceo Name</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{isset($aboutUs) ? $aboutUs->ceo_name : old('ceo_name')  }}" placeholder="Enter CEO Name" name="ceo_name" required autocomplete="off">
    </div>
</div>
<div class="form-group">
    <label  class="col-sm-2 control-label">CEO Message</label>
    <div class="col-sm-6">
        <textarea name="message_from_ceo" class="summernote" required id="" cols="90" rows="5"> {{ isset($aboutUs) ? $aboutUs->message_from_ceo : ''}} </textarea>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">CEO Image</label>
    <div class="col-sm-6">
        @if(isset($aboutUs->ceo_image))
            <img src="{{asset('uploads/contentManagement/ceo/'.$aboutUs->ceo_image)}}"
                 alt="ceo image" width="50" height="50">
        @endif
        <input type="file" class="form-control" value="{{isset($aboutUs) ? $aboutUs->ceo_image : old('ceo_image')  }}"  name="ceo_image"  autocomplete="off">
    </div>
</div>
<div class="form-group">
    <label  class="col-sm-2 control-label">Is Active</label>
    <div class="col-sm-6">
        <label class="switch">
            <input type="checkbox" value="1" {{ isset($aboutUs) ? $aboutUs->is_active == 1 ? 'checked' : '' : '' }} {{ old('is_active') == 1 ? 'checked' : '' }} name="is_active" >
            <span class="slider round"></span>
        </label>
    </div>
</div>
