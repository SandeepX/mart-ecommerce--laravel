<section class="content">

@include('SupportAdmin::layout.partials.flash_message')
<div class="row">
    <div class="col-xs-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">
                   Withdraw Request Of {{$filterData['store_name']}}({{$storeCode}})
                </h3>
            </div>

            <div class="box-body">
                <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>#</th>
{{--                        <th>Store</th>--}}
                        <th>Amount</th>
                        <th>Last Verification Status</th>
                        <th>Last Created Date</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $status = ['pending'=>'warning','verified'=>'success','rejected'=>'danger','completed'=>'success','cancelled'=>'danger'];
                    @endphp
                    @forelse($allwithdrawrequest as $i => $withdrawrequest)
                        <tr>
                            <td>{{++$i}}</td>
{{--                            <td>{{$withdrawrequest->store->store_name}}-{{($withdrawrequest->store_code)}}</td>--}}
                            <td>
                                <span style="padding: 0.0987em .5em .2em;" class="label label-warning">Pending</span> :  {{getNumberFormattedAmount(roundPrice($withdrawrequest->pending))}}<br/>
                                <span style="padding: 0.0987em .5em .2em;" class="label label-success">Processing</span> :  {{getNumberFormattedAmount(roundPrice($withdrawrequest->processing))}}<br/>
                                <span style="padding: 0.0987em .5em .2em;" class="label label-danger">Rejected</span> : {{getNumberFormattedAmount(roundPrice($withdrawrequest->rejected))}}<br/>
                                <span style="padding: 0.0987em .5em .2em;" class="label label-success">Completed</span> : {{getNumberFormattedAmount(roundPrice($withdrawrequest->completed))}}<br/>

                            </td>
                            <td> <span >{{convertToWords($withdrawrequest->last_verification_status)}}</span></td>
                            <td> {{getReadableDate($withdrawrequest->last_created_at)}}</td>
                            <td>
{{--                                @can('View Store Balance Withdraw Detail')--}}
{{--                                    {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('View ', route('admin.balance.withdraw-lists',$withdrawrequest->store_code),'Verify Withdraw Request', 'eye','primary')!!}--}}
{{--                                @endcan--}}
                            <a>
                                <button value="{{$withdrawrequest->store_code}}" data-url="{{route('support-admin.store-withdraw-requests',['storeCode'=> $withdrawrequest->store_code])}}"  id="store_all_withdraw_request" data-placement="left" data-tooltip="true" title="Details" class="btn btn-xs btn-info">
                                    <span class="fa fa-eye"></span>
                                    withdraw Requests
                                </button>
                            </a>
                            </td>
                        </tr>
{{--                        store-withdraw-requests--}}
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
<!-- /.row -->
</section>
