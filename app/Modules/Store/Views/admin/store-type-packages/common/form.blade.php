<div class="form-group col-md-4">
    <label class="control-label  @error('package_name') text-red @enderror">

        Package Name

        <span class="text-red">*</span></label>
    <div>
        <input type="text" class="form-control mx-1" value="{{isset($storeTypePackage) ? $storeTypePackage->package_name : old('package_name')  }}" placeholder=" Package Name" name="package_name" required
               autocomplete="off">
        @error('package_name')
          <small class="text-red">{{ $message }}</small>
        @enderror
    </div>
</div>

{{--<div class="form-group col-md-4">--}}
{{--    <label class="control-label">Image<span class="text-red">*</span></label>--}}
{{--    <div>--}}
{{--        <input type="file" class="form-control" name="image" required>--}}
{{--        @error('image')--}}
{{--        <small class="text-red">{{ $message }}</small>--}}
{{--        @enderror--}}
{{--    </div>--}}
{{--</div>--}}

<div class="form-group col-md-4">
    <label class="control-label">Refundable Registration Charge<span class="text-red">*</span></label></label>
    <div>
        <input type="number" class="form-control" value="{{isset($storeTypePackage) ? $storeTypePackage->refundable_registration_charge : old('refundable_registration_charge')  }}" placeholder="Refundable Registration Charge"
               name="refundable_registration_charge" required autocomplete="off">
        @error('refundable_registration_charge')
        <small class="text-red">{{ $message }}</small>
        @enderror
    </div>
</div>
<div class="form-group col-md-4">
    <label class="control-label">Non Refundable Registration Charge<span class="text-red">*</span></label></label>
    <div>
        <input type="number" class="form-control" value="{{isset($storeTypePackage) ? $storeTypePackage->non_refundable_registration_charge : old('non_refundable_registration_charge')  }}" placeholder="Non Refundable Registration Charge"
               name="non_refundable_registration_charge" required autocomplete="off">
        @error('non_refundable_registration_charge')
        <small class="text-red">{{ $message }}</small>
        @enderror
    </div>
</div>
<div class="form-group col-md-4">
    <label class="control-label">Base Investment<span class="text-red">*</span></label></label>
    <div>
        <input type="number" class="form-control" value="{{isset($storeTypePackage) ? $storeTypePackage->base_investment : old('base_investment')  }}" placeholder="Base Investment" name="base_investment" required
               autocomplete="off">
        @error('base_investment')
        <small class="text-red">{{ $message }}</small>
        @enderror
    </div>
</div>
<div class="form-group col-md-4">
    <label class="control-label">Annual Purchasing Limit<span class="text-red">*</span></label></label>
    <div>
        <input type="number" class="form-control" value="{{isset($storeTypePackage) ? $storeTypePackage->annual_purchasing_limit : old('annual_purchasing_limit')  }}" placeholder="Annual Purchasing Limit" name="annual_purchasing_limit"
               required autocomplete="off">
        @error('annual_purchasing_limit')
        <small class="text-red">{{ $message }}</small>
        @enderror
    </div>
</div>
<div class="form-group col-md-4">
    <label class="control-label">Referal Registration Incentive Amount<span class="text-red">*</span></label></label>
    <div>
        <input type="number" class="form-control" value="{{isset($storeTypePackage) ? $storeTypePackage->referal_registration_incentive_amount : old('referal_registration_incentive_amount')  }}" placeholder="Referal Registration Incentive Amount"
               name="referal_registration_incentive_amount" required autocomplete="off">
        @error('referal_registration_incentive_amount')
        <small class="text-red">{{ $message }}</small>
        @enderror
    </div>
</div>
<div class="form-group col-md-8">
    <label class="control-label">Description <span class="text-red">*</span></label></label>
    <div>
        <textarea class="form-control summernote col-md-12" name="description" required>{{isset($storeTypePackage) ? $storeTypePackage->description : old('description')  }}</textarea>
        @error('description')
        <small class="text-red">{{ $message }}</small>
        @enderror
    </div>
</div>
<input type="hidden" class="form-control" value="{{$storeType->store_type_code}}" name="store_type_code" required
       autocomplete="off">
