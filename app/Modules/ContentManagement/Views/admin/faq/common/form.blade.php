<div class="form-group">
    <label class="col-sm-2 control-label">Question</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{isset($faq) ? $faq->question : old('question')  }}" placeholder="Enter Question" name="question" required autocomplete="off">
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-2 control-label">Answer</label>
    <div class="col-sm-6">
        <textarea name="answer" id="" cols="90" rows="5"> {{ isset($faq) ? $faq->answer : ''}} </textarea>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Priority</label>
    <div class="col-sm-6">
        <input type="number" class="form-control" value="{{isset($faq) ? $faq->priority : old('priority')  }}" placeholder="Enter Priority" name="priority" required autocomplete="off">
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-2 control-label">Is Active</label>
    <div class="col-sm-6">
        <label class="switch">
            <input type="checkbox" value="1" {{ isset($faq) ? $faq->is_active == 1 ? 'checked' : '' : '' }} {{ old('is_active') == 1 ? 'checked' : '' }} name="is_active" >
            <span class="slider round"></span>
        </label>
    </div>
</div>