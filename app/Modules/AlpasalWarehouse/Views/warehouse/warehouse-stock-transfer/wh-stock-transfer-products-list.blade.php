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
                                <form action="{{ route($base_route.'.products-stock-transfer-code', $stockTransferCode) }}" method="GET">
                                    @include("AlpasalWarehouse::warehouse.warehouse-stock-transfer.partials.wh-stock-transfer-products-filter-form")
                                    <div class="col-xs-12 col-md-3" style="margin-top: 25px;">
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
                                    List of Products Transferred to {{ $warehouse->warehouse_name }}
                                </h3>
                            </div>
                            <div class="box-body">
                                <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product</th>
                                        <th>Variant Name</th>
                                        <th>Sending  Quantity</th>
                                        <th>Received Quantity</th>
                                        <th>Transfer Loss</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(isset($warehouseStockTransferProductsByCode))
                                        @foreach($warehouseStockTransferProductsByCode as $key => $product)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $product->product_name.'('.$product->product_code.')' }}</td>
                                                <td>{{ $product->product_variant_name }}</td>
                                                <td>{{ ($product->sending_package) ? $product->sending_package : $product->sending_quantity }}</td>
                                                <td>{{ isset($product->received_package) ? $product->received_package : $product->received_quantity ?? 'N/A'}}</td>
                                                <td>{{ isset($product->loss_package) ? $product->loss_package :  $product->loss_quantity ?? 'N/A' }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                                {{$warehouseStockTransferProductsByCode->appends($_GET)->links()}}
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
