<div class="form-group">
    <label class="col-sm-2 control-label">Bank Name</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{isset($bank) ? $bank->bank_name : old('bank_name')  }}" placeholder="Enter the Bank Name" name="bank_name" required autocomplete="off">
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-2 control-label">Bank Logo</label>
    <div class="col-sm-6">
        <input type="file" class="form-control" name="bank_logo" {{ !isset($bank) ? 'required' : ''  }} >
    </div>
</div>


<div class="form-group">
    <label  class="col-sm-2 control-label">Bank Remarks</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{ isset($bank) ? $bank->remarks : old('remarks')  }}" placeholder="Enter the Bank Remarks" name="remarks"  autocomplete="off">
    </div>
</div>