

<div class="form-group">
    <label  class="col-sm-2 control-label">Salary(Rs.)</label>
    <div class="col-sm-6">
        <input type="number" name="salary" min="1" class="form-control" placeholder="e.g 90000" value="{{isset($smiSetting->salary) ? $smiSetting->salary:''}}"  {{ !isset($smiSetting) ? 'required' : ''  }} />
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-2 control-label">Term And Conditon</label>
    <div class="col-sm-6">
        <textarea id="terms_and_conditon" class="form-control summernote" name="terms_and_condition"
                  rows="4" cols="50"
                  autocomplete="off" placeholder="Enter Terms and Conditions"
                  value="{{isset($smiSetting) ? $smiSetting->terms_and_condition : ''  }}" >
                    {{isset($smiSetting->terms_and_condition)? $smiSetting->terms_and_condition: ''}}
        </textarea>
    </div>
</div>
















