<div class="form-group">
    <label class="col-sm-2 control-label">Cancellation Name</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{isset($cancellationParam) ? $cancellationParam->cancellation_name : old('cancellation_name')  }}" placeholder="Enter the Cancellation Name" name="cancellation_name" required autocomplete="off">
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-2 control-label">Cancellation Remarks</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{ isset($cancellationParam) ? $cancellationParam->remarks : old('remarks')  }}" placeholder="Enter the Cancellation Remarks" name="remarks"  autocomplete="off">
    </div>
</div>