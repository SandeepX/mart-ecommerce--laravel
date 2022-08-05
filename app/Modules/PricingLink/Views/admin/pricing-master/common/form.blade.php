<div class="form-group col-md-8">
    <label class="control-label  @error('warehouse_code') text-red @enderror">
        Warehouse
        <span class="text-red">*</span></label>
    <div>
        <select name="warehouse_code" class="form-control select2" id="warehouse_code" required>
            <option value="" selected disabled>--Select An Option--</option>
            @if(isset($warehouses) && count($warehouses)>0)
                @foreach($warehouses as $value)
                    <option  {{isset($pricingLink) ? ( $value->warehouse_code == $pricingLink->warehouse_code ? 'selected' : '') : '' }}  {{old('warehouse_code') == $value->warehouse_code ? 'selected' : '' }} value="{{ $value->warehouse_code }}">
                        {{ $value->warehouse_name }}
                    </option>
                @endforeach
            @endif
        </select>
        @error('warehouse_code')
        <small class="text-red">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="form-group col-md-8">
    <label class="control-label">Link<span class="text-red">*</span></label>
    <div>
        <input type="hidden" value="{{isset($pricingLink) ? $pricingLink->link_code : $linkCode}}" name="link_code" />
        <textarea class="form-control" readonly name="link">{{isset($pricingLink) ? $pricingLink->link : $fullLink  }}
        </textarea>
        @error('link')
        <small class="text-red">{{ $message }}</small>
        @enderror
    </div>
</div>
<div class="form-group col-md-8">
    <label class="control-label">Password<span class="text-red">*</span></label>
    <div>

        <input type="text" class="form-control" value="{{isset($pricingLink) ? $pricingLink->password : old('password')  }}"
               name="password" required autocomplete="off">
        @error('password')
        <small class="text-red">{{ $message }}</small>
        @enderror
    </div>
</div>
<div class="form-group col-md-8">
    <label class="control-label">Expires At<span class="text-red">*</span></label>
  {{--  <div>
        <input type="date" class="form-control" value="{{isset($pricingLink) ? $pricingLink->expires_at : old('expires_at')  }}"
               name="expires_at" required autocomplete="off">
        @error('expires_at')
        <small class="text-red">{{ $message }}</small>
        @enderror
    </div>--}}
    <div class='input-group date datetimepicker'>
        <input type='text' class="form-control"
               value="{{isset($pricingLink->expires_at)? $pricingLink->expires_at:old('expires_at')}}"
               name="expires_at"/>
        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
    </div>

</div>

<div class="form-group col-md-8">
    <label class="control-label">Is Active<span class="text-red">*</span></label></label>
    <div>
        <input type="hidden" value="0" name="is_active" />
        <input type="checkbox" class="form-check-input " value="1"  id="is_active" name="is_active" {{(isset($pricingLink) && $pricingLink->is_active==1)? 'checked': '' }} />

    </div>
</div>





















