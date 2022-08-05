<div class="card card-default">
    <div class="card-header">
        <h4 class="card-title">
            <a href="javascript:void(0)">
                <b>SECTION I : Warehouse DETAILS</b>
            </a>
        </h4>
    </div>
    <div id="collapse1" class="collapse show">
        <div class="card-body">
            <div class="row">

                <div class="col-md-3 col-lg-4">
                    <div class="form-group">
                        <label class="control-label">Warehouse Name  *</label>
                        <input type="text" value="{{isset($warehouse->warehouse_name) ? $warehouse->warehouse_name : old('warehouse_name')  }}" class="form-control" name="warehouse_name" />
                    </div>
                </div>

                <div class="col-md-3 col-lg-4">
                    <div class="form-group">
                        <label for="warehouse_type_code" class="control-label">Warehouse Type *</label>
                        <select name="warehouse_type_code" class="form-control" id="warehouse_type_code" required>
                            <option value="" selected disabled>--Select An Option--</option>
                            @if(isset($warehouseTypes) && count($warehouseTypes)>0)
                                @foreach($warehouseTypes as $warehouseType)
                                    <option data-slug={{$warehouseType->slug}}
                                    {{isset($warehouse) ? ( $warehouseType->warehouse_type_code == $warehouse->warehouse_type_code ? 'selected' : '') : '' }}
                                    {{old('warehouse_type_code') == $warehouseType->warehouse_type_code ? 'selected' : '' }}
                                            value="{{ $warehouseType->warehouse_type_code }}">
                                        {{ $warehouseType->warehouse_type_name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="col-md-3 col-lg-4">
                    <div class="form-group">
                        <label class="control-label">Remarks </label>
                        <input type="text" value="{{isset($warehouse->remarks) ? $warehouse->remarks : old('remarks')  }}" class="form-control" name="remarks" />
                    </div>
                </div>

                <div class="col-md-3 col-lg-4">
                    <div class="form-group">
                        <label class="control-label">Pan/Vat Type *</label>
                        <select name="pan_vat_type" class="form-control" id="pan_vat_type"\>
                            <option  {{isset($warehouse) ? ( $warehouseType->pan_vat_type == 'pan' ? 'selected' : '') : '' }}  {{old('pan_vat_type') == 'pan' ? 'selected' : '' }} value="pan">
                                Pan
                            </option>
                            <option  {{isset($warehouse) ? ( $warehouseType->pan_vat_type == 'vat' ? 'selected' : '') : '' }}  {{old('pan_vat_type') == 'vat' ? 'selected' : '' }} value="vat">
                                Vat
                            </option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3 col-lg-4">
                    <div class="form-group">
                        <label class="control-label">Pan/Vat No.</label>
                        <input type="text" value="{{isset($warehouse->pan_vat_no) ? $warehouse->pan_vat_no : old('pan_vat_no')  }}" class="form-control" name="pan_vat_no" />
                    </div>
                </div>

                <div class="col-md-3 col-lg-4">
                    <div class="form-group">
                        <label class="control-label">Warehouse Logo </label>
                        <input type="file" class="form-control" name="warehouse_logo" />

                        @if(isset($warehouse->warehouse_logo))
                            <img src="{{asset($warehouse->getLogoUploadPath().$warehouse->warehouse_logo)}}"
                                 alt="Warehouse Logo" width="50" height="50">
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<br />
<div class="card card-default">
    <div class="card-header">
        <h4 class="card-title">
            <a href="javascript:void(0)">
                <b>SECTION II : LOCATION DETAILS</b>
            </a>
        </h4>
    </div>
    <div id="collapse2" class="collapse show">
        <div class="card-body">
            @include(''.$module.'.admin.form_partials.section_two')
        </div>
    </div>
</div>

<br />
<div class="card card-default">
    <div class="card-header">
        <h4 class="card-title">
            <a href="javascript:void(0)">
                <b>SECTION III : CONTACT DETAILS</b>
            </a>
        </h4>
    </div>
    <div id="collapse2" class="collapse show">
        <div class="card-body">
            @include(''.$module.'.admin.form_partials.section_three')
        </div>
    </div>
</div>

<div class="row">
    <hr>
    <div class="text-center">
        <button type="submit" id="save" class="btn btn-success  btn-md btn-block">
            <i class="fa fa-save"></i> Update
        </button>
    </div>
</div>

@push('scripts')
@includeIf('AlpasalWarehouse::admin.scripts.map-script')
@includeIf('AlpasalWarehouse::admin.scripts.location-script')
@includeIf('AlpasalWarehouse::admin.scripts.warehouse-edit-script')
@endpush
