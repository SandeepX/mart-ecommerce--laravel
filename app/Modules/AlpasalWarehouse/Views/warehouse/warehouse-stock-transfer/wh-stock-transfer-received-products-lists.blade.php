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
                                <form action="{{ route($base_route.'.products-stock-transfer-code', $stockTransferCode) }}" method="GET">
                                    @include("AlpasalWarehouse::warehouse.warehouse-stock-transfer.partials.wh-stock-transfer-products-filter-form")
                                    <div class="col-xs-12 col-md-3" style="margin-top: 25px">
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
                                    List of Products Transferred by {{ $warehouse->warehouse_name }}
                                </h3>
                                @if(isset($warehouseStockTransfer) && $warehouseStockTransfer->status == 'sent')
                                        @can('Update Received WH Stock Transfer Products Quantity')
                                            <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                                <button type="button" class="btn btn-info " data-toggle="modal"
                                                        data-target="#receiveStockModal">
                                                   Receive Stock
                                                </button>
                                            </div>
                                        @endcan
                                @endif
                            </div>
                            <div class="box-body">
                                    <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Product</th>
                                            <th>Variant Name</th>
                                            <th>Sending Quantity</th>
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

                    <!---stock receive modal--->
                    <div class="modal fade" id="receiveStockModal" tabindex="-1" role="dialog"
                         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <form
                                    action="{{route($base_route.'.update-received-products-quantity', $stockTransferCode)}}"
                                    method="post">
                                    {{csrf_field()}}
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLongTitle">Warehouse Receive Stock Form</h5>
                                        <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">

                                        <table class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Product Name</th>
                                                <th>Stock Transfer Package</th>
{{--                                                <th>Quantity</th>--}}
                                                <th>Received Quantity</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($warehouseStockTransferProductsByCode as $i => $stockTransferDetail)
                                                <tr>
                                                    <td>{{ ++$i }}</td>
                                                    <td>
                                                        {{ $stockTransferDetail->product_name }}
                                                        <br>
                                                        <small>{{ $stockTransferDetail->product_variant_name ?? ''}}</small>
                                                        <input
                                                            type="hidden"
                                                            name="warehouse_product_master_code[]"
                                                            value="{{ $stockTransferDetail->warehouse_product_master_code }}">
                                                    </td>
                                                    <td>{{$stockTransferDetail->sending_package}}</td>
{{--                                                    <td>{{ $orderDetail->quantity }}</td>--}}
                                                    <td>
                                                            <label>
                                                                {{$stockTransferDetail->micro_unit_name}}
                                                            </label>
                                                            <input name="micro_received_quantity[]"
                                                                   type="number"
                                                                   min="0" value=''>
                                                            @if($stockTransferDetail->unit_name)
                                                                <br>
                                                                <label>
                                                                    {{$stockTransferDetail->unit_name}}
                                                                </label>
                                                                <input name="unit_received_quantity[]"
                                                                       type="number"
                                                                       min="0" value=''>
                                                            @else
                                                                <input name="unit_received_quantity[]" hidden
                                                                       type="number"
                                                                       min="0" value=''>
                                                            @endif
                                                            @if($stockTransferDetail->macro_unit_name)
                                                                <br>
                                                                <label>
                                                                    {{$stockTransferDetail->macro_unit_name}}
                                                                </label>
                                                                <input name="macro_received_quantity[]"
                                                                       type="number"
                                                                       min="0" value=''>
                                                            @else
                                                                <input name="macro_received_quantity[]" hidden
                                                                       type="number"
                                                                       min="0" value=''>
                                                            @endif
                                                            @if($stockTransferDetail->super_unit_name)
                                                                <br>
                                                                <label>
                                                                    {{$stockTransferDetail->super_unit_name}}
                                                                </label>
                                                                <input name="super_received_quantity[]"
                                                                       type="number"
                                                                       min="0" value=''>
                                                            @else
                                                                <input name="super_received_quantity[]" hidden
                                                                       type="number"
                                                                       min="0" value=''>
                                                            @endif

                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close
                                        </button>
                                            <button type="submit" class="btn btn-primary">Save changes
                                            </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!---/stock receive modal--->
            </div>
            <!-- ends row-->
        </section>
        <!--ends section-->
    </div>
@endsection
