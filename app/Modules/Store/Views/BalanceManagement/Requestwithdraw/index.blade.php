
@extends('Admin::layout.common.masterlayout')

@section('content')
    <div class="content-wrapper">

    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=> formatWords($title,true),
    'sub_title'=>'Manage '. formatWords($title,true),
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route.'withdraw'),
    ])
    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div class="row">



                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of Withdraw Request Of Stores
                            </h3>
                        </div>


                        <div class="box-body">

                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">

                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Store Name</th>
                                    <th>Requested Amount</th>
                                    <th>Reason</th>

                                    <th>Verification status</th>

                                    <th>Requested Date</th>

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
                                            <td>{{$withdrawrequest->store->store_name}}</td>

                                            <td>Rs. {{ getNumberFormattedAmount($withdrawrequest->requested_amount) }}</td>
                                            <td>{{substr($withdrawrequest->reason,0,47)}} </td>



                                            <td>

                                                    <span class="label label-{{$statusColors[$withdrawrequest->status]}}">{{ucfirst($withdrawrequest->status)}}</span>

                                            </td>



                                            <td>

                                                {{ date("d-M-Y",strtotime($withdrawrequest->created_at)) }}
                                            </td>
                                            <td>

                                                @canany('View Store Balance Withdraw Detail')
                                                    {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('View ', route('admin.stores.balance-withdrawRequest.show',$withdrawrequest->store_balance_withdraw_request_code ),'Verify Withdraw Request', 'eye','primary')!!}
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
                            {{ $allwithdrawrequest->links() }}
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
    @includeIf('Store::admin.scripts.store-filter-script');
@endpush
