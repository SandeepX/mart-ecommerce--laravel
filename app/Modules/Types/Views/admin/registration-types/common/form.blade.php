<div class="form-group">
    <label class="col-sm-2 control-label">Registration  Type Name</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{isset($registrationType) ? $registrationType->registration_type_name : old('registration_type_name')  }}" placeholder="Enter the Company RegistrationType Name" name="registration_type_name" required autocomplete="off">
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-2 control-label"> Remarks</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{ isset($registrationType) ? $registrationType->remarks : old('remarks')  }}" placeholder="Enter the Company Registration Type Remarks" name="remarks"  autocomplete="off">
    </div>
</div>