

<div class="form-group">
    <label  class="col-sm-2 control-label">Meta Title</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{ isset($seoSetting) ? $seoSetting->meta_title : old('meta_title')  }}" placeholder="Enter the Meta Title" name="meta_title"  autocomplete="off">
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Meta Description</label>
    <div class="col-sm-6">
        <textarea name="meta_description" rows="4" cols="90">
            {{ isset($seoSetting) ? $seoSetting->meta_description : old('meta_description') }}
        </textarea>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Keywords</label>
    <div class="col-sm-6">

        <input type="text" id="keywords" data-role="tagsinput" name="keywords" value="{{ isset($seoSetting) ? implode(',', json_decode($seoSetting->keywords)) : old('keywords') }}">
        {{-- <textarea  data-role="tagsinput" name="keywords" rows="4" cols="90">
            {{ isset($seoSetting) ? $seoSetting->keywords : old('keywords') }}
        </textarea> --}}
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-2 control-label">Revisit After (Days)</label>
    <div class="col-sm-6">
        <input type="number" class="form-control" value="{{ isset($seoSetting) ? $seoSetting->revisit_after : old('revisit_after')  }}" placeholder="Enter Revisit After value" name="revisit_after"  autocomplete="off">
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-2 control-label">Author</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{ isset($seoSetting) ? $seoSetting->author : old('author')  }}" placeholder="Enter the Author" name="author"  autocomplete="off">
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-2 control-label">Sitemap Link</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{ isset($seoSetting) ? $seoSetting->sitemap_link : old('sitemap_link')  }}" placeholder="Enter the Sitemap Link" name="sitemap_link"  autocomplete="off">
    </div>
</div>

