@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>$title,
    'sub_title'=> "Manage {$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route.'index'),
    ])

    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div class="row">

                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of {{$warehouse->warehouse_name}} Pre-Orders
                            </h3>

                            <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                <a href="{{ route($base_route .'index') }}" style="border-radius: 0px; " class="btn btn-sm btn-info">
                                    <i class="fa fa-plus-circle"></i>
                                    Back To Warehouse List
                                </a>
                            </div>

                        </div>


                        <div class="box-body">

                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>PreOrder Name</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Finalization Time</th>
                                    <th>No Of Products</th>
                                    <th>Active</th>
                                    <th>Status Type</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($preOrders as $i => $preOrder)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>{{$preOrder->pre_order_name}}</td>
                                        <td>{{getReadableDate($preOrder->start_time)}}</td>
                                        <td>{{getReadableDate($preOrder->end_time)}}</td>
                                        <td>{{getReadableDate($preOrder->finalization_time)}}</td>
                                        <td>{{$preOrder->warehouse_pre_order_products_count}}</td>
                                        <td>
                                            @if($preOrder->is_active)
                                                @php
                                                    $activeStatus = 'Deactivate';
                                                @endphp
                                                <span class="label label-success">On</span>
                                            @else
                                                @php
                                                    $activeStatus = 'Activate';
                                                @endphp
                                                <span class="label label-danger">Off</span>
                                            @endif
                                        </td>
                                        <td>
                                                <span class="label label-{{returnLabelColor($preOrder->status_type)}}">
                                                    {{ucwords($preOrder->status_type)}}
                                                </span>

                                        </td>
                                        <td>{{date("Y-m-d",strtotime($preOrder->created_at))}}</td>
                                        @can('View Vendor Lists Pre Order Of Warehouse Having Pre Orders')
                                        <td>
                                            {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Details ',route('admin.warehouse-pre-orders.vendors-list',[
                                              'warehousePreOrderListingCode'=>$preOrder->warehouse_preorder_listing_code
                                              ]),'View', 'eye','info')!!}

                                            {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Store PreOrders ',route('admin.warehouse.listings.store.pre-orders.index',[
                                              'warehousePreOrderListingCode'=>$preOrder->warehouse_preorder_listing_code
                                              ]),'View', 'eye','success')!!}
                                        </td>
                                        @endcan
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

                            {{$preOrders->appends($_GET)->links()}}
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
@endsection
