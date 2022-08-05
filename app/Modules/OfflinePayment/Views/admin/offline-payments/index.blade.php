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
                    <div class="panel-group">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <strong >
                                    FILTER OFFLINE PAYMENTS
                                </strong>

                                <div class="btn-group pull-right" role="group" aria-label="...">
                                    <button style="margin-top: -5px;" data-toggle="collapse" data-target="#filter" type="button" class="btn btn-sm">
                                        <strong>Filter</strong> <i class="fa fa-filter"></i>
                                    </button>
                                </div>
                            </div>
                                <div class="panel-body" >
                                    <div class="panel panel-default">
                                        <div class="collapse" id="filter">
                                            <div class="panel-body" >
                                                <form action="{{route('admin.offline-payment.index')}}" method="get">

                                                    <div class="col-xs-3">
                                                        <div class="form-group">
                                                            <label for="payment_code">Payment Code</label>
                                                            <input type="text" class="form-control" name="payment_code" id="payment_code"
                                                                   value="{{$filterParameters['payment_code']}}">
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-3">
                                                        <div class="form-group">
                                                            <label for="payment_for">Payment For </label>
                                                            <select name="payment_for" class="form-control" id="payment_for">
                                                                <option value=""{{$filterParameters['payment_for'] == ''}}>All</option>
                                                                @foreach($paymentFor as $key => $value)
                                                                    <option value="{{$value}}"
                                                                        {{$value == $filterParameters['payment_for'] ?'selected' :''}}>
                                                                        {{ucwords($value)}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-3">
                                                        <div class="form-group">
                                                            <label for="user_type">User Type</label>
                                                            <input type="text" class="form-control" name="user_type" id="user_type"
                                                                   value="{{$filterParameters['user_type']}}">
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
                                                            <label for="has_matched">Has Matched </label>
                                                            <select name="has_matched" class="form-control" id="has_matched">
                                                                <option value=""{{$filterParameters['has_matched'] == ''}}>All</option>
                                                                <option value="1"{{($filterParameters['has_matched'] == '1') ? 'selected':''}}>True</option>
                                                                <option value="0"{{ ($filterParameters['has_matched'] == '0') ? 'selected':''}}>False</option>
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
                                                                @foreach($paymentStatus as $key=>$status)
                                                                    <option value="{{$status}}"
                                                                        {{$status == $filterParameters['payment_status'] ?'selected' :''}}>
                                                                        {{ucwords($status)}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>


                                                    <button type="submit" id="submit" class="btn btn-block btn-primary form-control">Filter</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>

                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of All offline Payments
                            </h3>
                            <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">

                            </div>
                        </div>

                        <div class="box-body">
                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Payment Code</th>
                                    <th>Payment For</th>
                                    <th>Payment Type</th>
                                    <th>Amount</th>
                                    <th>User Type</th>
                                    <th>Deposited By</th>
                                    <th>Payment Date</th>
                                    <th>Verify Status</th>
                                    <th>Has Matched</th>
                                    <th>Action</th>
                                    <th>Admin Remarks</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $hasMatched = [
                                             0 => 'danger',
                                             1 => 'success'
                                    ];
                                @endphp

                                @forelse($allOfflinePayments as $i => $offlinePayment)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>{{$offlinePayment->store_misc_payment_code}}</td>
                                        <td>{{convertToWords($offlinePayment->payment_for,'_')}}</td>
                                        <td>{{convertToWords($offlinePayment->payment_type)}}</td>
                                        <td>{{getNumberFormattedAmount(($offlinePayment->amount))}}</td>
                                        <td>{{(ucfirst($offlinePayment->submittedBy->userType->user_type_name))}}</td>
                                        <td>{{ucwords($offlinePayment->deposited_by)}}</td>
                                        <td>{{ date($offlinePayment->created_at) }}</td>
                                        <td>
                                            @if($offlinePayment->isVerified())
                                                <span class="label label-success">Verified</span>
                                            @elseif($offlinePayment->isRejected())
                                                <span class="label label-danger">Rejected</span>
                                            @else
                                                <span class="label label-warning">Pending</span>
                                            @endif

                                        </td>
                                        <td><span class="label label-{{$hasMatched[$offlinePayment->has_matched]}}">{{ ($offlinePayment->has_matched) ? 'True' : 'False'}}</span></td>
                                        <td>
                                            @canany(['Show offline Payment',
                                            'Verify Offline Payment'])
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('View ',route('admin.offline-payment.show', $offlinePayment->store_misc_payment_code),'View Detail', 'eye','primary')!!}
                                            @endcanany

                                            <a data-href="{{route('admin.stores.misc-payments.edit.payments',$offlinePayment->store_misc_payment_code)}}" class="btn btn-primary btn-xs view-remarks" data-toggle="modal" data-target="#misPaymentsRemarks">Edit</a>

                                        </td>
                                        <td>
                                            <a data-href="{{route('admin.stores.misc-payments.remarks.list',$offlinePayment->store_misc_payment_code)}}" class="btn btn-info btn-xs view-remarks" data-toggle="modal" data-target="#misPaymentsRemarks">View</a>
                                            <a data-href="{{route('admin.stores.misc-payments.remarks.create',$offlinePayment->store_misc_payment_code)}}" class="btn btn-info btn-xs add-remarks" data-toggle="modal" data-target="#misPaymentsRemarks">Add</a>
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

                            {{$allOfflinePayments->appends($_GET)->links()}}

                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>

    <div class="modal fade" id="misPaymentsRemarks" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function(){
            $('a[data-toggle="modal"]').click(function() {
                var target = $(this).attr('data-target');
                $(`${target} .modal-content`).html('');
                let url = $(this).attr('data-href');
                $(`${target} .modal-content`).load(url, function(result) {
                    $(target).show();
                });
            });
        });
    </script>
@endpush
