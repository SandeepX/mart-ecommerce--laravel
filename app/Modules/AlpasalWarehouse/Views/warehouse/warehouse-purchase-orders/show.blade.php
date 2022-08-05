@extends('AdminWarehouse::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('AdminWarehouse::layout.partials.breadcrumb',
    [
    'page_title'=>$title,
    'sub_title'=> "Manage {$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route.'index'),
    ])

    <!-- Main content -->
        <section class="content">
            @include('AdminWarehouse::layout.partials.flash_message')
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                Details of Warehouse Purchase Orders
                            </h3>
                        </div>
                    </div>

                    <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">
                        <a href="{{ route('warehouse.warehouse-purchase-orders.index') }}" style="border-radius: 0px; "
                           class="btn btn-sm btn-primary">
                            <i class="fa fa-list"></i>
                            List of {{$title}}
                        </a>
                    </div>


                    <div class="box-body">
                        <section class="invoice">
                            <!-- title row -->
                            <div class="row">
                                <div class="col-xs-12">
                                    <h2 class="page-header">
                                        <i class="fa fa-globe"></i> Alpasal Warehouse, Order.
                                        <small class="pull-right">{{ $warehousePurchaseOrder->order_date }}</small>
                                    </h2>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- info row -->
                            <div class="row invoice-info">
                                <div class="col-sm-4 invoice-col">
                                    From
                                    <address>
                                        <strong>{{ $warehousePurchaseOrder->warehouse->warehouse_name }}</strong><br>
                                        {{ $warehousePurchaseOrder->warehouse->location->location_name }}<br>
                                        {{ $warehousePurchaseOrder->warehouse->landmark_name }}<br>

                                    </address>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 invoice-col">
                                    To
                                    <address>
                                        <strong>{{ $warehousePurchaseOrder->vendor->vendor_name }}</strong><br>
                                        {{ $warehousePurchaseOrder->vendor->location->location_name }}<br>
                                        {{ $warehousePurchaseOrder->vendor->vendor_landmark }}<br>
                                        {{ $warehousePurchaseOrder->vendor->contact_mobile }}<br>
                                        {{ $warehousePurchaseOrder->vendor->contact_email }}
                                    </address>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 invoice-col">
                                    <br>
                                    <b>Order Code:</b>{{ $warehousePurchaseOrder->warehouse_order_code }}<br>
                                    <b>Sent Status:</b> <span
                                        class="label label-primary">{{ucwords($warehousePurchaseOrder->status)}}</span><br>
                                    <b>Order Date:</b> {{ $warehousePurchaseOrder->order_date }}<br>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->

                            <!-- Table row -->
                            <div class="row">
                                <div class="col-xs-12 table-responsive">
                                    <!-- Button trigger modal -->
                                    @if($warehousePurchaseOrder->getOrderStatus() == 'sent')
                                        <button type="button" class="btn btn-primary pull-right" data-toggle="modal"
                                                data-target="#exampleModalCenter">
                                            Stock Orders
                                        </button>
                                    @endif
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Product Name</th>
                                            <th>Rate</th>
                                            <th>Ordered Quantity</th>
                                            <th>Received Quantity</th>
                                            <th>Subtotal</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($purchaseOrderDetails as $i => $orderDetail)
                                            <tr>
                                                <td>{{ ++$i }}</td>
                                                <td>
                                                    {{ $orderDetail->product_name }}
                                                    <small>{{$orderDetail->product_variant_name ?? 'N/A'}}</small>
                                                </td>
                                                <td>
                                                    {{$orderDetail->unit_rate}}
                                                </td>
                                                <td>{{ ($orderDetail->sending_package) ? $orderDetail->sending_package : $orderDetail->quantity }}</td>
                                                <td>{{ ($orderDetail->received_package) ? $orderDetail->received_package : $orderDetail->received_quantity ?? 'N/A'}}</td>
                                                <td>{{ $orderDetail->quantity * $orderDetail->unit_rate}}</td>
                                                <td>
                                                        <span
                                                            class="label label-primary">Action not available
                                                        </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.col -->


                                <!---purchase return modal--->
                                <div class="modal fade" id="purchaseReturnModal" tabindex="-1" role="dialog"
                                     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <form id="purchase-return-form"
                                                  action=""
                                                  method="post">
                                                {{csrf_field()}}
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLongTitle"></h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">

                                                    <div class="form-group">
                                                        <label class="col-sm-2" for="reason_type">Reason Type</label>
                                                        <div class="col-sm-10">
                                                            <select class="form-control" id="reason_type"
                                                                    name="reason_type">
                                                                <option selected disabled> -- Select Reason Type --
                                                                </option>
                                                                @foreach($purchaseReturnReasonTypes as $reasonType)
                                                                    <option
                                                                        value="{{$reasonType}}">{{ucwords($reasonType)}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                    </div>


                                                    <div class="form-group">
                                                        <label for="return_quantity" class="col-sm-2 control-label">Return
                                                            Quantity</label>
                                                        <div class="col-sm-10">
                                                            <input type="number" id="return_quantity"
                                                                   class="form-control" value="" name="return_quantity">
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="return_reason_remarks"
                                                               class="col-sm-2 control-label">Return Reason</label>
                                                        <textarea class="form-control" name="return_reason_remarks"
                                                                  id="return_reason_remarks"></textarea>
                                                    </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Close
                                                    </button>

                                                    <button type="submit" class="btn btn-primary">
                                                        Save changes
                                                    </button>

                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!---/purchase return modal--->

                                <!---stock order modal--->
                                <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
                                     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <form
                                                action="{{route('warehouse.warehouse-purchase-orders.update-received-quantity',$warehousePurchaseOrder->warehouse_order_code)}}"
                                                method="post">
                                                {{csrf_field()}}
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLongTitle"></h5>
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
                                                            <th>Ordered Quantity</th>
                                                            <th>Received Quantity</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @php($ordersRemaining=false)
                                                        @foreach ($purchaseOrderDetails as $i => $orderDetail)
                                                            <tr>
                                                                <td>{{ ++$i }}</td>
                                                                <td>
                                                                    {{ $orderDetail->product_name }}
                                                                    <br>
                                                                    <small>{{ $orderDetail->product_variant_name ?? ''}}</small>
                                                                    <input name="warehouse_order_detail_code[]"
                                                                           type="hidden"
                                                                           value="{{$orderDetail->warehouse_order_detail_code}}">
                                                                    <input name="product_code[]"
                                                                           type="hidden"
                                                                           value="{{$orderDetail->product_code}}">
                                                                    <input name="product_variant_code[]"
                                                                           type="hidden"
                                                                           value="{{$orderDetail->product_variant_code}}">
                                                                </td>
                                                                <td>{{ ($orderDetail->sending_package) ? $orderDetail->sending_package : $orderDetail->quantity }}</td>
                                                                <td>
                                                                    @php($ordersRemaining= true)
                                                                        <label>
                                                                            {{$orderDetail->micro_unit_name}}
                                                                        </label>
                                                                        <input name="micro_received_quantity[]"
                                                                               type="number"
                                                                               min="0" value=''>
                                                                        @if($orderDetail->unit_name)
                                                                            <br>
                                                                            <label>
                                                                                {{$orderDetail->unit_name}}
                                                                            </label>
                                                                            <input name="unit_received_quantity[]"
                                                                                   type="number"
                                                                                   min="0" value=''>
                                                                        @else
                                                                            <input name="unit_received_quantity[]" hidden
                                                                                   type="number"
                                                                                   min="0" value=''>
                                                                        @endif
                                                                        @if($orderDetail->macro_unit_name)
                                                                            <br>
                                                                            <label>
                                                                                {{$orderDetail->macro_unit_name}}
                                                                            </label>
                                                                            <input name="macro_received_quantity[]"
                                                                                   type="number"
                                                                                   min="0" value=''>
                                                                        @else
                                                                            <input name="macro_received_quantity[]" hidden
                                                                                   type="number"
                                                                                   min="0" value=''>
                                                                        @endif
                                                                        @if($orderDetail->super_unit_name)
                                                                            <br>
                                                                            <label>
                                                                                {{$orderDetail->super_unit_name}}
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
                                                    @if($ordersRemaining)
                                                        <button type="submit" class="btn btn-primary">Save changes
                                                        </button>
                                                    @endif
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!---/stock order modal--->
                            </div>
                        </section>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
@endsection
@push('scripts')
    @include(''.$module.'.warehouse.warehouse-purchase-orders.show-partials.show-scripts')
@endpush
