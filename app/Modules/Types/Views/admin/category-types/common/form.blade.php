<div class="form-group">
    <label class="col-sm-2 control-label">Category Type Name</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{isset($categoryType) ? $categoryType->category_type_name : old('category_type_name')  }}" placeholder="Enter the Category Type Name" name="category_type_name" required autocomplete="off">
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-2 control-label"> Remarks</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{ isset($categoryType) ? $categoryType->remarks : old('remarks')  }}" placeholder="Enter the Category Type Remarks" name="remarks"  autocomplete="off">
    </div>
</div>