@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
        @include('Admin::layout.partials.breadcrumb',
        [
        'page_title'=>$title,
        'sub_title'=> "Manage {$title}",
        'icon'=>'home',
        'sub_icon'=>'',
        'manage_url'=>'',
        ])


        <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
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
                      <a href="{{ route('admin.warehouse-purchase-orders.index') }}" style="border-radius: 0px; " class="btn btn-sm btn-primary">
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
                                        <small class="pull-right">{{ $purchaseOrder->order_date }}</small>
                                    </h2>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- info row -->
                            <div class="row invoice-info">
                                <div class="col-sm-4 invoice-col">
                                    From
                                    <address>
                                        <strong>{{ $purchaseOrder->warehouse->warehouse_name }}</strong><br>
                                        {{ $purchaseOrder->warehouse->location->location_name }}<br>
                                        {{ $purchaseOrder->warehouse->landmark_name }}<br>

                                    </address>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 invoice-col">
                                    To
                                    <address>
                                        <strong>{{ $purchaseOrder->vendor->vendor_name }}</strong><br>
                                        {{ $purchaseOrder->vendor->location->location_name }}<br>
                                        {{ $purchaseOrder->vendor->vendor_landmark }}<br>
                                        {{ $purchaseOrder->vendor->contact_mobile }}<br>
                                        {{ $purchaseOrder->vendor->contact_email }}
                                    </address>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 invoice-col">
                                    <br>
                                    <b>Order Code:</b>{{ $purchaseOrder->order_code }}<br>
                                    <b>Sent Status:</b> {{ $purchaseOrder->sent_status }}<br>
                                    <b>Sent Date:</b> {{ $purchaseOrder->sent_date }}<br>
                                    <b>Vendor Status:</b>
                                    {{ isset($purchaseOrder->receivedByVendor) ? $purchaseOrder->receivedByVendor->order_received_status : 'not received' }}
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->

                            <!-- Table row -->
                            <div class="row">
                                <div class="col-xs-12 table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Product Name</th>
                                                <th>Product Variant </th>
                                                <th>Quantity</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($purchaseOrder->details as $i => $orderDetail)
                                                <tr>
                                                    <td>{{ ++$i }}</td>
                                                    <td>{{ $orderDetail->product->product_name }}</td>
                                                    <td>{{ $orderDetail->productVariant->product_variant_name }}</td>
                                                    <td>{{ $orderDetail->package_quantity }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.col -->
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
