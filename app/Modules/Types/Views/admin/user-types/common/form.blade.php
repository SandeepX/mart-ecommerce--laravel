<div class="form-group">
    <label class="col-sm-2 control-label">User Type Name</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{isset($userType) ? $userType->user_type_name : old('user_type_name')  }}" placeholder="Enter the User Type Name" name="user_type_name" required autocomplete="off">
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-2 control-label">User Type Remarks</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{ isset($userType) ? $userType->remarks : old('remarks')  }}" placeholder="Enter the User Type Remarks" name="remarks"  autocomplete="off">
    </div>
</div>