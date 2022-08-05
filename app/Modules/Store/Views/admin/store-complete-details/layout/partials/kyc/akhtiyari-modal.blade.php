{{--<div class="modal-header">--}}
{{--    <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--        <span aria-hidden="true">×</span></button>--}}
{{--    <h4 class="modal-title">Stores Kyc</h4>--}}
{{--</div>--}}
{{--<div class="modal-body">--}}
{{--    <div class="row">--}}
{{--        <section class="content-header">--}}
{{--            <h1>--}}
{{--                Stores Kyc--}}
{{--                <small>Manage Stores Kyc</small>--}}
{{--            </h1>--}}
{{--            <ol class="breadcrumb">--}}
{{--                <li><a href="http://backend.allkhata.com/admin.dashboard"><i class="fa fa-home"></i> Dashboard</a></li>--}}
{{--                <li class="active"><a href="http://backend.allkhata.com/admin/stores-kyc/individuals"><i class="fa fa-"></i> Stores Kyc</a></li>--}}
{{--            </ol>--}}
{{--        </section>--}}
{{--        <!-- Main content -->--}}
{{--        <section class="content">--}}
{{--            <div class="row">--}}
{{--                <!-- left column -->--}}
{{--                <div class="col-md-12">--}}
{{--                    <!-- general form elements -->--}}
{{--                    <div class="box box-primary">--}}

{{--                        <div class="box-body">--}}
{{--                            <div class="col-md-12">--}}
{{--                                <div class="box box-success">--}}
{{--                                    <div class="box-header with-border">--}}

{{--                                                                                                <span style="font-size: 15px;" class="label label-{{config('kyc_verification_statuses.labels.'.$akhtiyariKyc['verification_status'])}}">--}}
{{--                                                                                                    Status: {{$akhtiyariKyc['verification_status']}}--}}
{{--                                                                                                 </span>--}}

{{--                                        @can('Verify Store Individual Kyc')--}}
{{--                                            @if($akhtiyariKyc['verification_status'] != 'verified')--}}
{{--                                                <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">--}}
{{--                                                    <a href="javascript:void(0)" id="respond_btn" style="border-radius: 0px; " class="btn btn-sm btn-primary">--}}
{{--                                                        <i class="fa fa-reply"></i>--}}
{{--                                                        Respond--}}
{{--                                                    </a>--}}
{{--                                                </div>--}}
{{--                                            @endif--}}
{{--                                            @if($akhtiyariKyc['verification_status'] == 'verified' && $akhtiyariKyc['can_update_kyc'] == 0 )--}}
{{--                                                <form class="form-horizontal" role="form"--}}
{{--                                                      action="{{route('admin.stores-kyc.individual.allow-update-request',$akhtiyariKyc['kyc_code'])}}"--}}
{{--                                                      method="post">--}}
{{--                                                    @csrf--}}
{{--                                                    <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">--}}
{{--                                                        <button onclick="return confirm('Are you sure you want to continue ?')" type="submit"  style="border-radius: 0px; " class="btn btn-sm btn-primary">--}}
{{--                                                            <i class="fa fa-check-circle-o"></i>--}}
{{--                                                            Allow Kyc Update Request--}}
{{--                                                        </button>--}}
{{--                                                    </div>--}}
{{--                                                </form>--}}
{{--                                            @endif--}}


{{--                                            @if($akhtiyariKyc['verification_status'] == 'verified' && $akhtiyariKyc['can_update_kyc'] == 1 )--}}

{{--                                                <button style="cursor:pointer;margin-left:20px!important" class="btn btn-success">--}}
{{--                                                    <strong> Kyc Update Request Allowed : {{getNepTimeZoneDateTime($akhtiyariKyc['update_request_allowed_at'])}}</strong>--}}
{{--                                                </button>--}}

{{--                                            @endif--}}
{{--                                        @endcan--}}

{{--                                    </div>--}}
{{--                                    @if($akhtiyariKyc['verification_status'] != 'pending')--}}
{{--                                        <div class="box-body">--}}
{{--                                            <strong>Last Responded At: {{$akhtiyariKyc['responded_at']}} </strong><br>--}}
{{--                                            <strong>Responded By: {{$akhtiyariKyc['responded_by']}} </strong><br>--}}
{{--                                            <strong>Remarks: {!! $akhtiyariKyc['remarks'] !!} </strong><br>--}}
{{--                                        </div>--}}
{{--                                    @endif--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        @can('Verify Store Individual Kyc')--}}
{{--                        @if($akhtiyariKyc['verification_status'] != 'verified')--}}
{{--                            <div class="box-body" id="respond_form" {{old('verification_status') ? '' :'hidden'}}>--}}
{{--                                <form class="form-horizontal" role="form"--}}
{{--                                      action="{{route('admin.stores-kyc.individuals.respond',$akhtiyariKyc['kyc_code'])}}"--}}
{{--                                      method="post">--}}
{{--                                    {{csrf_field()}}--}}

{{--                                    <div class="box-body">--}}

{{--                                        <div class="col-md-12">--}}

{{--                                            <div class="form-group">--}}
{{--                                                <label for="verification_status" class="control-label">Verification--}}
{{--                                                    Status</label>--}}
{{--                                                <select id="verification_status" name="verification_status"--}}
{{--                                                        class="form-control">--}}
{{--                                                    <option value="verified"--}}
{{--                                                        {{old('verification_status') == 'verified' ? 'selected' : ''}}>--}}
{{--                                                        Verify--}}
{{--                                                    </option>--}}
{{--                                                    <option value="rejected"--}}
{{--                                                        {{old('verification_status') == 'rejected' ? 'selected' : ''}}>--}}
{{--                                                        Reject--}}
{{--                                                    </option>--}}

{{--                                                </select>--}}
{{--                                            </div>--}}

{{--                                            <div class="form-group">--}}
{{--                                                <label for="remarks" class="control-label">Remarks</label>--}}
{{--                                                <textarea id="remarks" class="form-control summernote" name="remarks"--}}
{{--                                                          placeholder="Enter remarks">{{old('remarks')}}</textarea>--}}
{{--                                            </div>--}}

{{--                                        </div>--}}

{{--                                    </div>--}}
{{--                                    <!-- /.box-body -->--}}

{{--                                    <div class="box-footer">--}}
{{--                                        <button type="submit" style="width: 49%;margin-left: 17%;"--}}
{{--                                                class="btn btn-block btn-primary">Respond--}}
{{--                                        </button>--}}
{{--                                    </div>--}}
{{--                                </form>--}}
{{--                            </div>--}}

{{--                        @endif--}}
{{--                    @endcan--}}
{{--                    @can('Show Store Individual Kyc')--}}
{{--                        <div class="box-body">--}}
{{--                            <div class="col-md-12">--}}
{{--                                <div class="col-md-6">--}}
{{--                                    <div class="box box-success">--}}
{{--                                        <div class="box-header with-border">--}}

{{--                                                                                                        <span style="font-size: 15px;" class="label label-primary">--}}
{{--                                                                                                             General Detail--}}
{{--                                                                                                         </span>--}}
{{--                                        </div>--}}
{{--                                        <!-- /.box-header -->--}}
{{--                                        <div class="box-body">--}}
{{--                                            <div class="row">--}}
{{--                                                <div class="col-sm-9">--}}
{{--                                                    <ul class="list-group list-group-unbordered">--}}
{{--                                                        <li class="list-group-item">--}}
{{--                                                            <b>Kyc code</b> <a--}}
{{--                                                                class="pull-right">{{$akhtiyariKyc['kyc_code']}}</a>--}}
{{--                                                        </li>--}}
{{--                                                        <li class="list-group-item">--}}
{{--                                                            <b>Store</b> <a class="pull-right">--}}
{{--                                                                {{$akhtiyariKyc['store_name']}}--}}
{{--                                                                - {{$akhtiyariKyc['store_code']}}--}}
{{--                                                            </a>--}}
{{--                                                        </li>--}}
{{--                                                        <li class="list-group-item">--}}
{{--                                                            <b>Submitted By</b> <a--}}
{{--                                                                class="pull-right">{{$akhtiyariKyc['submitted_by']}}</a>--}}
{{--                                                        </li>--}}
{{--                                                        <li class="list-group-item">--}}
{{--                                                            <b>Kyc For</b> <a--}}
{{--                                                                class="pull-right">{{$akhtiyariKyc['kyc_for']}}</a>--}}
{{--                                                        </li>--}}
{{--                                                        <li class="list-group-item">--}}
{{--                                                            <b>Name(Devanagari)</b> <a--}}
{{--                                                                class="pull-right">{{$akhtiyariKyc['name_in_devanagari']}}</a>--}}
{{--                                                        </li>--}}
{{--                                                        <li class="list-group-item">--}}
{{--                                                            <b>Name(English)</b> <a--}}
{{--                                                                class="pull-right">{{$akhtiyariKyc['name_in_english']}}</a>--}}
{{--                                                        </li>--}}
{{--                                                        <li class="list-group-item">--}}
{{--                                                            <b>Gender</b> <a--}}
{{--                                                                class="pull-right">{{$akhtiyariKyc['gender']}}</a>--}}
{{--                                                        </li>--}}
{{--                                                        <li class="list-group-item">--}}
{{--                                                            <b>Educational Qualification</b> <a--}}
{{--                                                                class="pull-right">{{$akhtiyariKyc['educational_qualification']}}</a>--}}
{{--                                                        </li>--}}
{{--                                                        <li class="list-group-item">--}}
{{--                                                            <b>Marital Status</b> <a--}}
{{--                                                                class="pull-right">{{$akhtiyariKyc['marital_status']}}</a>--}}
{{--                                                        </li>--}}
{{--                                                        <li class="list-group-item">--}}
{{--                                                            <b>Pan No</b> <a--}}
{{--                                                                class="pull-right">{{$akhtiyariKyc['pan_no']}}</a>--}}
{{--                                                        </li>--}}
{{--                                                    </ul>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}

{{--                                        </div>--}}
{{--                                        <!-- /.box-body -->--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-md-6">--}}
{{--                                    <div class="box box-success">--}}
{{--                                        <div class="box-header with-border">--}}

{{--                                            <span style="font-size: 15px;" class="label label-warning">--}}
{{--                                                 Family Detail--}}
{{--                                             </span>--}}

{{--                                        </div>--}}
{{--                                        <div class="box-body">--}}
{{--                                            <ul class="list-group list-group-unbordered">--}}

{{--                                                <li class="list-group-item">--}}
{{--                                                    <b>Grandfather</b> <a class="pull-right">--}}
{{--                                                        {{$akhtiyariKyc['kyc_family_detail']['grand_father_name']}}--}}
{{--                                                    </a>--}}
{{--                                                </li>--}}
{{--                                                <li class="list-group-item">--}}
{{--                                                    <b>Grandmother </b> <a class="pull-right">--}}
{{--                                                        {{$akhtiyariKyc['kyc_family_detail']['grand_mother_name']}}--}}
{{--                                                    </a>--}}
{{--                                                </li>--}}
{{--                                                <li class="list-group-item">--}}
{{--                                                    <b>Father</b> <a class="pull-right">--}}
{{--                                                        {{$akhtiyariKyc['kyc_family_detail']['father_name']}}--}}
{{--                                                    </a>--}}
{{--                                                </li>--}}
{{--                                                <li class="list-group-item">--}}
{{--                                                    <b>Mother</b> <a class="pull-right">--}}
{{--                                                        {{$akhtiyariKyc['kyc_family_detail']['mother_name']}}--}}
{{--                                                    </a>--}}
{{--                                                </li>--}}
{{--                                                <li class="list-group-item">--}}
{{--                                                    <b>Spouse</b> <a class="pull-right">--}}
{{--                                                        {{$akhtiyariKyc['kyc_family_detail']['spouse_name']}}--}}
{{--                                                    </a>--}}
{{--                                                </li>--}}
{{--                                                <li class="list-group-item">--}}
{{--                                                    <b>Sons</b> <a class="pull-right">--}}

{{--                                                        @if(isset($akhtiyariKyc['kyc_family_detail']['sons']))--}}
{{--                                                            @foreach($akhtiyariKyc['kyc_family_detail']['sons'] as $i=>$value)--}}
{{--                                                                {{$value}}{{$loop->last? '': ','}}--}}
{{--                                                            @endforeach--}}
{{--                                                        @endif--}}

{{--                                                    </a>--}}
{{--                                                </li>--}}
{{--                                                <li class="list-group-item">--}}
{{--                                                    <b>Daughters</b> <a class="pull-right">--}}

{{--                                                        @if(isset($akhtiyariKyc['kyc_family_detail']['daughters']))--}}
{{--                                                            @foreach($akhtiyariKyc['kyc_family_detail']['daughters'] as $i=>$value)--}}
{{--                                                                {{$value}}{{$loop->last? '': ','}}--}}
{{--                                                            @endforeach--}}
{{--                                                        @endif--}}
{{--                                                    </a>--}}
{{--                                                </li>--}}
{{--                                                <li class="list-group-item">--}}
{{--                                                    <b>Daughter-In-Laws</b> <a class="pull-right">--}}

{{--                                                        @if(isset($akhtiyariKyc['kyc_family_detail']['daughter_in_laws']))--}}
{{--                                                            @foreach($akhtiyariKyc['kyc_family_detail']['sons'] as $i=>$value)--}}
{{--                                                                {{$value}}{{$loop->last? '': ','}}--}}
{{--                                                            @endforeach--}}
{{--                                                        @endif--}}
{{--                                                    </a>--}}
{{--                                                </li>--}}
{{--                                                <li class="list-group-item">--}}
{{--                                                    <b>Father-In-Law</b> <a class="pull-right">--}}
{{--                                                        {{$akhtiyariKyc['kyc_family_detail']['father_in_law']}}--}}
{{--                                                    </a>--}}
{{--                                                </li>--}}
{{--                                                <li class="list-group-item">--}}
{{--                                                    <b>Mother-In-Law</b> <a class="pull-right">--}}
{{--                                                        {{$akhtiyariKyc['kyc_family_detail']['mother_in_law']}}--}}
{{--                                                    </a>--}}
{{--                                                </li>--}}


{{--                                            </ul>--}}
{{--                                        </div>--}}
{{--                                        <!-- /.box-body -->--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                <div class="col-md-6">--}}
{{--                                    <div class="box box-success">--}}
{{--                                        <div class="box-header with-border">--}}

{{--                                            <span style="font-size: 15px;" class="label label-success">--}}
{{--                                                 Citizenship Detail--}}
{{--                                             </span>--}}

{{--                                        </div>--}}
{{--                                        <div class="box-body">--}}
{{--                                            <ul class="list-group list-group-unbordered">--}}

{{--                                                <li class="list-group-item">--}}
{{--                                                    <b>Citizenship No.</b> <a class="pull-right">--}}
{{--                                                        {{$akhtiyariKyc['kyc_citizenship_detail']['citizenship_no']}}--}}
{{--                                                    </a>--}}
{{--                                                </li>--}}
{{--                                                <li class="list-group-item">--}}
{{--                                                    <b>Full Name </b> <a class="pull-right">--}}
{{--                                                        {{$akhtiyariKyc['kyc_citizenship_detail']['citizenship_full_name']}}--}}
{{--                                                    </a>--}}
{{--                                                </li>--}}
{{--                                                <li class="list-group-item">--}}
{{--                                                    <b>Nationality</b> <a class="pull-right">--}}
{{--                                                        {{$akhtiyariKyc['kyc_citizenship_detail']['citizenship_nationality']}}--}}
{{--                                                    </a>--}}
{{--                                                </li>--}}
{{--                                                <li class="list-group-item">--}}
{{--                                                    <b>Gender</b> <a class="pull-right">--}}
{{--                                                        {{$akhtiyariKyc['kyc_citizenship_detail']['citizenship_gender']}}--}}
{{--                                                    </a>--}}
{{--                                                </li>--}}
{{--                                                <li class="list-group-item">--}}
{{--                                                    <b>Birth Place</b> <a class="pull-right">--}}
{{--                                                        {{$akhtiyariKyc['kyc_citizenship_detail']['citizenship_birth_place']}}--}}
{{--                                                    </a>--}}
{{--                                                </li>--}}
{{--                                                <li class="list-group-item">--}}
{{--                                                    <b>District</b> <a class="pull-right">--}}
{{--                                                        {{$akhtiyariKyc['kyc_citizenship_detail']['citizenship_district']}}--}}
{{--                                                    </a>--}}
{{--                                                </li>--}}
{{--                                                <li class="list-group-item">--}}
{{--                                                    <b>Municipality</b> <a class="pull-right">--}}
{{--                                                        {{$akhtiyariKyc['kyc_citizenship_detail']['citizenship_municipality']}}--}}
{{--                                                    </a>--}}
{{--                                                </li>--}}
{{--                                                <li class="list-group-item">--}}
{{--                                                    <b>Ward</b> <a class="pull-right">--}}
{{--                                                        {{$akhtiyariKyc['kyc_citizenship_detail']['citizenship_ward_no']}}--}}
{{--                                                    </a>--}}
{{--                                                </li>--}}
{{--                                                <li class="list-group-item">--}}
{{--                                                    <b>Date Of Birth</b> <a class="pull-right">--}}
{{--                                                        {{$akhtiyariKyc['kyc_citizenship_detail']['citizenship_dob']}}--}}
{{--                                                    </a>--}}
{{--                                                </li>--}}
{{--                                                <li class="list-group-item">--}}
{{--                                                    <b>Father's Name</b> <a class="pull-right">--}}
{{--                                                        {{$akhtiyariKyc['kyc_citizenship_detail']['citizenship_father_name']}}--}}
{{--                                                    </a>--}}
{{--                                                </li>--}}
{{--                                                <li class="list-group-item">--}}
{{--                                                    <b>Father's Address</b> <a class="pull-right">--}}
{{--                                                        {{$akhtiyariKyc['kyc_citizenship_detail']['citizenship_father_address']}}--}}
{{--                                                    </a>--}}
{{--                                                </li>--}}
{{--                                                <li class="list-group-item">--}}
{{--                                                    <b>Father's Nationality</b> <a class="pull-right">--}}
{{--                                                        {{$akhtiyariKyc['kyc_citizenship_detail']['citizenship_father_nationality']}}--}}
{{--                                                    </a>--}}
{{--                                                </li>--}}
{{--                                                <li class="list-group-item">--}}
{{--                                                    <b>Mother's Name</b> <a class="pull-right">--}}
{{--                                                        {{$akhtiyariKyc['kyc_citizenship_detail']['citizenship_mother_name']}}--}}
{{--                                                    </a>--}}
{{--                                                </li>--}}
{{--                                                <li class="list-group-item">--}}
{{--                                                    <b>Mother's Address</b> <a class="pull-right">--}}
{{--                                                        {{$akhtiyariKyc['kyc_citizenship_detail']['citizenship_mother_address']}}--}}
{{--                                                    </a>--}}
{{--                                                </li>--}}
{{--                                                <li class="list-group-item">--}}
{{--                                                    <b>Mothers's Nationality</b> <a class="pull-right">--}}
{{--                                                        {{$akhtiyariKyc['kyc_citizenship_detail']['citizenship_mother_nationality']}}--}}
{{--                                                    </a>--}}
{{--                                                </li>--}}
{{--                                                <li class="list-group-item">--}}
{{--                                                    <b>Spouse Name</b> <a class="pull-right">--}}
{{--                                                        {{$akhtiyariKyc['kyc_citizenship_detail']['citizenship_spouse_name']}}--}}
{{--                                                    </a>--}}
{{--                                                </li>--}}
{{--                                                <li class="list-group-item">--}}
{{--                                                    <b>Spouse Address</b> <a class="pull-right">--}}
{{--                                                        {{$akhtiyariKyc['kyc_citizenship_detail']['citizenship_spouse_address']}}--}}
{{--                                                    </a>--}}
{{--                                                </li>--}}
{{--                                                <li class="list-group-item">--}}
{{--                                                    <b>Spouse Nationality</b> <a class="pull-right">--}}
{{--                                                        {{$akhtiyariKyc['kyc_citizenship_detail']['citizenship_spouse_nationality']}}--}}
{{--                                                    </a>--}}
{{--                                                </li>--}}
{{--                                                <li class="list-group-item">--}}
{{--                                                    <b>Issued Date</b> <a class="pull-right">--}}
{{--                                                        {{$akhtiyariKyc['kyc_citizenship_detail']['citizenship_issued_date']}}--}}
{{--                                                    </a>--}}
{{--                                                </li>--}}


{{--                                            </ul>--}}
{{--                                        </div>--}}
{{--                                        <!-- /.box-body -->--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                <div class="col-md-6">--}}

{{--                                    <div class="col-md-6">--}}
{{--                                        <div class="box box-success">--}}
{{--                                            <div class="box-header with-border">--}}

{{--                                            <span style="font-size: 15px;" class="label label-danger">--}}
{{--                                               Permanent Address Detail--}}
{{--                                             </span>--}}

{{--                                            </div>--}}
{{--                                            <div class="box-body">--}}
{{--                                                <ul class="list-group list-group-unbordered">--}}

{{--                                                    <li class="list-group-item">--}}
{{--                                                        <b>Province No</b> <a class="pull-right">--}}
{{--                                                            {{$akhtiyariKyc['permanent_location_tree']['province']['location_name']}}--}}
{{--                                                        </a>--}}
{{--                                                    </li>--}}
{{--                                                    <li class="list-group-item">--}}
{{--                                                        <b>District </b> <a class="pull-right">--}}
{{--                                                            {{$akhtiyariKyc['permanent_location_tree']['district']['location_name']}}--}}
{{--                                                        </a>--}}
{{--                                                    </li>--}}
{{--                                                    <li class="list-group-item">--}}
{{--                                                        <b>Municipality</b> <a class="pull-right">--}}
{{--                                                            {{$akhtiyariKyc['permanent_location_tree']['municipality']['location_name']}}--}}
{{--                                                        </a>--}}
{{--                                                    </li>--}}
{{--                                                    <li class="list-group-item">--}}
{{--                                                        <b>Ward</b> <a class="pull-right">--}}
{{--                                                            {{$akhtiyariKyc['permanent_location_tree']['ward']['location_name']}}--}}
{{--                                                        </a>--}}
{{--                                                    </li>--}}
{{--                                                    <li class="list-group-item">--}}
{{--                                                        <b>Street</b> <a class="pull-right">--}}
{{--                                                            {{$akhtiyariKyc['permanent_street']}}--}}
{{--                                                        </a>--}}
{{--                                                    </li>--}}

{{--                                                    <li class="list-group-item">--}}
{{--                                                        <b>House No</b> <a class="pull-right">--}}
{{--                                                            {{$akhtiyariKyc['permanent_house_no']}}--}}
{{--                                                        </a>--}}
{{--                                                    </li>--}}

{{--                                                </ul>--}}
{{--                                            </div>--}}
{{--                                            <!-- /.box-body -->--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

{{--                                    <div class="col-md-6">--}}
{{--                                        <div class="box box-success">--}}
{{--                                            <div class="box-header with-border">--}}

{{--                                            <span style="font-size: 15px;" class="label label-danger">--}}
{{--                                               Present Address Detail--}}
{{--                                             </span>--}}

{{--                                            </div>--}}
{{--                                            <div class="box-body">--}}
{{--                                                <ul class="list-group list-group-unbordered">--}}

{{--                                                    <li class="list-group-item">--}}
{{--                                                        <b>Province No</b> <a class="pull-right">--}}
{{--                                                            {{$akhtiyariKyc['present_location_tree']['province']['location_name']}}--}}
{{--                                                        </a>--}}
{{--                                                    </li>--}}
{{--                                                    <li class="list-group-item">--}}
{{--                                                        <b>District </b> <a class="pull-right">--}}
{{--                                                            {{$akhtiyariKyc['present_location_tree']['district']['location_name']}}--}}
{{--                                                        </a>--}}
{{--                                                    </li>--}}
{{--                                                    <li class="list-group-item">--}}
{{--                                                        <b>Municipality</b> <a class="pull-right">--}}
{{--                                                            {{$akhtiyariKyc['present_location_tree']['municipality']['location_name']}}--}}
{{--                                                        </a>--}}
{{--                                                    </li>--}}
{{--                                                    <li class="list-group-item">--}}
{{--                                                        <b>Ward</b> <a class="pull-right">--}}
{{--                                                            {{$akhtiyariKyc['present_location_tree']['ward']['location_name']}}--}}
{{--                                                        </a>--}}
{{--                                                    </li>--}}
{{--                                                    <li class="list-group-item">--}}
{{--                                                        <b>Street</b> <a class="pull-right">--}}
{{--                                                            {{$akhtiyariKyc['present_street']}}--}}
{{--                                                        </a>--}}
{{--                                                    </li>--}}

{{--                                                    <li class="list-group-item">--}}
{{--                                                        <b>House No</b> <a class="pull-right">--}}
{{--                                                            {{$akhtiyariKyc['present_house_no']}}--}}
{{--                                                        </a>--}}
{{--                                                    </li>--}}

{{--                                                </ul>--}}
{{--                                            </div>--}}
{{--                                            <!-- /.box-body -->--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

{{--                                    <div class="col-md-12">--}}
{{--                                        <div class="box box-success">--}}
{{--                                            <div class="box-header with-border">--}}

{{--                                            <span style="font-size: 15px;" class="label label-danger">--}}
{{--                                               Documents--}}
{{--                                             </span>--}}

{{--                                            </div>--}}
{{--                                            <div class="box-body">--}}
{{--                                                <ul class="list-group list-group-unbordered">--}}

{{--                                                    @foreach($akhtiyariKyc['kyc_documents'] as $kycDocument)--}}
{{--                                                        <li class="list-group-item">--}}
{{--                                                            <b>{{convertToWords($kycDocument['document_type'],'_')}}</b>--}}



{{--                                                            <div class="pull-right">--}}
{{--                                                                <button class="btn btn-xs btn-info">--}}
{{--                                                                    <a style="color:white" href="{{$kycDocument['document_file']}}"--}}
{{--                                                                       class="pull-right" download>--}}
{{--                                                                        Download--}}
{{--                                                                    </a>--}}
{{--                                                                </button>--}}
{{--                                                                <button class="btn btn-xs btn-primary">--}}
{{--                                                                    <a style="color:white" href="{{$kycDocument['document_file']}}"--}}
{{--                                                                       class="pull-right" target="_blank">--}}
{{--                                                                        View--}}
{{--                                                                    </a>--}}
{{--                                                                </button>--}}
{{--                                                            </div>--}}

{{--                                                        </li>--}}
{{--                                                    @endforeach--}}

{{--                                                </ul>--}}
{{--                                            </div>--}}
{{--                                            <!-- /.box-body -->--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

{{--                                </div>--}}

{{--                                <div class="col-md-12">--}}

{{--                                    <div class="box box-success">--}}
{{--                                        <div class="box-header with-border">--}}
{{--                                            <span style="font-size: 15px;" class="label label-danger">--}}
{{--                                               Bank Details--}}
{{--                                             </span>--}}
{{--                                        </div>--}}

{{--                                        @foreach($akhtiyariKyc['kyc_banks_detail'] as $bankDetail)--}}

{{--                                            <div class="col-md-6">--}}
{{--                                                <div class="box-body">--}}
{{--                                                    <strong>Bank Code : {{$bankDetail['bank_code']}}</strong><br>--}}
{{--                                                    <strong>Bank Name : {{$bankDetail['bank_name']}}</strong><br>--}}
{{--                                                    <strong>Branch Name--}}
{{--                                                        : {{$bankDetail['bank_branch_name']}}</strong><br>--}}
{{--                                                    <strong>Account No : {{$bankDetail['bank_account_no']}}</strong><br>--}}
{{--                                                    <strong>Account Holder Name--}}
{{--                                                        : {{$bankDetail['bank_account_holder_name']}}</strong>--}}

{{--                                                    <hr>--}}
{{--                                                </div>--}}
{{--                                                <!-- /.box-body -->--}}
{{--                                            </div>--}}
{{--                                        @endforeach--}}

{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-md-12">--}}
{{--                                    <input hidden id="latitude" name="latitude" value="{{$akhtiyariKyc['latitude']}}">--}}
{{--                                    <input hidden id="longitude" name="longitude" value="{{$akhtiyariKyc['longitude']}}">--}}
{{--                                    <div id="map-location"></div>--}}
{{--                                </div>--}}

{{--                            </div>--}}
{{--                        </div>--}}
{{--                @endcan--}}
{{--                    </div>--}}
{{--                    <!-- /.box -->--}}
{{--                </div>--}}
{{--                <!--/.col (left) -->--}}

{{--            </div>--}}
{{--            <!-- /.row -->--}}
{{--        </section>--}}
{{--        <!-- /.content -->--}}
{{--    </div>--}}
{{--</div>--}}
{{--<div class="row" style="padding: 20px 0;">--}}
{{--    <div class="col-md-12 text-center">--}}
{{--        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>--}}
{{--    </div>--}}
{{--</div>--}}
