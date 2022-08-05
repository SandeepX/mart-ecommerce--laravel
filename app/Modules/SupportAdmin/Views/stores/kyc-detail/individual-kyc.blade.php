<div class="card card-default bg-panel">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="row">
                    <div class="col-md-5">
                        <h3 style="margin-left:10px; font-weight: bold;">Store Individual Kyc</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-12">
            <div class="panel panel-default">
                <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Store</th>
                        <th>Kyc For</th>
                        <th>Submitted At</th>
                        <th>Verify Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($individualsKyc as $i => $individualKyc)
                        <tr>
                            <td>{{++$i}}</td>
                            <td>{{$individualKyc->store->store_name}}-{{($individualKyc->store_code)}}</td>
                            <td>{{$individualKyc->kyc_for}}</td>
                            <td>
                                {{date('M j Y', strtotime($individualKyc->created_at))}}
                            </td>
                            <td>
                                @if($individualKyc->isVerified())
                                    <span class="label label-success">Verified</span>

                                    @if( $individualKyc['can_update_kyc'] == 1 )
                                        <span style="margin-left:20px!important" class="label label-info">
                                                     <strong> Kyc Update Request Allowed : {{getNepTimeZoneDateTime($individualKyc['update_request_allowed_at'])}}</strong>
                                                     </span>

                                    @endif
                                @elseif($individualKyc->isRejected())
                                    <span class="label label-danger">Rejected</span>
                                @else
                                    <span class="label label-warning">Pending</span>
                                @endif

                            </td>


                            <td>
                                <a>
                                    <button data-toggle="modal" value="{{$individualKyc->kyc_code}}" data-url="{{route('support-admin.stores-kyc.individuals.show',['kycCode'=> $individualKyc->kyc_code])}}" data-target="#modal-target1" id="kyc_individual_btn" data-placement="left" data-tooltip="true" title="Details" class="btn btn-xs btn-info">
                                        <span class="fa fa-eye"></span>
                                        Details
                                    </button>
                                </a>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="100%">
                                <p class="text-center"><b>No records found!</b></p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>

                <div class="modal fade" id="modal-target1" >
                    <div class="modal-dialog" style="width: 80% !important; height: 90vh; overflow: scroll;">
                        <div class="kyc-individual-detail-modal-content" style="background-color: white" >
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>

            </div>
        </div>
    </div>
</div>
