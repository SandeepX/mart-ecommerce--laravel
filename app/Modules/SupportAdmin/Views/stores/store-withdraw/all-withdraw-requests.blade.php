    <div class="card card-default bg-panel">
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default">
                    <div class="row">
                        <div class="col-md-5">
                            <h3 style="margin-left:10px; font-weight: bold;">List of Store Withdraw Requests</h3>
                        </div>
                    </div>
                </div>

{{--                <div class="panel panel-default collapse" id="collapseFilter" style="background-color: #E4E4E4">--}}
{{--                    <div class="panel-body">--}}

{{--                    </div>--}}
{{--                </div>--}}

            </div>
            <div class="col-xs-12">
                <div class="panel panel-default">
                    <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Withdraw Request Code</th>
                            <th>Requested Amount</th>
                            <th>Account_no</th>
                            <th>Payment_method</th>
                            <th>Payment_body_name</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Action</th>

                        </tr>
                        </thead>
                        <tbody>
                        @php

                            $statusColors = [
                                'completed'=>'success',
                                'processing'=>'primary',
                                'pending'=>'warning',
                                'rejected'=>'danger',
                                'cancelled' => 'danger'
                            ];

                        @endphp
                        @forelse($allwithdrawrequest as $key =>$withdrawrequest)

                            <tr>
                                <td>{{++$key}}</td>
                                <td>{{$withdrawrequest->store_balance_withdraw_request_code}}</td>

                                <td>Rs. {{ getNumberFormattedAmount($withdrawrequest->requested_amount) }}</td>
                                <td>{{$withdrawrequest->account_no}} </td>
                                <td>{{ucwords($withdrawrequest->payment_method)}} </td>
                                <td>{{ucwords($withdrawrequest->getPaymentBodyName())}} </td>
                                <td>
                                    <span class="label label-{{$statusColors[$withdrawrequest->status]}}">{{ucfirst($withdrawrequest->status)}}</span>
                                </td>
                                <td>
                                    {{ getReadableDate(getNepTimeZoneDateTime($withdrawrequest->created_at),'Y-M-d') }}
                                </td>
                                <td>
                                    <a>
                                        <button data-toggle="modal" value="{{$withdrawrequest->store_balance_withdraw_request_code}}"
                                                data-url="{{route('support-admin.store-withdraw-requests.show',['withdrawRequestCode'=> $withdrawrequest->store_balance_withdraw_request_code])}}"
                                                data-target="#modal-target1"
                                                id="withdraw_view_btn"
                                                data-placement="left" data-tooltip="true" title="Details" class="btn btn-xs btn-info">
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
                            <div class="withdraw-detail-modal-content" style="background-color: white" >

                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                </div>
            </div>
        </div>
    </div>











