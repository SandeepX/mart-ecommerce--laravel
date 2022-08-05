<div class="form-group">
    <label class="col-sm-2 control-label">File Name</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{isset($promotionLink) ? $promotionLink->filename : old('filename')}}" placeholder="Enter the File Name" name="filename" autocomplete="off">
    </div>
</div>
<div class="form-group">
    <label  class="col-sm-2 control-label">File</label>
    <div class="col-sm-6">
        <input type="file" class="form-control" name="file">
        @if(isset($promotionLink->file))
            <a target="_blank" href="{{asset($promotionLink->getPromotionFileUploadPath().$promotionLink->file)}}" alt="your image" width="auto" height="100px" style="object-fit: cover;">
                {{ $promotionLink->file}}
            </a>
        @endif
    </div>
</div>
<div class="form-group">
    <label  class="col-sm-2 control-label"><a href="#" data-toggle="tooltip" data-html = "true" data-placement="Sources" title="<h5>This is used for you url name</h5>">
            <i class="fa fa-info-circle"></i>
        </a>Link Code</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{isset($promotionLink) ? $promotionLink->link_code :old('link_code')}}" placeholder="Enter Link Code" name="link_code"  autocomplete="off" required>
    </div>
</div>
<div class="form-group">
    <label  class="col-sm-2 control-label">Title *</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{isset($promotionLink) ? $promotionLink->title :old('title')}}" placeholder="Enter Title" name="title" required  autocomplete="off" required>
    </div>
</div>
<div class="form-group">
    <label  class="col-sm-2 control-label">Description</label>
    <div class="col-sm-6">
       <textarea class="form-control summernote" name="description">{!! isset($promotionLink) ? $promotionLink->description :old('description') !!}</textarea>
    </div>
</div>
<div class="form-group">
    <label  class="col-sm-2 control-label">Image</label>
    <div class="col-sm-6">
        <input type="file" class="form-control" name="image">
        @if(isset($promotionLink->image))
            <a target="_blank" href="{{asset($promotionLink->getPromotionImageUploadPath().$promotionLink->image)}}" alt="your image" width="auto" height="100px" style="object-fit: cover;">
                {{ $promotionLink->image}}
            </a>
        @endif
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">Meta Details</div>
    <div class="panel-body">
        <div class="form-group">
            <label  class="col-sm-2 control-label">OG Title</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" value="{{isset($promotionLink) ? $promotionLink->og_title :old('og_title')}}" placeholder="Enter OG Title" name="og_title"  autocomplete="off">
            </div>
        </div>
        <div class="form-group">
            <label  class="col-sm-2 control-label">OG Description</label>
            <div class="col-sm-6">
                <textarea class="form-control" name="og_description">{{isset($promotionLink) ? $promotionLink->og_description : old('og_description')}}</textarea>
            </div>
        </div>
        <div class="form-group">
            <label  class="col-sm-2 control-label">OG Image</label>
            <div class="col-sm-6">
                <input type="file" class="form-control" name="og_image" >
                @if(isset($promotionLink->og_image))
                    <br/>
                    <img id="image_preview" src="{{asset($promotionLink->getOGImageUploadPath().$promotionLink->og_image)}}" alt="your image" width="auto" height="100px" style="object-fit: cover;" />
                @endif
            </div>
        </div>
    </div>
</div>


