
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span></button>
    <h4 class="modal-title">Store Indiviual Kyc Detail</h4>
</div>

<div class="box-body">
    <div class="col-md-12">
        <div class="col-md-6">
            <div class="box box-success">
                <div class="box-header with-border">

                                            <span style="font-size: 15px;" class="label label-primary">
                                                 General Detail
                                             </span>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-9">
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b>Kyc code</b> <a
                                        class="pull-right">{{$individualKyc['kyc_code']}}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Store</b> <a class="pull-right">
                                        {{$individualKyc['store_name']}}
                                        - {{$individualKyc['store_code']}}
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <b>Submitted By</b> <a
                                        class="pull-right">{{$individualKyc['submitted_by']}}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Kyc For</b> <a
                                        class="pull-right">{{$individualKyc['kyc_for']}}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Name(Devanagari)</b> <a
                                        class="pull-right">{{$individualKyc['name_in_devanagari']}}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Name(English)</b> <a
                                        class="pull-right">{{$individualKyc['name_in_english']}}</a>
                                </li>


                                <li class="list-group-item">
                                    <b>Educational Qualification</b> <a
                                        class="pull-right">{{$individualKyc['educational_qualification']}}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Marital Status</b> <a
                                        class="pull-right">{{$individualKyc['marital_status']}}</a>
                                </li>
                                {{--                                                            <li class="list-group-item">--}}
                                {{--                                                                <b>Pan No</b> <a--}}
                                {{--                                                                        class="pull-right">{{$individualKyc['pan_no']}}</a>--}}
                                {{--                                                            </li>--}}
                            </ul>
                        </div>
                    </div>

                </div>
                <!-- /.box-body -->
            </div>
        </div>


        <div class="col-md-6">
            <div class="box box-success">
                <div class="box-header with-border">

                                            <span style="font-size: 15px;" class="label label-success">
                                                 Citizenship Detail
                                             </span>

                </div>
                <div class="box-body">
                    <ul class="list-group list-group-unbordered">

                        <li class="list-group-item">
                            <b>Citizenship No.</b> <a class="pull-right">
                                {{$individualKyc['kyc_citizenship_detail']['citizenship_no']}}
                            </a>
                        </li>

                        <li class="list-group-item">
                            <b>Nationality</b> <a class="pull-right">
                                {{$individualKyc['kyc_citizenship_detail']['citizenship_nationality']}}
                            </a>
                        </li>
                        <li class="list-group-item">
                            <b>Gender</b> <a class="pull-right">
                                {{$individualKyc['kyc_citizenship_detail']['citizenship_gender']}}
                            </a>
                        </li>
                        <li class="list-group-item">
                            <b>Birth Place</b> <a class="pull-right">
                                {{$individualKyc['kyc_citizenship_detail']['citizenship_birth_place']}}
                            </a>
                        </li>
                        <li class="list-group-item">
                            <b>District</b> <a class="pull-right">
                                {{$individualKyc['kyc_citizenship_detail']['citizenship_district']}}
                            </a>
                        </li>

                        <li class="list-group-item">
                            <b>Date Of Birth</b> <a class="pull-right">
                                {{$individualKyc['kyc_citizenship_detail']['citizenship_dob']}}
                            </a>
                        </li>
                        <li class="list-group-item">
                            <b>Father's Name</b> <a class="pull-right">
                                {{$individualKyc['kyc_citizenship_detail']['citizenship_father_name']}}
                            </a>
                        </li>

                        <li class="list-group-item">
                            <b>Mother's Name</b> <a class="pull-right">
                                {{$individualKyc['kyc_citizenship_detail']['citizenship_mother_name']}}
                            </a>
                        </li>

                        <li class="list-group-item">
                            <b>Spouse Name</b> <a class="pull-right">
                                {{$individualKyc['kyc_citizenship_detail']['citizenship_spouse_name']}}
                            </a>
                        </li>

                        <li class="list-group-item">
                            <b>Grandfather's Name</b> <a class="pull-right">
                                {{$individualKyc['kyc_citizenship_detail']['citizenship_grandfather_name']}}
                            </a>
                        </li>


                        <li class="list-group-item">
                            <b>Issued Date</b> <a class="pull-right">
                                {{$individualKyc['kyc_citizenship_detail']['citizenship_issued_date']}}
                            </a>
                        </li>


                    </ul>
                </div>
                <!-- /.box-body -->
            </div>
        </div>

        <div class="col-md-6">

            <div class="col-md-6">
                <div class="box box-success">
                    <div class="box-header with-border">

                                            <span style="font-size: 15px;" class="label label-danger">
                                               Permanent Address Detail
                                             </span>

                    </div>
                    <div class="box-body">
                        <ul class="list-group list-group-unbordered">

                            <li class="list-group-item">
                                <b>Province No</b> <a class="pull-right">
                                    {{$individualKyc['permanent_location_tree']['province']['location_name']}}
                                </a>
                            </li>
                            <li class="list-group-item">
                                <b>District </b> <a class="pull-right">
                                    {{$individualKyc['permanent_location_tree']['district']['location_name']}}
                                </a>
                            </li>
                            <li class="list-group-item">
                                <b>Municipality</b> <a class="pull-right">
                                    {{$individualKyc['permanent_location_tree']['municipality']['location_name']}}
                                </a>
                            </li>
                            <li class="list-group-item">
                                <b>Ward</b> <a class="pull-right">
                                    {{$individualKyc['permanent_location_tree']['ward']['location_name']}}
                                </a>
                            </li>
                            <li class="list-group-item">
                                <b>Street</b> <a class="pull-right">
                                    {{$individualKyc['permanent_street']}}
                                </a>
                            </li>

                            <li class="list-group-item">
                                <b>House No</b> <a class="pull-right">
                                    {{$individualKyc['permanent_house_no']}}
                                </a>
                            </li>

                        </ul>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>

            <div class="col-md-6">
                <div class="box box-success">
                    <div class="box-header with-border">

                                            <span style="font-size: 15px;" class="label label-danger">
                                               Present Address Detail
                                             </span>

                    </div>
                    <div class="box-body">
                        <ul class="list-group list-group-unbordered">

                            <li class="list-group-item">
                                <b>Province No</b> <a class="pull-right">
                                    {{$individualKyc['present_location_tree']['province']['location_name']}}
                                </a>
                            </li>
                            <li class="list-group-item">
                                <b>District </b> <a class="pull-right">
                                    {{$individualKyc['present_location_tree']['district']['location_name']}}
                                </a>
                            </li>
                            <li class="list-group-item">
                                <b>Municipality</b> <a class="pull-right">
                                    {{$individualKyc['present_location_tree']['municipality']['location_name']}}
                                </a>
                            </li>
                            <li class="list-group-item">
                                <b>Ward</b> <a class="pull-right">
                                    {{$individualKyc['present_location_tree']['ward']['location_name']}}
                                </a>
                            </li>
                            <li class="list-group-item">
                                <b>Street</b> <a class="pull-right">
                                    {{$individualKyc['present_street']}}
                                </a>
                            </li>

                            <li class="list-group-item">
                                <b>House No</b> <a class="pull-right">
                                    {{$individualKyc['present_house_no']}}
                                </a>
                            </li>

                        </ul>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>

{{--            <div class="col-md-12">--}}
{{--                <div class="box box-success">--}}
{{--                    <div class="box-header with-border">--}}

{{--                                            <span style="font-size: 15px;" class="label label-danger">--}}
{{--                                               Documents--}}
{{--                                             </span>--}}

{{--                    </div>--}}
{{--                    <div class="box-body">--}}
{{--                        <ul class="list-group list-group-unbordered">--}}

{{--                            @foreach($individualKyc['kyc_documents'] as $kycDocument)--}}
{{--                                <li class="list-group-item">--}}
{{--                                    <b>{{convertToWords($kycDocument['document_type'],'_')}}</b>--}}



{{--                                    <div class="pull-right">--}}
{{--                                        <button class="btn btn-xs btn-info">--}}
{{--                                            <a style="color:white" href="{{$kycDocument['document_file']}}"--}}
{{--                                               class="pull-right" download>--}}
{{--                                                Download--}}
{{--                                            </a>--}}
{{--                                        </button>--}}
{{--                                        <button class="btn btn-xs btn-primary">--}}
{{--                                            <a style="color:white" href="{{$kycDocument['document_file']}}"--}}
{{--                                               class="pull-right" target="_blank">--}}
{{--                                                View--}}
{{--                                            </a>--}}
{{--                                        </button>--}}
{{--                                    </div>--}}

{{--                                </li>--}}
{{--                            @endforeach--}}

{{--                        </ul>--}}
{{--                    </div>--}}
{{--                    <!-- /.box-body -->--}}
{{--                </div>--}}
{{--            </div>--}}

        </div>

        <div class="col-md-12">

            <div class="box box-success">
                <div class="box-header with-border">
                                            <span style="font-size: 15px;" class="label label-danger">
                                               Bank Details
                                             </span>
                </div>

                @foreach($individualKyc['kyc_banks_detail'] as $bankDetail)

                    <div class="col-md-6">
                        <div class="box-body">
                            <strong>Bank Code : {{$bankDetail['bank_code']}}</strong><br>
                            <strong>Bank Name : {{$bankDetail['bank_name']}}</strong><br>
                            <strong>Branch Name
                                : {{$bankDetail['bank_branch_name']}}</strong><br>
                            <strong>Account No : {{$bankDetail['bank_account_no']}}</strong><br>
                            <strong>Account Holder Name
                                : {{$bankDetail['bank_account_holder_name']}}</strong>

                            <hr>
                        </div>
                        <!-- /.box-body -->
                    </div>
                @endforeach

            </div>
        </div>
        <div class="col-md-12">
            <input hidden id="latitude" name="latitude" value="{{$individualKyc['latitude']}}">
            <input hidden id="longitude" name="longitude" value="{{$individualKyc['longitude']}}">
            <div id="map-location"></div>
        </div>

    </div>
</div>

@push('scripts')
    @include('Store::admin.kyc.common.scripts.map-scripts')
    @include('Store::admin.kyc.common.scripts.kyc-scripts')
@endpush
