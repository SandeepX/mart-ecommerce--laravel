

<div class="form-group">
    <label  class="col-sm-2 control-label">Social Media Name</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" placeholder="Example:facebook" value="{{isset($socialMediaDetail->social_media_name)?$socialMediaDetail->social_media_name:''}}" name="social_media_name" {{ !isset($socialMediaDetail) ? 'required' : ''  }} />
    </div>
</div>


<div class="form-group">
    <label  class="col-sm-2 control-label">Base Url</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" placeholder="Example:https://" value="{{isset($socialMediaDetail->base_url)?$socialMediaDetail->base_url:''}}" name="base_url" {{ !isset($socialMediaDetail) ? 'required' : ''  }} />
    </div>
</div>

<div class="form-group ">
    <label  class="col-sm-2 control-label">Enable For SMI</label>
    <div class="col-sm-6">
            <input type="checkbox" class="form-check-input " value="1"  id="enabled_for_smi" name="enabled_for_smi" {{ (isset($socialMediaDetail) && ($socialMediaDetail->enabled_for_smi==1))? 'checked': '' }} />
    </div>
</div>












