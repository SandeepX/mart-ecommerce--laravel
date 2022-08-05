<div class="card card-default bg-panel">
        <div id="collapse2" class="collapse show">
            <div class="card-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="panel panel-default">
                            <div class="row">
                                <div class="col-md-5">
                                    <h3 style="margin-left:10px; font-weight: bold;">Store Kyc</h3>
{{--                                    <p style="margin-left: 10px;">Updated information: <a href="#">2 min ago</a></p>--}}
                                </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-xs-12">
                        <div class="panel panel-default">
                            <div class="box-body">
                                <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th rowspan="2" class="text-center">#</th>
                                        <th rowspan="2">Store</th>
{{--                                        <th>Akhtiyari</th>--}}
                                        <th>Sanchalak</th>
                                        <th>Firm</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <tr>
                                        <td><strong>1</strong></td>
                                        <td>
                                            <strong>
                                                {{$store->store_name}}-{{$store->store_code}}
                                            </strong>
                                        </td>

{{--                                        @if($akhtiyariKyc)--}}
{{--                                        <td>--}}
{{--                                            <strong> -Last Updated At:  {{$akhtiyariKyc['updated_at']}} <br/></strong>--}}
{{--                                            <strong> -Last Verification Status: <span class="label  label-success "> {{$akhtiyariKyc['verification_status']}}</span><br/></strong>--}}
{{--                                            <a data-target="#akhtiyariKyc" data-toggle="modal">--}}
{{--                                                <button data-placement="left" data-tooltip="true" title="View Kyc" class="btn btn-xs btn-primary">--}}
{{--                                                    <span class="fa fa-eye"></span>--}}
{{--                                                    View--}}
{{--                                                </button>--}}
{{--                                            </a>--}}
{{--                                            <div class="modal fade" id="akhtiyariKyc">--}}
{{--                                                <div class="modal-dialog" style="width: 80%;">--}}
{{--                                                    <div class="modal-content">--}}
{{--                                                        @include('Store::admin.store-complete-details.layout.partials.kyc.akhtiyari-modal')--}}
{{--                                                    </div>--}}
{{--                                                    <!-- /.modal-content -->--}}
{{--                                                </div>--}}
{{--                                                <!-- /.modal-dialog -->--}}
{{--                                            </div>--}}
{{--                                        </td>--}}
{{--                                        @else--}}
{{--                                            <td>-</td>--}}
{{--                                        @endif--}}
                                         @if($individualKyc)
                                        <td>
                                            <strong> -Last Updated At: {{$individualKyc['updated_at']}} <br/></strong>
                                            <strong> -Last Verification Status: <span class="label  label-success ">{{$individualKyc['verification_status']}}</span><br/></strong>
                                            <a data-target="#sanchalakKyc" data-toggle="modal">
                                                <button data-placement="left" data-tooltip="true" title="View Kyc" class="btn btn-xs btn-primary">
                                                    <span class="fa fa-eye"></span>
                                                    View
                                                </button>
                                            </a>
                                            <div class="modal fade" id="sanchalakKyc">
                                                <div class="modal-dialog" style="width: 80%;">
                                                    <div class="modal-content">
                                                        @include('Store::admin.store-complete-details.layout.partials.kyc.sanchalak-modal')
                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                                <!-- /.modal-dialog -->
                                            </div>

                                        </td>
                                        @else
                                             <td>-</td>
                                        @endif

                                        @if($firmKyc)
                                        <td>
                                            <strong> -Last Updated At:{{$firmKyc['updated_at']}}<br/></strong>
                                            <strong> -Last Verification Status: <span class="label  label-success ">{{$firmKyc['verification_status']}}</span><br/></strong>
                                            <a data-target="#firm" data-toggle="modal">
                                                <button data-placement="left" data-tooltip="true" title="View Kyc" class="btn btn-xs btn-primary">
                                                    <span class="fa fa-eye"></span>
                                                    View
                                                </button>
                                            </a>

                                            <div class="modal fade" id="firm">
                                                <div class="modal-dialog" style="width: 80%;">
                                                    <div class="modal-content">
                                                        @include('Store::admin.store-complete-details.layout.partials.kyc.firm-modal')
                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                                <!-- /.modal-dialog -->
                                            </div>


                                        </td>
                                        @else
                                        <td>-</td>
                                            @endif

                                    </tr>

                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>

{{--                <div class="row">--}}
{{--                    <div class="col-md-12 text-center">--}}
{{--                        <a href="#miscellaneous" data-toggle="tab" aria-expanded="true" class="btn btn-default">Previous</a>--}}
{{--                        <a href="#balanceManagement" data-toggle="tab" aria-expanded="true" class="btn btn-primary">Next</a>--}}
{{--                    </div>--}}
{{--                </div>--}}

            </div>
</div>


