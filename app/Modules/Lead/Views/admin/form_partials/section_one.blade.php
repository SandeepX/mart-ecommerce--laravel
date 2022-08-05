<div class="row">
    <div class="col-md-3 col-lg-4">
        <div class="form-group">
            <label class="control-label">Name  *</label>
            <input type="text" required value="{{isset($lead) ? $lead->lead_name : old('lead_name')  }}" class="form-control" name="lead_name" />
        </div>
    </div>

    <div class="col-md-3 col-lg-4">
        <div class="form-group">
            <label class="control-label">Email</label>
            <input type="text" value="{{isset($lead) ? $lead->lead_email : old('lead_email') }}" class="form-control" name="lead_email" />
        </div>
    </div>

    <div class="col-md-3 col-lg-4">
        <div class="form-group">
            <label class="control-label">Phone No. * </label>
            <input type="number" required value="{{isset($lead) ? $lead->lead_phone_no : old('lead_phone_no') }}" class="form-control" name="lead_phone_no" />
        </div>
    </div>

    <div class="col-md-3 col-lg-4">
        <div class="form-group">
            <label class="control-label">Alternative Phone No. </label>
            <input type="number" value="{{isset($lead) ? $lead->lead_alternative_phone_no : old('lead_alternative_phone_no') }}" class="form-control" name="lead_alternative_phone_no" />
        </div>
    </div>

    <div class="col-md-8 col-lg-8">
        <div class="form-group">
            <label class="control-label">Remarks</label>
            <input type="text" value="{{isset($lead) ? $lead->remarks : old('remarks') }}" class="form-control" name="remarks" />
        </div>
    </div>


</div>

