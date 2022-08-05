@extends('AdminWarehouse::layout.common.masterlayout')
@push('css')
    <style type="text/css">
        .table {
            display: table;
            border-collapse: separate;
            /*border-spacing:2px;*/
        }

        .thead {
            display: table-header-group;
            color: white;
            font-weight: bold;
            background-color: grey;
        }

        .tbody {
            display: table-row-group;
        }

        .tr {
            display: table-row;
        }

        .td {
            display: table-cell;
            border: 1px solid black;
            padding: 1px;
        }

        .tr.editing .td INPUT {
            width: 100px;
        }
        .box-color {
            float: left;
            height: 20px;
            width: 20px;
            padding-top: 5px;
            border: 1px solid black;
        }

        .danger-color {
            background-color:  #debf6d ;
        }

        .warning-color {
            background-color:  #f5917d ;
        }
    </style>

@endpush
@section('content')
    <div class="content-wrapper">
    @include('AdminWarehouse::layout.partials.breadcrumb',
    [
    'page_title'=>$title,
    'sub_title'=> "Manage {$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>'',
    ])

    <!-- Main content -->
        <section class="content">

            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                Details of Store Pre-Order : {{ $storePreOrder['store_preorder_code'] }}
                            </h3>
                        </div>

                        <div class="pull-right" style="margin-top: -35px; padding-right: 10px;">
                            <a href="{{ route('warehouse.warehouse-pre-orders.store-orders',$storePreOrder['warehouse_preorder_listing_code']) }}"
                               style="border-radius: 0px; " class="btn btn-sm btn-success">
                                <i class="fa fa-list"></i>
                                List of Store Pre-Orders
                            </a>
                        </div>
                    </div>

                    <div class="box-body">
                        <section class="invoice">
                            <!-- title row -->
                            <div class="row">
                                <div class="col-xs-12">
                                    <h2 class="page-header">
                                        <i class="fa fa-globe"></i> Alpasal Store, Pre-Order.
                                        <small class="pull-right">{{ date($storePreOrder->created_at) }}</small>

                                        <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">
                                            <a href="{{ route($base_route.'store-orders.generate-excel',$storePreOrder->store_preorder_code) }}"
                                               style="border-radius: 0px; " class="btn btn-sm btn-primary">
                                                <i class="fa fa-file-excel-o"></i>
                                                Generate Excel Bill
                                            </a>
                                        </div>

                                        <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">
                                            <a href="{{ route($base_route.'store-orders.generate-pdf',$storePreOrder->store_preorder_code) }}"
                                               style="border-radius: 0px; " class="btn btn-sm btn-primary">
                                                <i class="fa fa-file-excel-o"></i>
                                                Generate Pdf Bill
                                            </a>
                                        </div>
                                    </h2>
                                </div>
                                <!-- /.col -->
                            </div>

                            @include('AdminWarehouse::layout.partials.flash_message')

                            @if (Session::has('stock_unavailable_items'))
                                <div class="alert alert-danger">
                                    <strong style="text-decoration: underline">Stock Unavailable Products</strong><br>
                                    @foreach(session('stock_unavailable_items') as $stock_unavailable_item)
                                        <strong>
                                            Product : {{$stock_unavailable_item['product_name']}}<br>
                                            @if($stock_unavailable_item['product_variant_name'])
                                                Variant : {{$stock_unavailable_item['product_variant_name']}}<br>
                                            @endif
                                            Dispatching Qty : {{$stock_unavailable_item['dispatchingQty']}}<br>
                                            Dispatching Micro Qty : {{$stock_unavailable_item['dispatchingMicroQty']}}<br>
                                            Insufficient Qty : {{$stock_unavailable_item['insufficientQty']}}<br>
                                        </strong><br>
                                    @endforeach
                                </div>
                            @endif

                        <!-- info row -->
                            <div class="row invoice-info">
                                <div class="col-sm-4 invoice-col">
                                    From
                                    <address>
                                        Store : <strong>{{ $storePreOrder->store->store_name }}
                                            ({{ $storePreOrder->store->store_code }})</strong><br>
                                        Contact : <strong>{{ $storePreOrder->store->store_contact_phone }}
                                            | {{ $storePreOrder->store->store_contact_mobile }}</strong><br>
                                    </address>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 invoice-col">
                                    To
                                    <address>
                                        <strong>{{$storePreOrder->warehousePreOrderListing->warehouse->warehouse_name}}
                                            ({{$storePreOrder->warehousePreOrderListing->warehouse->warehouse_code}}
                                            )</strong>
                                    </address>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 invoice-col">
                                    <br>
                                    <b>Pre-Order Code : </b>{{ $storePreOrder->store_preorder_code }}<br>
                                    <b>Total Price <small>(Initial Order Qty)</small> :
                                    </b>{{ $storePreOrder->storePreOrderView->total_price }}<br>
                                    {{-- <b>Acceptable Price <small>(Accepted Order Qty)</small> :
                                     </b>{{ $storePreOrder->acceptable_amount ? $storeOrder->acceptable_amount : 'N/A' }}
                                     <br>--}}
                                    <b>Payment Status
                                        : </b>{{ $storePreOrder->payment_status == '0' ? 'unpaid' : 'paid' }}<br>
                                    <b>Status : </b> <span class="label label-{{returnLabelColor($storePreOrder->status)}}">{{ strtoupper($storePreOrder->status) }}</span><br><br>

                                    {{-- @if($storeOrder->delivery_status == 'processing'||$storeOrder->delivery_status == 'dispatched')
                                         <a target="_blank" class="btn btn-xs btn-primary" href="{{route('warehouse.store.orders.pdf',$storeOrder->store_order_code) }}">View Payment Bill</a>
                                         <a class="btn btn-xs btn-primary" href="{{route('warehouse.store.orders.pdf',[$storeOrder->store_order_code,'action'=>'download']) }}">Download Payment Bill</a>
                                     @endif--}}

                                </div>

                            </div>



                            @if(
                               !\App\Modules\Store\Helpers\PreOrder\StorePreOrderHelper::isStorePreOrderFinalizableByReason(
                                $storePreOrder->store_preorder_code,'non_deleted_preorder_details')
                               )
                                <div class="alert alert-danger">
                                    <strong>
                                        This store pre order contains only the items that have been deleted by the store itself, after the store have added to pre order.
                                        <br>
                                        So, the items are not listed !
                                    </strong>

                                </div>
                            @endif
                            @if(
                             !\App\Modules\Store\Helpers\PreOrder\StorePreOrderHelper::isStorePreOrderFinalizableByReason(
                              $storePreOrder->store_preorder_code,'active_preorder_products')
                             )
                                <div class="alert alert-warning">
                                    <strong>
                                        This store pre order contains all the items that have been set inactive by the warehouse, after the store have added items to pre order.
                                    </strong>

                                </div>
                            @endif

                        <!-- /.row -->

                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="col-sm-2">
                                        <span class='box-color danger-color'></span> &nbsp;Inactive
                                        {{--                                         <span class='box-color warning-color'></span> Suspened--}}
                                    </div>
                                    <div class="col-sm-2" style="margin-left: -50px !important">
                                        {{--                                         <span class='box-color danger-color'></span>  Banned--}}
                                        <span class='box-color warning-color'></span> &nbsp;Rejected
                                    </div>
                                </div>
                            </div>


                            <div class="row">

                                <div class="col-xs-12 table-responsive">

                                    @if(isset($taxableOrderProducts) && count($taxableOrderProducts) > 0)
                                        <div class="panel panel-danger">
                                            @if(isset($taxableOrderDetails) && count($taxableOrderDetails) > 0)
                                                <div class="panel-heading">
                                                    <b>Taxable Products : {{count($taxableOrderProducts) }}</b>
                                                    <div class="pull-right">

                                                        <b>SubTotal
                                                            : {{roundPrice($taxableOrderDetails['tax_excluded_amount'])}}</b>
                                                        &nbsp;&nbsp;&nbsp; |&nbsp;&nbsp;&nbsp;
                                                        <b>Tax Amount (13 %)
                                                            : {{roundPrice($taxableOrderDetails['tax_amount'])}}</b>
                                                        &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                                                        <b>Total Amount : {{roundPrice($taxableOrderDetails['total_amount'])}}</b>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="panel-body">
                                                @include('AlpasalWarehouse::warehouse.warehouse-pre-orders.store-pre-orders.show-partials.order-taxable-products')
                                            </div>
                                        </div>
                                        <hr>
                                    @endif
                                    @if(isset($nonTaxableOrderProducts) && count($nonTaxableOrderProducts) > 0)
                                        <div class="panel panel-primary">
                                            @if(isset($nonTaxableOrderDetails) && count($nonTaxableOrderDetails) > 0)
                                                <div class="panel-heading">
                                                    <b>Non-Taxable Products : {{count($nonTaxableOrderProducts) }}</b>
                                                    <div class="pull-right">&nbsp;
                                                        <b>Total Amount
                                                            : {{roundPrice($nonTaxableOrderDetails['total_amount'])}}</b>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="panel-body">
                                                @include('AlpasalWarehouse::warehouse.warehouse-pre-orders.store-pre-orders.show-partials.order-non-taxable-products')
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            </div>


                            @if($storePreOrder->status == 'finalized' || $storePreOrder->status == 'processing')
                                <hr>
                                <div class="alert alert-warning">
                                    <strong>While Updating status !</strong><br>
                                    <ul>
                                        <li>
                                            <strong>You won't be able to update status or perform any action on this
                                                pre-order.</strong>
                                        </li>
                                    </ul>
                                </div>

                                <hr>
                                @include(''.$module.'.warehouse.warehouse-pre-orders.store-pre-orders.show-partials.status-form')

                            @endif
                            @if($storePreOrderDispatchDetail && $storePreOrder->status == 'dispatched')
                                <div class="row">
                                    <div class="col-xs-12 table-responsive">

                                        <div class="panel panel-warning">
                                            <div class="panel-heading">
                                                <b>Store Pre Order Dispatch Detail :</b>
                                            </div>
                                            <div class="panel-body">
                                                <table class="table table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Driver Name</th>
                                                        <th>Vehicle Type</th>
                                                        <th>Vehicle Number</th>
                                                        <th>Vehicle Contact Number</th>
                                                        <th>Expected Delivery Date</th>

                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>{{ucfirst($storePreOrderDispatchDetail->driver_name)}}</td>
                                                        <td>{{ucfirst($storePreOrderDispatchDetail->vehicle_type)}}</td>
                                                        <td>{{$storePreOrderDispatchDetail->vehicle_number}}</td>
                                                        <td>{{$storePreOrderDispatchDetail->contact_number}}</td>
                                                        <td>{{date('d-M-Y H:i:s',strtotime($storePreOrderDispatchDetail->expected_delivery_time))}}</td>
                                                    </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @include(''.$module.'.warehouse.warehouse-pre-orders.store-pre-orders.show-partials.status-log')

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
    @include(''.$module.'.warehouse.warehouse-pre-orders.store-pre-orders.show-script')
    <script>
        $(document).ready(function (){
            $('.delivery_status').on('change',function (){
                $(this).closest('form').trigger('submit');
            });
        });
    </script>
@endpush
