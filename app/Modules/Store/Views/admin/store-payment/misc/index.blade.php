@extends('Admin::layout.common.masterlayout')

@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
   'page_title'=> formatWords($title,false),
   'sub_title'=>'Manage '. formatWords($title,false),
   'icon'=>'home',
   'sub_icon'=>'',
   'manage_url'=>route($base_route.'index'),
   ])
    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')

            <br>
            <div class="row">

                <div class="col-xs-12">
                    <div class="panel panel-default">

                        <div class="panel-body">
                            <form action="{{route('admin.stores.misc-payments.index')}}" method="get">
                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label for="store_name">Store</label>
                                        <input type="text" class="form-control" name="store_name" id="store_name"
                                               value="{{$filterParameters['store_name']}}">
                                    </div>
                                </div>

                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label for="payment_for">Payment For</label>
                                        <select name="payment_for" class="form-control" id="payment_for">
                                            <option value="" {{$filterParameters['payment_for'] == ''}}>All</option>
                                            @foreach($paymentsFor as $key=>$paymentFor)
                                                <option value="{{$paymentFor}}"
                                                    {{$paymentFor == $filterParameters['payment_for'] ?'selected' :''}}>
                                                    {{ucwords($paymentFor)}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label for="payment_for">Last Verification Status</label>
                                        <select name="last_verification_status" class="form-control" id="last_verification_status">
                                            <option value="" {{$filterParameters['last_verification_status'] == ''}}>All</option>
                                            @foreach($verificationStatuses as $key=>$verificationStatus)
                                                <option value="{{$verificationStatus}}"
                                                    {{$verificationStatus == $filterParameters['last_verification_status'] ?'selected' :''}}>
                                                    {{ucwords($verificationStatus)}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xs-4">
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

                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label for="amount">Amount</label>
                                        <input type="number" min="0" class="form-control" name="amount" id="amount"
                                               value="{{$filterParameters['amount']}}">
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-block btn-primary form-control">Filter</button>
                            </form>
                        </div>
                    </div>

                </div>

                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of {{  formatWords($title,true)}}
                            </h3>


                            <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">

                            </div>

                        </div>

                        <div class="box-body">
                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Store</th>
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
                                        <td>{{$storePayment->store_name}}-{{($storePayment->store_code)}}</td>
                                        <td>{{convertToWords($storePayment->payment_for,'_')}}</td>
                                        <td>
                                            <span style="padding: 0.0987em .5em .2em;" class="label label-warning">Pending</span> :  {{getNumberFormattedAmount(roundPrice($storePayment->Pending))}}<br/>
                                            <span style="padding: 0.0987em .5em .2em;" class="label label-success">Verified</span> :  {{getNumberFormattedAmount(roundPrice($storePayment->Verified))}}<br/>
                                            <span style="padding: 0.0987em .5em .2em;" class="label label-danger">Rejected</span> : {{getNumberFormattedAmount(roundPrice($storePayment->Rejected))}}<br/>

                                        </td>
                                        <td> <span class="label label-{{$status[$storePayment->lastStatus]}}">{{convertToWords($storePayment->lastStatus)}}</span></td>
                                        <td> {{getReadableDate(getNepTimeZoneDateTime($storePayment->lastPaymentDate))}}</td>
                                        <td>
                                            @canany(['Show Store Miscellaneous Payment',
                                            'Verify Store Miscellaneous Payment'])
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('View',route('admin.stores.misc-payments-detaillog.show',[$storePayment->store_code,$storePayment->payment_for]),'View LogDetail', 'eye','primary')!!}
                                            @endcanany

                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('View Matched',route('admin.stores.misc-payments-detaillog.matched.show',[$storePayment->store_code,$storePayment->payment_for]),'View Matched LogDetail', 'eye','primary')!!}

                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('View UnMatched',route('admin.stores.misc-payments-detaillog.unmatched.show',[$storePayment->store_code,$storePayment->payment_for]),'View UnMatched LogDetail', 'eye','primary')!!}

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
                            {{$storePayments->appends($_GET)->links()}}
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
@endsection
