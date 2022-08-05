<div class="form-group">
    <label class="col-sm-2 control-label">Store Size Name</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{isset($storeSize) ? $storeSize->store_size_name : old('store_size_name')  }}" placeholder="Enter the Store Size Name" name="store_size_name" required autocomplete="off">
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-2 control-label"> Remarks</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{ isset($storeSize) ? $storeSize->remarks : old('remarks')  }}" placeholder="Enter the Store Size Remarks" name="remarks"  autocomplete="off">
    </div>
</div>