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

            <br>
            <div class="row">

                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <form action="{{route('admin.balance.withdraw')}}" method="get">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label for="store_name">Store Name </label>
                                        <input type="text" class="form-control" name="store_name" id="store_name"
                                               value="{{($filterData['store_name'])}}">
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
                                List of Withdraw Request Of Stores
                            </h3>




                        </div>

                        <div class="box-body">
                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Store</th>
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
                                        <td>{{$withdrawrequest->store->store_name}}-{{($withdrawrequest->store_code)}}</td>
                                        <td>
                                            <span style="padding: 0.0987em .5em .2em;" class="label label-warning">Pending</span> :  {{getNumberFormattedAmount(roundPrice($withdrawrequest->pending))}}<br/>
                                            <span style="padding: 0.0987em .5em .2em;" class="label label-success">Processing</span> :  {{getNumberFormattedAmount(roundPrice($withdrawrequest->processing))}}<br/>
                                            <span style="padding: 0.0987em .5em .2em;" class="label label-danger">Rejected</span> : {{getNumberFormattedAmount(roundPrice($withdrawrequest->rejected))}}<br/>
                                            <span style="padding: 0.0987em .5em .2em;" class="label label-success">Completed</span> : {{getNumberFormattedAmount(roundPrice($withdrawrequest->completed))}}<br/>

                                        </td>
                                        <td> <span >{{convertToWords($withdrawrequest->last_verification_status)}}</span></td>
                                        <td> {{getReadableDate($withdrawrequest->last_created_at)}}</td>
                                        <td>
                                            @can('View Store Balance Withdraw Detail')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('View ', route('admin.balance.withdraw-lists',$withdrawrequest->store_code),'Verify Withdraw Request', 'eye','primary')!!}
                                            @endcan
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
                            {{$allwithdrawrequest->appends($_GET)->links()}}
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
