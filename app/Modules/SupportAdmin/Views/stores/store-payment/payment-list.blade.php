<div class="card card-default bg-panel">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="row">
                    <div class="col-md-5">
                        <h3 style="margin-left:10px; font-weight: bold;">List of Store Payment</h3>
                    </div>

                    <div class="col-md-7 text-right">
                        <a class="btn btn-danger mr-3 mt-3 "
                            data-toggle="collapse" href="#collapseFilter" role="button" aria-expanded="false"
                            aria-controls="collapseExample">
                            <i class="fa  fa-filter"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="panel panel-default collapse" id="collapseFilter" style="background-color: #E4E4E4">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form id="payment_filter_form" action="{{route('support-admin.store-payment.list',[$store->store_code,$paymentFor])}}" method="get">
                                @csrf
                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="payment_code">Payment Code</label>
                                        <input type="text" class="form-control" name="payment_code" id="payment_code"
                                               value="{{$filterParameters['payment_code']}}">
                                    </div>
                                </div>

                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="payment_type">Payment Type</label>
                                        <select name="payment_type" class="form-control" id="payment_type">
                                            <option value=""{{$filterParameters['payment_type'] == ''}}>All</option>
                                            @foreach($paymentsTypes as $key=>$paymentType)
                                                <option value="{{$paymentType}}"
                                                    {{$paymentType == $filterParameters['payment_type'] ?'selected' :''}}>
                                                    {{ucwords($paymentType)}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="amount_condition">Amount Condition</label>
                                        <select name="amount_condition" class="form-control" id="amount_condition">
                                            <option value="" {{$filterParameters['amount_condition'] == ''}}>All</option>
                                            @foreach($amountConditions as $key=>$amountCondition)
                                                <option value="{{$amountCondition}}"
                                                    {{$amountCondition == $filterParameters['amount_condition'] ?'selected' :''}}>
                                                    {{ucwords($key)}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="amount">Amount</label>
                                        <input type="number" min="0" class="form-control" name="amount" id="amount"
                                               value="{{$filterParameters['amount']}}">
                                    </div>
                                </div>

                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="payment_date_from">Payment Date From</label>
                                        <input type="date" class="form-control" name="payment_date_from" id="payment_date_from"
                                               value="{{$filterParameters['payment_date_from']}}">
                                    </div>

                                </div>

                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="payment_date_to">Payment Date To</label>
                                        <input type="date" class="form-control" name="payment_date_to" id="payment_date_to"
                                               value="{{$filterParameters['payment_date_to']}}">
                                    </div>
                                </div>

                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="payment_status">Verify Status</label>
                                        <select name="payment_status" class="form-control" id="payment_status">
                                            <option value="" {{$filterParameters['payment_status'] == ''}}>All</option>
                                            @foreach($paymentStatus as $key=>$paymentStatus)
                                                <option value="{{$paymentStatus}}"
                                                    {{$paymentStatus == $filterParameters['payment_status'] ?'selected' :''}}>
                                                    {{ucwords($paymentStatus)}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="col-xs-3">
                                        <button type="button" class="btn btn-block btn-primary form-control" id="store-payment-filter-btn">Filter</button>
                                    </div>
                                </div>
                            </form>
                        </div>
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
                        <th>Payment Code</th>
                        <th>Payment Type</th>
                        <th>Amount</th>
                        <th>Deposited By</th>
                        <th>Payment Date</th>
                        <th>Verify Status</th>
                        <th>Action</th>
{{--                        <th>Admin Remarks</th>--}}
                    </tr>
                    </thead>
                    <tbody>

                        @forelse($storePayments as $i => $storePayment)
                            <tr>
                                <td>{{++$i}}</td>
                                {{--                                        <td>{{$storePayment->store->store_name}}-{{($storePayment->store_code)}}</td>--}}
                                {{--                                        <td>{{convertToWords($storePayment->payment_for,'_')}}</td>--}}
                                <td>{{$storePayment->store_misc_payment_code}}</td>
                                <td>{{convertToWords($storePayment->payment_type)}}</td>
                                <td>{{getNumberFormattedAmount(($storePayment->amount))}}</td>
                                <td>{{ucwords($storePayment->deposited_by)}}</td>
                                <td>{{ getReadableDate($storePayment->created_at) }}</td>
                                <td>
                                    @if($storePayment->isVerified())
                                        <span class="label label-success">Verified</span>
                                    @elseif($storePayment->isRejected())
                                        <span class="label label-danger">Rejected</span>
                                    @else
                                        <span class="label label-warning">Pending</span>
                                    @endif

                                </td>
                                <td>


                                    <a>
                                        <button data-toggle="modal" value="{{$storePayment->store_misc_payment_code}}"
                                                data-url="{{route('support-admin.store-payment.show',['miscPaymentCode'=> $storePayment->store_misc_payment_code])}}"
                                                data-target="#modal-target1"
                                                id="store_payment_detail_btn"
                                                data-placement="left" data-tooltip="true" title="Details" class="btn btn-xs btn-info">
                                            <span class="fa fa-eye"></span>
                                            Details
                                        </button>
                                    </a>
                                </td>
{{--                                <td>--}}
{{--    --}}{{--                                <a data-href="{{route('admin.stores.misc-payments.remarks.list',$storePayment->store_misc_payment_code)}}" class="btn btn-info btn-xs view-remarks" data-toggle="modal" data-target="#misPaymentsRemarks">View</a>--}}
{{--                                </td>--}}
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
                        <div class="payment-detail-modal-content" style="background-color: white" >

                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>

                <div class="pagination" id="payment-pagination">
                    @if(isset($storePayments))
                         {{$storePayments->appends($_GET)->links()}}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>









