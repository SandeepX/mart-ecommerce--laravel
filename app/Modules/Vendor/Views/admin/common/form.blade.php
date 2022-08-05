<div class="card card-default">
            <div class="card-header">
                <h4 class="card-title">
                    <a  href="javascript:void(0)">
                        <b>SECTION I : VENDOR PERSONAL DETAILS</b>
                    </a>
                </h4>
            </div>
            <div id="collapse1" class="collapse show">
                <div class="card-body">
                    @include(''.$module.'.admin.form_partials.section_one')
                </div>
            </div>
        </div>

        <br />
        <div class="card card-default">
            <div class="card-header">
                <h4 class="card-title">
                    <a  href="javascript:void(0)">
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
                    <a  href="javascript:void(0)">
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
        <br />

        @if(request()->routeIs('admin.vendors.create'))
            <div class="card card-default">
                <div class="card-header">
                    <h4 class="card-title">
                        <a  href="javascript:void(0)">
                            <b>SECTION IV : LOGIN DETAILS</b>
                        </a>
                    </h4>
                </div>
                <div id="collapse2" class="collapse show">
                    <div class="card-body">
                        @include(''.$module.'.admin.form_partials.section_four')
                    </div>
                </div>
            </div>
        @endif




<div class="row">

    <hr>
    <div class="text-center">
        <button type="submit" id="save" class="btn btn-success  btn-md btn-block">
            <i class="fa fa-save"></i> Save
        </button>
    </div>
</div>


@push('scripts')
    @includeIf('Vendor::admin.scripts.map-script')
    @includeIf('Vendor::admin.scripts.location-script')
@endpush





