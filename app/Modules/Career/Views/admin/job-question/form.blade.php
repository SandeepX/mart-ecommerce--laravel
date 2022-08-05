
<div class="form-group">
    <label class="col-sm-2 control-label">Question</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{isset($jobQuestion) ? $jobQuestion->question : old('question')  }}" placeholder="Enter the question" name="question" required>
    </div>
</div>

<div class="form-group">
    <label for="status" class="col-sm-2 control-label">Active</label>
    <div class="col-sm-6">
        <input id="status" type="checkbox" @if((isset($jobQuestion) && $jobQuestion->is_active) ||old('status') ) checked @endif
               name="status">
    </div>
</div>

