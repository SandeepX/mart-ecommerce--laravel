<div class="form-group">
    <label class="col-sm-2 control-label">Variant Name</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{isset($variant) ? $variant->variant_name : old('variant_name')  }}" placeholder="Enter the Variant Name" name="variant_name" required autocomplete="off">
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-2 control-label">Variant Remarks</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{ isset($variant) ? $variant->remarks : old('remarks')  }}" placeholder="Enter the Variant Remarks" name="remarks"  autocomplete="off">
    </div>
</div>