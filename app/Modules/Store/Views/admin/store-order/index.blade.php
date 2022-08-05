@extends('Admin::layout.common.masterlayout')

@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
   'page_title'=> formatWords($title,true),
   'sub_title'=>'Manage '. formatWords($title,true),
   'icon'=>'home',
   'sub_icon'=>'',
   'manage_url'=>route($base_route.'.index'),
   ])
    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default">

                        <div class="panel-body">
                            @include(''.$module.'.admin.store-order.filter-form')
                        </div>
                    </div>

                </div>
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of {{  formatWords($title,true)}}
                            </h3>
                            <h3 class="panel-title pull-right">
                                <a class="btn btn-sm btn-primary" href="{{route('admin.store.orders.exportExcelStoreOrder') }}">Download Excell Bill</a>
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
                                    <th>Warehouse Name</th>
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
                                        <td>{{$storeOrder->acceptable_amount ? getNumberFormattedAmount($storeOrder->acceptable_amount): 'N/A' }}</td>
{{--                                        <td><a href="#" data-toggle="tooltip" data-placement="top" title="--}}
{{--                                        <div>Owner:{{$storeOrder->store->store_owner}}<div>--}}
{{--                                        <div>Phone:{{$storeOrder->store->store_contact_phone}}<div>--}}
{{--                                        <div>Mobile:{{$storeOrder->store->store_contact_mobile}}<div>--}}
{{--                                        <div>Location:{{$storeOrder->store->store_full_location}}<div>--}}
{{--                                             ">{{$storeOrder->store->store_name}}</a>--}}
{{--                                        </td>--}}
                                        <td>{{$storeOrder->store->store_name}}
                                            <div>Contact: {{isset($storeOrder->store->store_contact_phone) ? $storeOrder->store->store_contact_phone .',' : ''}}{{$storeOrder->store->store_contact_mobile}}</div>
                                            <div>Location: {{$storeOrder->store->store_full_location}}</div>
                                        </td>
                                        <td>{{ $storeOrder->warehouse->warehouse_name }}</td>
                                        <td>{{$storeOrder->delivery_status}}</td>
                                        <td>{{ date($storeOrder->created_at) }}</td>

                                        <td>

                                            @can('Show Store Order')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Details ',route('admin.store.orders.show', $storeOrder->store_order_code),'Details', 'eye','info')!!}
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
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush
