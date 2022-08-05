
<div class="row">

    <div class="col-md-3 col-lg-4">
        <div class="form-group">
            <label class="control-label">Company Type *</label>
            <select name="store_company_type_code" class="form-control" id="store_company_type_code" required>
                <option value="" selected disabled>--Select An Option--</option>
                @if(isset($companyTypes) && count($companyTypes)>0)
                    @foreach($companyTypes as $companyType)
                        <option  {{isset($store) ? ( $companyType->company_type_code == $store->store_company_type_code ? 'selected' : '') : '' }}  {{old('store_company_type_code') == $companyType->company_type_code ? 'selected' : '' }} value="{{ $companyType->company_type_code }}">
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
            <select name="store_registration_type_code" class="form-control" id="store_registration_type_code" required>
                <option value="" selected disabled>--Select An Option--</option>
                @if(isset($registrationTypes) && count($registrationTypes)>0)
                    @foreach($registrationTypes as $registrationType)
                        <option  {{isset($store) ? ( $registrationType->registration_type_code == $store->store_registration_type_code ? 'selected' : '') : '' }}  {{old('store_company_type_code') == $registrationType->registration_type_code ? 'selected' : '' }} value="{{ $registrationType->registration_type_code }}">
                            {{ $registrationType->registration_type_name }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>

    <div class="col-md-3 col-lg-4">
        <div class="form-group">
            <label class="control-label">Store Owner  *</label>
            <input required type="text" value="{{isset($store) ? $store->store_owner : old('store_owner')}}" class="form-control" name="store_owner" />
        </div>
    </div>

    <div class="col-md-3 col-lg-4">
        <div class="form-group">
            <label class="control-label">Store Name  *</label>
            <input required type="text" value="{{isset($store->store_name) ? $store->store_name : old('store_name')  }}" class="form-control" name="store_name" />
        </div>
    </div>

    <div class="col-md-3 col-lg-4">
        <div class="form-group">
            <label class="control-label">Store Size  *</label>
            <select name="store_size_code" class="form-control" id="store_size_code" required>
                <option value="" selected disabled>--Select An Option--</option>
                @if(isset($storeSizes))
                    @foreach($storeSizes as $storeSize)
                        <option {{isset($store) ? ( $storeSize->store_size_code == $store->store_size_code ? 'selected' : '') : '' }}
                                {{old('store_size_code') == $storeSize->store_size_code ? 'selected' : '' }}
                                value="{{ $storeSize->store_size_code }}"
                                >
                            {{ $storeSize->store_size_name }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>

    <div class="col-md-3 col-lg-4">
        <div class="form-group">
            <label class="control-label">Store Established date  *</label>
            <input required type="date" value="{{isset($store->store_established_date) ? $store->store_established_date : old('store_established_date')  }}" class="form-control" name="store_established_date" />
        </div>
    </div>

    <div class="col-md-3 col-lg-4">
        <div class="form-group">
            <label class="control-label">Store Logo*</label>
            <input  type="file" class="form-control" name="store_logo" />
            @if(isset($store))
            <br>
            <img src="{{asset($store->getLogoUploadPath().$store->store_logo)}}" alt="Store Logo" width="50" height="50">
            @endif
        </div>
    </div>


    <div class="col-md-3 col-lg-4">
        <div class="form-group">
            <label class="control-label">Pan/Vat Type *</label>
            <select name="pan_vat_type" class="form-control" id="pan_vat_type"\>
                <option  {{isset($store) ? ( $store->pan_vat_type == 'pan' ? 'selected' : '') : '' }}  {{old('pan_vat_type') == 'pan' ? 'selected' : '' }} value="pan">
                    Pan
                </option>
                <option  {{isset($store) ? ( $store->pan_vat_type == 'vat' ? 'selected' : '') : '' }}  {{old('pan_vat_type') == 'vat' ? 'selected' : '' }} value="vat">
                    Vat
                </option>
            </select>
        </div>
    </div>

    <div class="col-md-3 col-lg-4">
        <div class="form-group">
            <label class="control-label">Pan/Vat No.</label>
            <input type="text" value="{{isset($store->pan_vat_no) ? $store->pan_vat_no : old('pan_vat_no')  }}" class="form-control" name="pan_vat_no" />
        </div>
    </div>

</div>


