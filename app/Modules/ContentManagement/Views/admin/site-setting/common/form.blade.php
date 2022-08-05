<div class="form-group">
    <label class="col-sm-2 control-label">Content</label>
    <div class="col-sm-6">
        <input type="hidden" value="{{ $sitePage->content_type }}" name="content_type" >
        <textarea class="summernote" name="content" rows="5" cols="90">{{ $sitePage->content }}</textarea>
    </div>
</div>

