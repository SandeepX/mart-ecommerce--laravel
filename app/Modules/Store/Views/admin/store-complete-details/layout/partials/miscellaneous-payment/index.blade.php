<div class="card card-default bg-panel">
        <div id="collapse2" class="collapse show">
            <div class="card-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="panel panel-default">
                            <div class="row">
                                <div class="col-md-5">
                                    <h3 style="margin-left:10px; font-weight: bold;">List of Miscellaneous Payments</h3>
                                </div>
                                <div class="col-md-3">
                                    <h3 style="font-weight: bold;">{{$storePayments->total() }}</h3>
                                    <p>Total Payments</p>
                                </div>

                                <div class="col-md-4">
                                    <a style="margin-top: 30px !important;" class="btn btn-danger" data-toggle="collapse" href="#collapseFilterPayment" href="#" role="button" aria-expanded="false" aria-controls="collapseExample">
                                        <i class="fa  fa-filter"></i>
                                    </a>


                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default collapse" id="collapseFilterPayment" style="background-color: #E4E4E4">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <form id="miscellaneous_filter_form" action="{{route('admin.store.miscellaneous',['storeCode'=>$storeCode])}}" method="GET">
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


                                            <div class="row">
                                                <div class="col-md-12 text-center">
                                                    <button id="miscellaneous_filter_btn" type="submit" class="btn btn-primary">Filter</button>
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
                            <div class="box-body">

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
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($storePayments as $i => $storePayment)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>{{$storePayment->store_misc_payment_code}}</td>
                                        <td>{{convertToWords($storePayment->payment_type)}}</td>
                                        <td>{{getNumberFormattedAmount(($storePayment->amount))}}</td>
                                        <td>{{ucwords($storePayment->deposited_by)}}</td>
                                        <td>{{ date($storePayment->created_at) }}</td>
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
                                            @canany(['Show Store Miscellaneous Payment',
                                                 'Verify Store Miscellaneous Payment'])
                                            <a>
                                                <button data-toggle="modal" value="{{$storePayment->store_misc_payment_code}}" data-url="{{route('admin.store.miscellaneous.details',['paymentCode'=> $storePayment->store_misc_payment_code])}}" data-target="#view-modal" data-placement="left" data-tooltip="true" title="View LogDetail" class="btn btn-xs btn-primary" id="miscellaneous-view-btn">
                                                    <span class="fa fa-eye"></span>
                                                    View
                                                </button>
                                            </a>


                                            @endcanany

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

                                    <!-- View Model -->
                                    <div class="modal fade" id="view-modal">
                                        <div class="modal-dialog" style="width: 80%; height: 95vh; overflow: auto;">
                                            <div class="miscellaneous-detail-modal-content" style="background-color: white">

                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>

                                </table>
                                <div class="pagination" id="miscellaneous-pagination">
                                    @if(isset($storePayments))
                                        {{$storePayments->appends($_GET)->links()}}
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

{{--                <div class="row">--}}
{{--                    <div class="col-md-12 text-center">--}}
{{--                        <a href="#storeOrder" data-toggle="tab" aria-expanded="true" class="btn btn-default">Previous</a>--}}
{{--                        <a href="#kyc" data-toggle="tab" aria-expanded="true" class="btn btn-primary">Next</a>--}}
{{--                    </div>--}}
{{--                </div>--}}

            </div>
        </div>
    </div>

 @include('Store::admin.store-complete-details.layout.common.scripts');


