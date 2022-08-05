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
                            <form action="{{route('admin.stores.offline-order-payments.index')}}" method="get">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label for="store_name">Store</label>
                                        <input type="text" class="form-control" name="store_name" id="store_name"
                                               value="{{$filterParameters['store_name']}}">
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label for="store_order_code">Order Code</label>
                                        <input type="text" class="form-control" name="store_order_code" id="store_order_code"
                                               value="{{$filterParameters['store_order_code']}}">
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label for="payment_type">Payment Type</label>
                                        <select name="payment_type" class="form-control" id="payment_type">
                                            <option value="" {{$filterParameters['payment_type'] == ''}}>All</option>
                                            @foreach($paymentTypes as $paymentType)
                                                <option value="{{$paymentType}}"
                                                        {{$paymentType == $filterParameters['payment_type'] ?'selected' :''}}>
                                                    {{ucwords($paymentType)}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label for="payment_status">Payment Status</label>
                                        <select name="payment_status" class="form-control" id="payment_status">
                                            <option value="" {{$filterParameters['payment_status'] == ''}}>All</option>
                                            @foreach($paymentStatuses as $paymentStatus)
                                                <option value="{{$paymentStatus}}"
                                                        {{$paymentStatus == $filterParameters['payment_status'] ?'selected' :''}}>
                                                    {{ucwords($paymentStatus)}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label for="payment_date_from">Payment Date From</label>
                                        <input type="date" class="form-control" name="payment_date_from" id="payment_date_from"
                                               value="{{$filterParameters['payment_date_from']}}">
                                    </div>

                                </div>

                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label for="payment_date_to">Payment Date To</label>
                                        <input type="date" class="form-control" name="payment_date_to" id="payment_date_to"
                                               value="{{$filterParameters['payment_date_to']}}">
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
                                {{-- <a href="{{ route('admin.stores.create') }}" style="border-radius: 0px; " class="btn btn-sm btn-info">
                                     <i class="fa fa-plus-circle"></i>
                                     Add New {{$title}}
                                 </a>--}}


                            </div>



                        </div>


                        <div class="box-body">
                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Store</th>
                                    <th>Order Code</th>
                                    <th>Total Payment</th>
                                    <th>Submitted By</th>
                                    <th>Verify Status</th>
                                    <th>Responded At</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($storePayments as $i => $storePayment)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>{{$storePayment->store->store_name}}-{{($storePayment->store_code)}}</td>
                                        <td>{{$storePayment->store_order_code}}</td>
                                        <td>{{convertToWords($storePayment->payment_type)}}</td>
                                        <td>{{$storePayment->submittedBy->name}}</td>
                                        <td>
                                            @if($storePayment->isVerified())
                                                <span class="label label-success">Verified</span>
                                            @elseif($storePayment->isRejected())
                                                <span class="label label-danger">Rejected</span>
                                            @else
                                                <span class="label label-warning">Pending</span>
                                            @endif

                                        </td>
                                        <td>{{$storePayment->responded_at ? getNepTimeZoneDateTime($storePayment->responded_at)  : '-'}}</td>


                                        <td>

                                            @canany(['Show Store Order Offline Payment',
                                           'Verify Store Order Offline Payment'])
                                            {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('View ',route('admin.stores.offline-order-payments.show', $storePayment->store_offline_payment_code),'View Detail', 'eye','primary')!!}
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
