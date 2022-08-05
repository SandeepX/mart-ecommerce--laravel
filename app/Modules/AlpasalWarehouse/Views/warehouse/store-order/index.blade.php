@extends('AdminWarehouse::layout.common.masterlayout')

@section('content')
    <div class="content-wrapper">
    @include('AdminWarehouse::layout.partials.breadcrumb',
    [
   'page_title'=> formatWords($title,true),
   'sub_title'=>'Manage '. formatWords($title,true),
   'icon'=>'home',
   'sub_icon'=>'',
   'manage_url'=>route($base_route.'.index'),
   ])
    <!-- Main content -->
        <section class="content">
            @include('AdminWarehouse::layout.partials.flash_message')
            <div class="row">
                    <div class="col-xs-12">
                        <div class="panel panel-default">

                            <div class="panel-body">
                                @include(''.$module.'warehouse.store-order.filter-form-index')
                            </div>
                        </div>
                    </div>
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of {{  formatWords($title,true)}}
                            </h3>
                        </div>

{{--                        @can('View Store Order List')--}}
                            <div class="box-body">

                                <table id="{{ $base_route }}-table" class="table table-bordered table-striped"
                                       cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Store Name</th>
                                        <th>Last Order Date</th>
                                        <th>Last Order Status</th>
                                        <th>Total No. of Orders</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($storeOrders as $i => $storeOrder)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $storeOrder->store_name.'('.$storeOrder->store_code.')'}}</td>
                                            <td>{{ getReadableDate(getNepTimeZoneDateTime($storeOrder->created_at)) }}</td>
                                            <td>{{ $storeOrder->delivery_status }}</td>
                                            <td>{{ $storeOrder->total_orders }}</td>
                                            <td>

{{--                                                @can('View WH Store Order List')--}}
                                                    {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Details ', route('warehouse.store.orders.lists', $storeOrder->store_code),'Details', 'eye','info')!!}
{{--                                                @endcan--}}

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
{{--                        @endcan--}}
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
@endsection
