<div class="form-group">
    <label class="col-sm-2 control-label">Vendor Type Name</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{isset($vendorType) ? $vendorType->vendor_type_name : old('vendor_type_name')  }}" placeholder="Enter the Vendor Type Name" name="vendor_type_name" required autocomplete="off">
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-2 control-label">Vendor Type Remarks</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{ isset($vendorType) ? $vendorType->remarks : old('remarks')  }}" placeholder="Enter the Vendor Type Remarks" name="remarks"  autocomplete="off">
    </div>
</div>