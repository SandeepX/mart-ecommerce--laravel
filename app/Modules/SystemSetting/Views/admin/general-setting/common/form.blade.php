

<div class="form-group">
    <label  class="col-sm-2 control-label">Logo</label>
    <div class="col-sm-6">
        <input type="file" class="form-control" name="logo" {{ !isset($generalSetting) ? 'required' : ''  }} >
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Favicon</label>
    <div class="col-sm-6">
        <input type="file" class="form-control" name="favicon" {{ !isset($generalSetting) ? 'required' : ''  }} >
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-2 control-label">Admin Side Bar Logo</label>
    <div class="col-sm-6">
        <input type="file" class="form-control" name="admin_sidebar_logo" {{ !isset($generalSetting) ? 'required' : ''  }} >
    </div>
</div>


<div class="form-group">
    <label  class="col-sm-2 control-label">Full Address</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{ isset($generalSetting) ? $generalSetting->full_address : old('full_address')  }}" placeholder="Enter the Full Address" name="full_address"  autocomplete="off">
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-2 control-label">Primary Contact</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{ isset($generalSetting) ? $generalSetting->primary_contact : old('primary_contact')  }}" placeholder="Enter the Primary Contact" name="primary_contact"  autocomplete="off">
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-2 control-label">Secondary Contact</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{ isset($generalSetting) ? $generalSetting->secondary_contact : old('secondary_contact')  }}" placeholder="Enter the Primary Contact" name="secondary_contact"  autocomplete="off">
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-2 control-label">Primary Bank Name </label>
    <div class="col-sm-6">
        <select class="form-control select2"  name="primary_bank_name"  autocomplete="off">
            <option value="">select bank </option>
            @foreach($banks as $key => $value)
                <option {{ (isset($generalSetting) && ($value->bank_name == $generalSetting->primary_bank_name))? 'selected':''}} value="{{$value->bank_name}} ">{{ucfirst($value->bank_name)}}</option>
            @endforeach
        </select>
    </div>
</div>


<div class="form-group">
    <label  class="col-sm-2 control-label">Primary Bank Account Number </label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{ isset($generalSetting) ? $generalSetting->primary_bank_account_number : old('primary_bank_account_number')  }}" placeholder="Enter the primary bank account number" name="primary_bank_account_number"  autocomplete="off">
    </div>
</div>


<div class="form-group">
    <label  class="col-sm-2 control-label">Primary Bank Branch </label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{ isset($generalSetting) ? $generalSetting->primary_bank_branch : old('primary_bank_branch')  }}" placeholder="Enter the Primary bank branch" name="primary_bank_branch"  autocomplete="off">
    </div>
</div>


<div class="form-group">
    <label  class="col-sm-2 control-label">Secondary Bank Name</label>
    <div class="col-sm-6">
        <select class="form-control select2"  name="secondary_bank_name"  autocomplete="off">
            <option value="">select bank </option>
            @foreach($banks as $key => $value)
                <option {{ (isset($generalSetting->secondary_bank_name) && ($value->bank_name == $generalSetting->secondary_bank_name) )? 'selected':''}} value="{{ $value->bank_name}} ">{{ucfirst($value->bank_name)}}</option>
            @endforeach
        </select>
    </div>
</div>


<div class="form-group">
    <label  class="col-sm-2 control-label">Secondary Bank Account Number</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{ isset($generalSetting) ? $generalSetting->secondary_bank_account_number : old('secondary_bank_account_number')  }}" placeholder="Enter the secondary bank account number Contact" name="secondary_bank_account_number"  autocomplete="off">
    </div>
</div>


<div class="form-group">
    <label  class="col-sm-2 control-label">Secondary Bank Branch</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{ isset($generalSetting) ? $generalSetting->secondary_bank_branch : old('secondary_bank_branch')  }}" placeholder="Enter the secondary bank branch " name="secondary_bank_branch"  autocomplete="off">
    </div>
</div>



<div class="form-group">
    <label  class="col-sm-2 control-label">Company Email</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{ isset($generalSetting) ? $generalSetting->company_email : old('company_email')  }}" placeholder="Enter the Company Email" name="company_email"  autocomplete="off">
    </div>
</div>


<div class="form-group">
    <label  class="col-sm-2 control-label">Company Brief</label>
    <div class="col-sm-6">
        <textarea name="company_brief" class="form-control" rows="2">{{ isset($generalSetting) ? $generalSetting->company_brief : old('company_brief')  }}</textarea>
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-2 control-label">Facebook</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{ isset($generalSetting) ? $generalSetting->facebook : old('facebook')  }}" placeholder="Enter the Facebook Details" name="facebook"  autocomplete="off">
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-2 control-label">Twitter</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{ isset($generalSetting) ? $generalSetting->twitter : old('twitter')  }}" placeholder="Enter the Twitter Details" name="twitter"  autocomplete="off">
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-2 control-label">Instagram</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" value="{{ isset($generalSetting) ? $generalSetting->instagram : old('instagram')  }}" placeholder="Enter the Instagram Details" name="instagram"  autocomplete="off">
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-2 control-label">Maintainence Mode</label>
    <div class="col-sm-6">
        <label class="switch">
            <input type="checkbox" value="1" {{ isset($generalSetting) ? $generalSetting->is_maintenance_mode == 1 ? 'checked' : '' : '' }} {{ old('is_maintenance_mode') == 1 ? 'checked' : '' }} name="is_maintenance_mode" >
            <span class="slider round"></span>
        </label>
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-2 control-label">Ip Filtering</label>
    <div class="col-sm-6">
        <label class="switch">
            <input type="checkbox" value="1" {{ isset($generalSetting) ? $generalSetting->ip_filtering == 1 ? 'checked' : '' : '' }} {{ old('ip_filtering') == 1 ? 'checked' : '' }} name="ip_filtering" >
            <span class="slider round"></span>
        </label>
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-2 control-label">SMS Enable</label>
    <div class="col-sm-6">
        <label class="switch">
            <input type="checkbox" value="1" {{ isset($generalSetting) ? $generalSetting->sms_enable == 1 ? 'checked' : '' : '' }} {{ old('sms_enable') == 1 ? 'checked' : '' }} name="sms_enable" >
            <span class="slider round"></span>
        </label>
    </div>
</div>
