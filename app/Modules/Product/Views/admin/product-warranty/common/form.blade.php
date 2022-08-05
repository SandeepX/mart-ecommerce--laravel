<div class="form-group">
    <label class="col-sm-2 control-label">Warranty Name</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{isset($productWarranty) ? $productWarranty->warranty_name : old('warranty_name')  }}" placeholder="Enter the Warranty Name" name="warranty_name" required autocomplete="off">
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-2 control-label">Warranty Remarks</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{ isset($productWarranty) ? $productWarranty->remarks : old('remarks')  }}" placeholder="Enter the Warranty Remarks" name="remarks" autocomplete="off">
    </div>
</div>