@extends('Admin::layout.common.masterlayout')

@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
   'page_title'=> formatWords($title,true),
   'sub_title'=>'Manage '. formatWords($title,true),
   'icon'=>'home',
   'sub_icon'=>'',
   'manage_url'=>route($base_route.'.index',['warehousePreOrderListingCode'=>$warehousePreOrder->warehouse_preorder_listing_code]),
   ])
    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                           @include(''.$module.'.admin.store-preorder.filter-form')
                        </div>
                    </div>

                </div>
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of {{  formatWords($title,true)}} / Pre Order Name: {{$warehousePreOrder->pre_order_name}}  ({{$warehousePreOrder->warehouse_preorder_listing_code}})/ Start Date: {{getReadableDate($warehousePreOrder->start_time)}} / End Date: {{getReadableDate($warehousePreOrder->end_time)}}
                            </h3>
                        </div>

                        <div class="box-body">

                            <table id="{{ $base_route }}-table" class="table table-bordered table-striped"
                                   cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Pre Order Code</th>
                                    <th>Total Price</th>
                                    <th>Store Name</th>
                                    <th>Payment Status</th>
                                    <th>Delivery Status</th>
                                    <th>Order Date</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($storePreOrders as $storePreOrder)
                                    <tr>
                                        <td>{{++$loop->index}}</td>
                                        <td>{{$storePreOrder->store_preorder_code}}</td>
                                        <td>{{getNumberFormattedAmount($storePreOrder->total_price)}}</td>
                                        <td>{{$storePreOrder->store->store_name }} ({{$storePreOrder->store_code}})</td>
                                        <td> <span class="label label-{{($storePreOrder->payment_status) ? 'success' : 'danger'}}">{{ ($storePreOrder->payment_status) ? 'Paid' : 'UnPaid' }}</span></td>
                                        <td>{{$storePreOrder->status}}</td>
                                        <td>{{ getNepTimeZoneDateTime($storePreOrder->created_at) }}</td>
                                        <td>
                                            @can('Show Store Pre Order')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Details ',route('admin.store.pre-orders.show', $storePreOrder->store_preorder_code),'Details', 'eye','info')!!}
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
                            {{$storePreOrders->appends($_GET)->links()}}
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
@endsection
