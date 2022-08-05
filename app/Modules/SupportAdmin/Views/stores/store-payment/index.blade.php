  <div class="card card-default bg-panel">
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default">
                        <h2 class="panel-title">
                         <strong> Payment Of {{$filterParameters['store_name']}}({{$storeCode}}) </strong>
                        </h2>
                </div>
            </div>
            <div class="col-xs-12">
                <div class="panel panel-default">
                    <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>#</th>
                            {{--                            <th>Store</th>--}}
                            <th>Payment For</th>
                            <th>Amount</th>
                            <th>Last Verification Status</th>
                            <th>Last Created Date</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $status = ['pending'=>'warning','verified'=>'success','rejected'=>'danger'];
                        @endphp
                        @forelse($storePayments as $i => $storePayment)
                            <tr>
                                <td>{{++$i}}</td>
                                {{--                                <td>{{$storePayment->store_name}}-{{($storePayment->store_code)}}</td>--}}
                                <td>{{convertToWords($storePayment->payment_for,'_')}}</td>
                                <td>
                                    <span style="padding: 0.0987em .5em .2em;" class="label label-warning">Pending</span> :  {{getNumberFormattedAmount(roundPrice($storePayment->Pending))}}<br/>
                                    <span style="padding: 0.0987em .5em .2em;" class="label label-success">Verified</span> :  {{getNumberFormattedAmount(roundPrice($storePayment->Verified))}}<br/>
                                    <span style="padding: 0.0987em .5em .2em;" class="label label-danger">Rejected</span> : {{getNumberFormattedAmount(roundPrice($storePayment->Rejected))}}<br/>

                                </td>
                                <td> <span class="label label-{{$status[$storePayment->lastStatus]}}">{{convertToWords($storePayment->lastStatus)}}</span></td>
                                <td> {{getReadableDate(getNepTimeZoneDateTime($storePayment->lastPaymentDate))}}</td>
                                <td>

                                    <a>
                                        <button value="{{$storePayment->store_code}}" data-url="{{route('support-admin.store-payment.list',['storeCode'=> $storePayment->store_code,'paymentFor'=>$storePayment->payment_for])}}"  id="store_payment_list" data-placement="left" data-tooltip="true" title="Details" class="btn btn-xs btn-info">
                                            <span class="fa fa-eye"></span>
                                            view
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

                </div>
            </div>
        </div>
    </div>











