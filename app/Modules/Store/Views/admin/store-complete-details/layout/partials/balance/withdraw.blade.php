<div class="">

    <div class="panel panel-default" style="margin-top: 20px;">
        <div class="row">
            <div class="col-md-6">
                <h4 style="margin-left:10px; font-weight: bold;">List of Withdraw Request Of Stores</h4>
{{--                <p style="margin-left: 10px;">Updated information: <a href="#">2 min ago</a></p>--}}
            </div>
            <div class="col-md-6">
                <h4 style="font-weight: bold;">{{$storeWithdrawRequests->total()}}</h4>
                <p>Total Withdraw Request Of Stores</p>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="box-body" id="withdraw-request-table">
            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Withdraw Request Code</th>
                    <th>Requested Amount</th>
                    <th>Account No</th>
                    <th>Payment Method</th>
                    <th>Paymen Body Name</th>
                    <th>Reason</th>
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
                                    'rejected'=>'danger'
                                ];
                @endphp

                @forelse($storeWithdrawRequests as $key =>$withdrawrequest)
                    <tr>
                        <td>{{++$key}}</td>
                        <td>{{$withdrawrequest->store_balance_withdraw_request_code}}</td>

                        <td>Rs. {{ getNumberFormattedAmount($withdrawrequest->requested_amount) }}</td>
                        <td>{{$withdrawrequest->account_no}} </td>
                        <td>{{ucwords($withdrawrequest->payment_method)}} </td>
                        <td>{{ucwords($withdrawrequest->getPaymentBodyName())}} </td>
                        <td>{{substr($withdrawrequest->reason,0,47)}} </td>
                        <td>
                            <span class="label label-{{$statusColors[$withdrawrequest->status]}}">{{ucfirst($withdrawrequest->status)}}</span>
                        </td>
                        <td>
                            {{ getReadableDate(getNepTimeZoneDateTime($withdrawrequest->created_at),'Y-M-d') }}
                        </td>

                        <td>

                            @canany('Verify Store Balance Withdraw Request')
                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('View ', route('admin.stores.balance-withdrawRequest.show',$withdrawrequest->store_balance_withdraw_request_code ),'Verify Withdraw Request', 'eye','primary')!!}
                            @endcanany

                        </td>

{{--                        <td>--}}

{{--                            <a href="#">--}}
{{--                                <button data-toggle="modal" id="withdraw_view_btn" value="{{$withdrawrequest->store_balance_withdraw_request_code}}" data-url="{{route('admin.store.withdraw.detail',['withRequestCode'=> $withdrawrequest->store_balance_withdraw_request_code])}}" data-target="#withdraw-modal" data-placement="left" data-tooltip="true" title="Verify Withdraw Request" class="btn btn-xs btn-primary">--}}
{{--                                    <span class="fa fa-eye"></span>--}}
{{--                                    View--}}
{{--                                </button>--}}
{{--                            </a>--}}
{{--                            <!-- Modal -->--}}
{{--                            <div class="modal fade" id="withdraw-modal">--}}
{{--                                <div class="modal-dialog" style="width: 50%; height: 90vh; overflow: scroll; background-color: white;">--}}
{{--                                    <div class="withdraw-detail-modal-content" >--}}

{{--                                    </div>--}}
{{--                                    <!-- /.modal-content -->--}}
{{--                                </div>--}}
{{--                                <!-- /.modal-dialog -->--}}
{{--                            </div>--}}

{{--                        </td>--}}
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
            <div class="pagination" id="withdraw-pagination">
                @if(isset($storeWithdrawRequests))
                    {{$storeWithdrawRequests->appends($_GET)->links()}}
                @endif
            </div>
        </div>
    </div>
</div>











































