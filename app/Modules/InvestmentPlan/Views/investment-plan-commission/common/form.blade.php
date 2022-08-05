

<div class="form-group">
    <div class="col-sm-6">
        <input type="hidden" class="form-control" name="investment_plan_code"  required  value="{{ isset($investmentCommissionDetail) ? $investmentCommissionDetail->investment_plan_code : $IPCode }}"  />
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Commission Type</label>
    <div class="col-sm-6">
        <select class="form-control select2"  name="commission_type" required autocomplete="off">
            <option value="">select commission type</option>
            <option {{ (isset($investmentCommissionDetail) && ($investmentCommissionDetail->commission_type =='annual'))? 'selected':''}} value ="annual">Annual</option>
            <option {{ (isset($investmentCommissionDetail) && ($investmentCommissionDetail->commission_type =='instant'))? 'selected':''}} value ="instant">Instant</option>
        </select>
    </div>
</div>


<div class="form-group">
    <label class="col-sm-2 control-label">Commission Mount Type</label>
    <div class="col-sm-6">
        <select class="form-control select2"  name="commission_mount_type" required autocomplete="off">
            <option value="">select commission mount type</option>
            <option {{ (isset($investmentCommissionDetail) && ($investmentCommissionDetail->commission_mount_type =='p'))? 'selected':''}} value ="p">Percentage</option>
            <option {{ (isset($investmentCommissionDetail) && ($investmentCommissionDetail->commission_mount_type =='f'))? 'selected':''}} value ="f">Flate</option>
        </select>
    </div>
</div>



<div class="form-group">
    <label class="col-sm-2 control-label">Commission Amount (Rs.)</label>
    <div class="col-sm-6">
        <input type="number" step="any" min="1" class="form-control" name="commission_amount_value"  required placeholder="commission amount" value="{{isset($investmentCommissionDetail) ? $investmentCommissionDetail->commission_amount_value : '' }}"  />
    </div>
</div>


<div class="form-group ">
    <label  class="col-sm-2 control-label">is_active</label>
    <div class="col-sm-6">
        @if(isset($investmentCommissionDetail))
            <input type="checkbox" class="form-check-input " value="1"  id="is_active" name="is_active" {{($investmentCommissionDetail->is_active==1)? 'checked': '' }} />
        @else
            <input type="checkbox" class="form-check-input " value="1"  id="is_active" name="is_active"  />
        @endif
    </div>
</div>













