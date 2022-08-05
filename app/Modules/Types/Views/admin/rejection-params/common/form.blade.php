<div class="form-group">
    <label class="col-sm-2 control-label">Rejection Name</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{isset($rejectionParam) ? $rejectionParam->rejection_name : old('rejection_name')  }}" placeholder="Enter the Rejection Name" name="rejection_name" required autocomplete="off">
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-2 control-label">Rejection Remarks</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{ isset($rejectionParam) ? $rejectionParam->remarks : old('remarks')  }}" placeholder="Enter the Rejection  Remarks" name="remarks"  autocomplete="off">
    </div>
</div>