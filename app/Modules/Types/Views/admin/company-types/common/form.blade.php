<div class="form-group">
    <label class="col-sm-2 control-label">Company Type Name</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{isset($companyType) ? $companyType->company_type_name : old('company_type_name')  }}" placeholder="Enter the Company Type Name" name="company_type_name" required autocomplete="off">
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-2 control-label">Company Type Remarks</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{ isset($companyType) ? $companyType->remarks : old('remarks')  }}" placeholder="Enter the Company Type Remarks" name="remarks"  autocomplete="off">
    </div>
</div>