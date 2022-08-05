@extends('Admin::layout.common.masterlayout')
@push('css')
    <style>
        .swal-wide{
            width:300px !important;
            height:200px !important;
        }
    </style>
@endpush
@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>$title,
    'sub_title'=> "Manage {$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route('admin.online-payments.payment-holder-type.payment-for.lists',['payment_holder_code'=>$paymentHolderType,'payment_for'=>$paymentFor]),
    ])

    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default">

                        <div class="panel-body">
                            <form action="{{route('admin.online-payments.payment-holder-type.payment-for.lists',['payment_holder_code'=>$paymentHolderType,'payment_for'=>$paymentFor])}}" method="get">

                                <div class="col-xs-3">
                                    <label for="store_name">Transaction ID</label>
                                    <input type="text" class="form-control" name="transaction_id" id="transaction_id" value="{{ isset($filterParameters['transaction_id']) ? $filterParameters['transaction_id'] : ''}}">
                                </div>

                                <div class="col-xs-3">
                                    <label for="store_name">Store Name</label>
                                    <input type="text" class="form-control" name="store_name" id="store_name" value="{{isset($filterParameters['store_name']) ? $filterParameters['store_name'] : ''}}">
                                </div>

                                <div class="col-xs-3">
                                    <label for="store_status">Payment Status</label>
                                    <select id="status" name="status" class="form-control">
                                        <option value="">
                                            All
                                        </option>
                                        <option value="pending" {{$filterParameters['status'] == 'pending' ? 'selected' : ''}}> Pending </option>
                                        <option value="verified" {{$filterParameters['status'] == 'verified' ? 'selected' : ''}}> Verified </option>
                                        <option value="rejected" {{$filterParameters['status'] == 'rejected' ? 'selected' : ''}}> Rejected </option>
                                    </select>
                                </div>
                                <br>

                                <button style="margin-top: 5px;" type="submit" class="btn btn-primary btn-sm pull-left">Filter</button>
                                <a style="margin-left: 5px;margin-top: 5px;" href="{{route('admin.online-payments.payment-holder-type.payment-for.lists',['payment_holder_code'=>$paymentHolderType,'payment_for'=>$paymentFor])}}" class="btn btn-danger btn-sm pull-left">Clear</a>

                            </form>

                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of Online {{ucfirst($paymentHolderType)}} {{ucwords(str_replace('_' ,' ',$paymentFor))}} Lists
                            </h3>
                        </div>

                        <div class="box-body">
                            <table class="table table-bordered table-striped" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Payment Code</th>
                                    <th>Transaction ID</th>
                                    <th>Initiator Name</th>
                                    <th>Wallet Name</th>
                                    <th>Amount</th>
                                    <th>TXN Type</th>
                                    <th>Status</th>
                                    <th>TXN Date</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $status = [
                                            'pending' => 'warning',
                                            'verified'=>'success',
                                            'rejected'=>'danger'
                                        ]
                                @endphp
                                @forelse($paymentLists as $i => $list)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $list->online_payment_master_code}}</td>
                                        <td>{{ $list->transaction_id }}</td>
                                        <td>
                                            {{$list->holder_name}}
                                            @if($list->link)
                                                <a href="{{$list->link}}" target="_blank">
                                                    ({{$list->initiator_code }})</a>
                                            @else
                                                {{$list->initiator_code }}
                                            @endif
                                        </td>
                                        <td>{{ $list->digitalWallet->wallet_name }}</td>
                                        <td>{{ getNumberFormattedAmount(convertPaisaToRs($list->amount))}}</td>
                                        <td>{{ ucwords(str_replace('_',' ',$list->transaction_type)) }}</td>
                                        <td > <span class="label label-{{$status[$list->status]}}">{{ ucwords($list->status) }} </span> </td>
                                        <td>{{ getReadableDate(getNepTimeZoneDateTime($list->created_at))}}</td>
                                        <td>
                                            @if($list->status ==='pending')
                                                @can('Verify Online Payment')
                                                    <a class="btn btn-info btn-xs reverify" href="{{route('admin.wallet.online-payment.load-balance.respond',$list->online_payment_master_code)}}">
                                                        <i class="fa fa-refresh"></i>Reverify
                                                    </a>
                                                @endcan
                                            @else
                                                N/A
                                            @endif
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
                            {{$paymentLists->appends($_GET)->links()}}

                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>



@endsection
@push('scripts')
    <script>
        $('.reverify').on('click',function (event){
            event.preventDefault();
            var url = $(this).attr('href');
            Swal.fire({
                title: 'Are You Sure You Want To Reverify?',
                text:'Once done it cannot revert',
                showCancelButton: true,
                customClass: 'swal-wide',
                cancelButtonColor: '#d33',
                confirmButtonText: `OK`,
                cancelButtonText: `No`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });

        });
    </script>
@endpush
