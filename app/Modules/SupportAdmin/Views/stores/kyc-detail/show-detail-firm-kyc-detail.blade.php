

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span></button>
    <h4 class="modal-title">Store Firm Kyc Detail</h4>
</div>

<div class="box-body">
    <div class="col-md-12">
        <div class="col-md-6">
            <div class="box box-success">
                <div class="box-header with-border">

                                            <span style="font-size: 15px;" class="label label-primary">
                                                 Business Detail
                                             </span>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-9">
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b>Kyc code</b> <a
                                        class="pull-right">{{$firmKyc['kyc_code']}}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Store</b> <a class="pull-right">
                                        {{$firmKyc['store_name']}}
                                        - {{$firmKyc['store_code']}}
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <b>Submitted By</b> <a
                                        class="pull-right">{{$firmKyc['submitted_by']}}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Business Name</b> <a
                                        class="pull-right">{{$firmKyc['business_name']}}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Business Capital</b> <a
                                        class="pull-right">{{$firmKyc['business_capital']}}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Business Registered From</b> <a
                                        class="pull-right">{{$firmKyc['business_registered_from']}}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Business Registered Address</b> <a
                                        class="pull-right">{{$firmKyc['business_registered_address']}}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Business Pan/Vat Type</b> <a
                                        class="pull-right">{{$firmKyc['business_pan_vat_type']}}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Business {{$firmKyc['business_pan_vat_type']}} Number</b> <a
                                        class="pull-right">{{$firmKyc['business_pan_vat_number']}}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Business Registration No</b> <a
                                        class="pull-right">{{$firmKyc['business_registration_no']}}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Business Registered Date</b> <a
                                        class="pull-right">{{$firmKyc['business_registered_date']}}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Purpose Of Business</b> <a
                                        class="pull-right">{{$firmKyc['purpose_of_business']}}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Number Of Shareholders</b> <a
                                        class="pull-right">{{$firmKyc['share_holders_no']}}</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
                <!-- /.box-body -->
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="box box-success">
                    <div class="box-header with-border">

                                        <span style="font-size: 15px;" class="label label-danger">
                                           Business Address Detail
                                         </span>

                    </div>
                    <div class="box-body">
                        <ul class="list-group list-group-unbordered">

                            <li class="list-group-item">
                                <b>Province No</b> <a class="pull-right">
                                    {{$firmKyc['store_location_tree']['province']['location_name']}}
                                </a>
                            </li>
                            <li class="list-group-item">
                                <b>District </b> <a class="pull-right">
                                    {{$firmKyc['store_location_tree']['district']['location_name']}}
                                </a>
                            </li>
                            <li class="list-group-item">
                                <b>Municipality</b> <a class="pull-right">
                                    {{$firmKyc['store_location_tree']['municipality']['location_name']}}
                                </a>
                            </li>
                            <li class="list-group-item">
                                <b>Ward</b> <a class="pull-right">
                                    {{$firmKyc['store_location_tree']['ward']['location_name']}}
                                </a>
                            </li>
                            <li class="list-group-item">
                                <b>Address</b> <a class="pull-right">
                                    {{$firmKyc['business_registered_address']}}
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
                                               Documents
                                             </span>

                    </div>
                    <div class="box-body">
                        <ul class="list-group list-group-unbordered">

                            @foreach($firmKyc['kyc_documents'] as $kycDocument)
                                <li class="list-group-item">
                                    <b>{{convertToWords($kycDocument['document_type'],'_')}}</b>


                                    <div class="pull-right">
                                        <button class="btn btn-xs btn-info">
                                            <a style="color:white" href="{{$kycDocument['document_file']}}"
                                               class="pull-right" download>
                                                Download
                                            </a>
                                        </button>

                                        @if(hasImageExtension($kycDocument['document_file']))
                                            <button class="btn btn-xs btn-primary">
                                                <a style="color:white" href="{{$kycDocument['document_file']}}"
                                                   class="pull-right" target="_blank">
                                                    View
                                                </a>
                                            </button>
                                        @endif

                                    </div>


                                </li>
                            @endforeach

                        </ul>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>
        <div class="col-md-12">

            <div class="box box-success">
                <div class="box-header with-border">
                                            <span style="font-size: 15px;" class="label label-danger">
                                               Bank Details
                                             </span>
                </div>

                @foreach($firmKyc['kyc_banks_detail'] as $bankDetail)

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
            <input hidden id="latitude" name="latitude" value="{{$firmKyc['business_address_latitude']}}">
            <input hidden id="longitude" name="longitude" value="{{$firmKyc['business_address_longitude']}}">
            <div id="map-location"></div>
        </div>
    </div>
</div>

@push('scripts')
    @include('Store::admin.kyc.common.scripts.map-scripts')
    @include('Store::admin.kyc.common.scripts.kyc-scripts')
@endpush
