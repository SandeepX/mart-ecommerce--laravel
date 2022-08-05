<div class="row">
    <div class="col-md-3 col-lg-4">
        <div class="form-group">
            <label class="control-label">Vendor Type *</label>
            <select name="vendor_type_code" class="form-control" id="vendor_type_code" required>
                <option value="" selected disabled>--Select An Option--</option>
                @if(isset($vendorTypes) && count($vendorTypes)>0)
                    @foreach($vendorTypes as $vendorType)
                        <option  {{isset($vendor) ? ( $vendor->vendor_type_code == $vendorType->vendor_type_code ? 'selected' : '') : '' }}  {{old('vendor_type_code') == $vendorType->vendor_type_code ? 'selected' : '' }} value="{{ $vendorType->vendor_type_code }}">
                            {{ $vendorType->vendor_type_name }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>


    <div class="col-md-3 col-lg-4">
        <div class="form-group">
            <label class="control-label">Company Type *</label>
            <select name="company_type_code" class="form-control" id="company_type_code" required>
                <option value="" selected disabled>--Select An Option--</option>
                @if(isset($companyTypes) && count($companyTypes)>0)
                    @foreach($companyTypes as $companyType)
                        <option  {{isset($vendor) ? ( $companyType->company_type_code == $vendor->company_type_code ? 'selected' : '') : '' }}  {{old('company_type_code') == $companyType->company_type_code ? 'selected' : '' }} value="{{ $companyType->company_type_code }}">
                            {{ $companyType->company_type_name }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>


    <div class="col-md-3 col-lg-4">
        <div class="form-group">
            <label class="control-label">Registration Type *</label>
            <select name="registration_type_code" class="form-control" id="registration_type_code" required>
                <option value="" selected disabled>--Select An Option--</option>
                @if(isset($registrationTypes) && count($registrationTypes)>0)
                    @foreach($registrationTypes as $registrationType)
                        <option  {{isset($vendor) ? ( $vendor->registration_type_code == $registrationType->registration_type_code ? 'selected' : '') : '' }}  {{old('registration_type_code') == $registrationType->registration_type_code ? 'selected' : '' }} value="{{ $registrationType->registration_type_code }}">
                            {{ $registrationType->registration_type_name }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>

    <div class="col-md-3 col-lg-4">
        <div class="form-group">
            <label class="control-label">Vendor Owner  *</label>
            <input type="text" value="{{isset($vendor) ? $vendor->vendor_owner : old('vendor_owner')}}" class="form-control" name="vendor_owner" />
        </div>
    </div>

    <div class="col-md-3 col-lg-4">
        <div class="form-group">
            <label class="control-label">Vendor Name  *</label>
            <input type="text" value="{{isset($vendor->vendor_name) ? $vendor->vendor_name : old('vendor_name')  }}" class="form-control" name="vendor_name" />
        </div>
    </div>

    <div class="col-md-3 col-lg-4">
        <div class="form-group">
            <label class="control-label">PAN No. </label>
            <input type="text" value="{{isset($vendor->pan) ? $vendor->pan : old('pan') }}" class="form-control" name="pan" />
        </div>
    </div>

    <div class="col-md-3 col-lg-4">
        <div class="form-group">
            <label class="control-label">VAT No. </label>
            <input type="text" value="{{isset($vendor->vat) ? $vendor->vat : old('vat') }}" class="form-control" name="vat" />
        </div>
    </div>


    <div class="col-md-3 col-lg-4">
        <div class="form-group">
            <label class="control-label">Company Logo </label>
            <input type="file" class="form-control" name="vendor_logo" />

            @if(isset($vendor->vendor_logo))
                <img src="{{asset($vendor->getLogoUploadPath().$vendor->vendor_logo)}}"
                     alt="Vendor Logo" width="50" height="50">
            @endif
        </div>
    </div>

</div>

