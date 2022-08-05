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
                @can('View Received WH Stock Transfer List')
                    <div class="col-xs-12">
                        <div class="panel panel-default">
                            <div class="panel panel-body">
                                <form action="{{ route($base_route.'.received-stocks') }}" method="GET">
                                    @include("AlpasalWarehouse::warehouse.warehouse-stock-transfer.partials.received.received-stocks-filter-form")
                                    <div class="col-xs-12 col-md-3">
                                        <button type="submit" class="btn btn-primary form-control">Filter</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    List of Received {{formatWords($title, true)}}
                                </h3>
                            </div>


                            <div class="box-body">
                                <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Source Warehouse</th>
                                        <th>Remarks</th>
                                        <th>No. of Products</th>
                                        <th>Delivery Status</th>
                                        <th>Transaction Date</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(isset($warehouseStockTransfers))
                                        @foreach($warehouseStockTransfers as $key => $warehouseStockTransfer)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $warehouseStockTransfer->warehouse_name.'('.$warehouseStockTransfer->source_warehouse_code.')' }}</td>
                                                <td>{!! $warehouseStockTransfer->remarks !!}</td>
                                                <td>{{  $warehouseStockTransfer->total_products }}</td>
                                                <td>{{ ucfirst($warehouseStockTransfer->status) }}</td>
                                                <td>{{ getReadableDate(getNepTimeZoneDateTime($warehouseStockTransfer->created_at)) }}</td>
                                                <td>
                                                    <a href="{{ route($base_route.'.received-products-stock-transfer-code', $warehouseStockTransfer->stock_transfer_master_code) }}" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i> Details</a>
                                                    <a href="{{ route($base_route.'.get-received-delivery-detail', $warehouseStockTransfer->stock_transfer_master_code) }}" class="btn btn-sm btn-warning"><i class="fa fa-list"></i> More</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endcan
                <!--ends column-->
            </div>
            <!-- ends row-->
        </section>
        <!--ends section-->
    </div>
@endsection
