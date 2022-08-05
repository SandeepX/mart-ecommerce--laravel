<div class="form-group">
    <label class="col-sm-2 control-label">Sensitivity Name</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{isset($productSensitivity) ? $productSensitivity->sensitivity_name : old('sensitivity_name')  }}" placeholder="Enter the Sensitivity Name" name="sensitivity_name" required autocomplete="off">
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-2 control-label">Sensitivity Remarks</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{ isset($productSensitivity) ? $productSensitivity->remarks : old('remarks')  }}" placeholder="Enter the Sensitivity Remarks" name="remarks" autocomplete="off">
    </div>
</div>