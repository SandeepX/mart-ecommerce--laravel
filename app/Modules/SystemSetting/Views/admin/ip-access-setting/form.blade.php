<div class="form-group">
    <label for="ip_name" class="col-sm-2 control-label">Ip Name</label>
    <div class="col-sm-6">
        <input id="ip_name" type="text" class="form-control"
               value="{{isset($ipSetting) ? $ipSetting->ip_name : old('ip_name')  }}"
               placeholder="Eg: Office Ip" name="ip_name">
    </div>
</div>

<div class="form-group">
    <label for="ip_address" class="col-sm-2 control-label">Ip Address</label>
    <div class="col-sm-6">
        <input id="ip_address" type="text" class="form-control"
               value="{{isset($ipSetting) ? $ipSetting->ip_address : old('ip_address')  }}"
               placeholder="Eg: 192.169.0.10" name="ip_address">
    </div>
</div>

<div class="form-group">
    <label for="is_allowed" class="col-sm-2 control-label">Allowed</label>
    <div class="col-sm-6">
        <label class="switch">
            <input id="is_allowed" type="checkbox"
                   {{ isset($ipSetting) ? $ipSetting->is_allowed == 1 ? 'checked' : '' : '' }} {{ old('is_allowed') == 1 ? 'checked' : '' }} name="is_allowed">
            <span class="slider round"></span>
        </label>
    </div>
</div>
