
<div class="form-group">
    <div class="col-sm-6">
        <input type="hidden" class="form-control" name="investment_plan_code"  required  value="{{ isset($investmentInterestReleaseDetail) ? $investmentInterestReleaseDetail->investment_plan_code : $IPCode }}"  />
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Interest Release Time</label>

        <div class="col-sm-6">
{{--            @if(isset($investmentInterestReleaseDetail))--}}
                <select class="form-control select2"  name="interest_release_time" required autocomplete="off">
                    <option {{ (isset($investmentInterestReleaseDetail) && ($investmentInterestReleaseDetail->interest_release_time =='monthly'))? 'selected':''}} value ="monthly">Monthly</option>
                    <option {{ (isset($investmentInterestReleaseDetail) && ($investmentInterestReleaseDetail->interest_release_time =='yearly'))? 'selected':''}} value ="yearly">Yearly</option>
                    <option {{ (isset($investmentInterestReleaseDetail) && ($investmentInterestReleaseDetail->interest_release_time =='quaterly'))? 'selected':''}} value="quaterly">Quaterly</option>
                    <option {{ (isset($investmentInterestReleaseDetail) && ($investmentInterestReleaseDetail->interest_release_time =='semi-annually'))? 'selected':''}} value="semi-annually">Semi-Annually</option>
                </select>
{{--            @else--}}
{{--                <select class="form-control select2"   name="interest_release_time" required autocomplete="off">--}}
{{--                    <option value ="monthly">Monthly</option>--}}
{{--                    <option  value ="yearly">Yearly</option>--}}
{{--                    <option  value="quaterly">Quaterly</option>--}}
{{--                    <option  value="semi-annually">Semi-Annually</option>--}}
{{--                </select>--}}
{{--            @endif--}}
        </div>

</div>

<div class="form-group ">
    <label  class="col-sm-2 control-label">is_active</label>
    <div class="col-sm-6">
        @if(isset($investmentInterestReleaseDetail))
            <input type="checkbox" class="form-check-input " value="1"  id="is_active" name="is_active" {{($investmentInterestReleaseDetail->is_active==1)? 'checked': '' }} />
        @else
            <input type="checkbox" class="form-check-input " value="1"  id="is_active" name="is_active"  />
        @endif
    </div>
</div>











