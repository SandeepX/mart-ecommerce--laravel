@extends('AdminWarehouse::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
        @include('AdminWarehouse::layout.partials.breadcrumb', [
            'page_title' => $title,
            'sub_title' => "Manage {$title}",
            'icon' => 'home',
            'sub_icon' => '',
            'manage_url' => route($base_route.'.index'),
        ])

        <!-- Main content -->
        <section class="content">
            @include('AdminWarehouse::layout.partials.flash_message')
            <div class="row">
                @can('View WH Stock Transfer List')
                    <div class="col-xs-12">
                        <div class="panel panel-default">
                            <div class="panel panel-body">
                                <form action="{{ route($base_route.'.index') }}" method="GET">
                                    @include("AlpasalWarehouse::warehouse.warehouse-stock-transfer.partials.index-filter-form")
                                    <div class="col-xs-12 col-md-3">
                                        <button type="submit" class="btn btn-primary form-control">Filter</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endcan

                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of {{formatWords($title, true)}}
                            </h3>

                            @can('Create WH Stock Transfer')
                                <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                    <a href="{{ route($base_route.'.create') }}" style="border-radius: 0px; " class="btn btn-sm btn-primary">
                                        <i class="fa fa-plus-circle"></i>
                                        Add New {{formatWords($title, true)}}
                                    </a>
                                </div>
                            @endcan
                        </div>

                        @can('View WH Stock Transfer List')
                            <div class="box-body">
                                <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Destination Warehouse</th>
                                            <th>Remarks</th>
                                            <th>No. of Products</th>
                                            <th>Delivery Status</th>
                                            <th>Transaction Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($warehouseStockTransfers as $key => $warehouseStockTransfer)
                                            <tr>
                                                <td>{{$key + 1}}</td>
                                                <td>{{$warehouseStockTransfer->warehouse_name.'('.$warehouseStockTransfer->destination_warehouse_code.')' }}</td>
                                                <td>{!! $warehouseStockTransfer->remarks !!}</td>
                                                <td>{{  isset($warehouseStockTransfer->total_products) ? $warehouseStockTransfer->total_products : ''}}</td>
                                                <td>{{ ucfirst($warehouseStockTransfer->status) }}</td>
                                                <td>{{ getReadableDate(getNepTimeZoneDateTime($warehouseStockTransfer->created_at)) }}</td>
                                                <td>
                                                    <a href="{{ route($base_route.'.products-stock-transfer-code', $warehouseStockTransfer->stock_transfer_master_code) }}" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i> Details</a>
                                                    @if($warehouseStockTransfer->status == 'draft')
                                                        @can('Create WH Stock Transfer')
                                                            <a href="{{ route(
                                                                $base_route.'.add-products',
                                                                $warehouseStockTransfer->stock_transfer_master_code
                                                            ) }}" class="btn btn-sm btn-success"><i class="fa fa-pencil"></i> Edit</a>
                                                        @endcan
                                                    @endif
                                                    <a href="{{ route($base_route.'.get-delivery-detail', $warehouseStockTransfer->stock_transfer_master_code) }}" class="btn btn-sm btn-warning"><i class="fa fa-list"></i> More</a>
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
                                {{$warehouseStockTransfers->appends($_GET)->links()}}
                            </div>
                        @endcan
                    </div>
                </div>
                <!--ends column-->
            </div>
            <!-- ends row-->
        </section>
        <!--ends section-->
    </div>
@endsection
