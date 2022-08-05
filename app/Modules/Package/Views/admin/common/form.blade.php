<div class="form-group">
    <label class="col-sm-2 control-label">Package Name</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{isset($packageType) ? $packageType->package_name : old('packageType_name')  }}" placeholder="Enter the Package Name" name="package_name" required autocomplete="off">
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-2 control-label">Package Remarks</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{ isset($packageType) ? $packageType->remarks : old('remarks')  }}" placeholder="Enter the Package Remarks" name="remarks" autocomplete="off">
    </div>
</div>