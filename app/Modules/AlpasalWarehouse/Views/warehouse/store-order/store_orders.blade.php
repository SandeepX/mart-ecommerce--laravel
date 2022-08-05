@extends('AdminWarehouse::layout.common.masterlayout')

@section('content')
    <div class="content-wrapper">
    @include('AdminWarehouse::layout.partials.breadcrumb',
    [
   'page_title'=> formatWords($title,true),
   'sub_title'=>'Manage '. formatWords($title,true),
   'icon'=>'home',
   'sub_icon'=>'',
   'manage_url'=>route($base_route.'.lists', $storeCode),
   ])
    <!-- Main content -->
        <section class="content">
            @include('AdminWarehouse::layout.partials.flash_message')
            <div class="row">
                @can('View WH Store Order List')
                    <div class="col-xs-12">
                        <div class="panel panel-default">

                            <div class="panel-body">
                                @include(''.$module.'warehouse.store-order.filter-form')
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    List of {{  formatWords($title,true)}} of {{$storeName->store_name}}
                                </h3>
                            </div>

                            <div class="box-body">

                                <table id="{{ $base_route }}-table" class="table table-bordered table-striped"
                                       cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Order Code</th>
                                        <th>Total Price</th>
                                        <th>Acceptable Price</th>
                                        <th>Store Name</th>
                                        <th>Payment Status</th>
                                        <th>Delivery Status</th>
                                        <th>Order Date</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($storeOrders as $i => $storeOrder)
                                        <tr>
                                            <td>{{++$i}}</td>
                                            <td>{{$storeOrder->store_order_code}}</td>
                                            <td>{{getNumberFormattedAmount($storeOrder->total_price)}}</td>
                                            <td>{{$storeOrder->acceptable_amount ? getNumberFormattedAmount($storeOrder->acceptable_amount) : 'N/A' }}</td>
                                            <td>{{$storeOrder->store->store_name}}</td>
                                            <td>{{ $storeOrder->payment_status  ? 'Paid' : 'Unpaid' }}</td>
                                            <td>{{$storeOrder->delivery_status}}</td>
                                            <td>{{ getReadableDate(getNepTimeZoneDateTime($storeOrder->created_at)) }}</td>

                                            <td>

                                                @can('Show WH Store Order')
                                                    {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Details ',route('warehouse.store.orders.show', $storeOrder->store_order_code),'Details', 'eye','info')!!}
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
                                {{$storeOrders->appends($_GET)->links()}}
                            </div>
                        </div>
                    </div>
                @endcan
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
@endsection
