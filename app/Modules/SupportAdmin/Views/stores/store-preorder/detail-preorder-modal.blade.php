{{--@push('css')--}}
{{--    <style type="text/css">--}}
{{--        .table {--}}
{{--            display: table;--}}
{{--            border-collapse: separate;--}}
{{--            /*border-spacing:2px;*/--}}
{{--        }--}}

{{--        .thead {--}}
{{--            display: table-header-group;--}}
{{--            color: white;--}}
{{--            font-weight: bold;--}}
{{--            background-color: grey;--}}
{{--        }--}}

{{--        .tbody {--}}
{{--            display: table-row-group;--}}
{{--        }--}}

{{--        .tr {--}}
{{--            display: table-row;--}}
{{--        }--}}

{{--        .td {--}}
{{--            display: table-cell;--}}
{{--            border: 1px solid black;--}}
{{--            padding: 1px;--}}
{{--        }--}}

{{--        .tr.editing .td INPUT {--}}
{{--            width: 100px;--}}
{{--        }--}}
{{--    </style>--}}
{{--@endpush--}}
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span></button>
    <h4 class="modal-title">Store PreOrder Modal</h4>
</div>
<div class="modal-body">
    <div class="row">
        <section class="content-header">
            <h1>
                Store PreOrder
                <small>Manage Store PreOrder</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="javascript:void(0);"><i class="fa fa-home"></i> Dashboard</a></li>
                <li class="active"><a href=""><i class="fa fa-"></i> Store PreOrder</a></li>
            </ol>
        </section>
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

                        <!-- <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">
                        <a href="http://backend.allkhata.com/warehouse/warehouse-pre-orders/WPLC1023/store-orders" style="border-radius: 0px; " class="btn btn-sm btn-success">
                            <i class="fa fa-list"></i> List of Store Pre-Orders
                        </a>
                    </div> -->
                    </div>

                    <div class="box-body">
                        <section class="invoice">
                            <!-- title row -->
                            <div class="row">
                                <div class="col-xs-12">
                                    <h2 class="page-header">
                                        <i class="fa fa-globe"></i> Alpasal Store, Pre-Order.
                                        <small class="pull-right">{{ date($storePreOrder->created_at) }}</small>

                                        {{--                                        <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">--}}
                                        {{--                                            <a href="{{ route('warehouse.warehouse-pre-orders.store-orders.generate-excel',$storePreOrder->store_preorder_code) }}" style="border-radius: 0px; " class="btn btn-sm btn-primary">--}}
                                        {{--                                                <i class="fa fa-file-excel-o"></i> Generate Excel Bill--}}
                                        {{--                                            </a>--}}
                                        {{--                                        </div>--}}

                                        {{--                                        <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">--}}
                                        {{--                                            <a href="{{ route('warehouse.warehouse-pre-orders.store-orders.generate-pdf',$storePreOrder->store_preorder_code) }}" style="border-radius: 0px; " class="btn btn-sm btn-primary">--}}
                                        {{--                                                <i class="fa fa-file-excel-o"></i> Generate Pdf Bill--}}
                                        {{--                                            </a>--}}
                                        {{--                                        </div>--}}
                                    </h2>
                                </div>
                                <!-- /.col -->
                            </div>


                            @include('SupportAdmin::layout.partials.flash_message')
                            @if (Session::has('stock_unavailable_items'))
                                <div class="alert alert-danger">
                                    <strong style="text-decoration: underline">Stock Unavailable Products</strong><br>
                                    @foreach(session('stock_unavailable_items') as $stock_unavailable_item)
                                        <strong>
                                            {{$stock_unavailable_item->product_name}}
                                            {{$stock_unavailable_item->product_variant_name}}
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
                                    <b>Payment Status
                                        : </b>{{ $storePreOrder->payment_status == '0' ? 'unpaid' : 'paid' }}<br>
                                    <b>Status : </b> {{ ucwords($storePreOrder->status) }}<br><br>

                                </div>
                            </div>


                            <!-- /.row -->

                            <div class="row">
                                <div class="col-xs-12 table-responsive">
                                    @if(isset($taxableOrderProducts) && count($taxableOrderProducts) > 0)
                                        <div class="panel panel-danger">
                                            @if(isset($taxableOrderDetails) && count($taxableOrderDetails) > 0)
                                                <div class="panel-heading">
                                                    <b>Taxable Products : {{count($taxableOrderProducts) }}</b>

                                                    <div class="pull-right">

                                                        <b>SubTotal
                                                            : {{$taxableOrderDetails['tax_excluded_amount']}} </b> &nbsp;&nbsp;&nbsp;
                                                        |&nbsp;&nbsp;&nbsp;
                                                        <b>Tax Amount (13 %)
                                                            : {{$taxableOrderDetails['tax_amount']}} </b> &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                                                        <b>Total Amount : {{$taxableOrderDetails['total_amount']}}</b>
                                                    </div>

                                                </div>
                                            @endif
                                            <div class="panel-body">
                                                <div class="table" style="display: table;border-collapse: separate;">
                                                    <div class="thead" style="display: table-header-group; color: white; font-weight: bold;  background-color: grey;" >
                                                        <div class="tr" style="display: table-row;">
                                                            <div class="td" style="display: table-cell; border: 1px solid black; padding: 1px;">Product</div>
                                                            <div class="td" style="display: table-cell;border: 1px solid black;padding: 1px;">Vendor</div>
                                                            <div class="td" style="display: table-cell;border: 1px solid black;padding: 1px;">Initial Order Quantity</div>
                                                            <div class="td" style="display: table-cell;border: 1px solid black;padding: 1px;">Quantity(Dispatching)</div>
                                                            <div class="td" style="display: table-cell;border: 1px solid black;padding: 1px;">Unit Rate</div>
                                                            <div class="td" style="display: table-cell;border: 1px solid black;padding: 1px;">Sub Total</div>
                                                            <div class="td" style="display: table-cell;border: 1px solid black;padding: 1px;">Delivery Status</div>

                                                        </div>
                                                    </div>
                                                    <div class="tbody" style="display: table-row-group;">
                                                        @if(isset($taxableOrderProducts))
                                                            @foreach($taxableOrderProducts as $taxableOrderProduct)
                                                                <div class="tr" method="post" style="display: table-row;" >
                                                                    <div class="td" style="display: table-cell;border: 1px solid black;padding: 1px;">
                                                                        {{--                            <input type="hidden"  name="store_preorder_detail_code" value="{{$taxableOrderProduct->store_preorder_detail_code}}"/>--}}
                                                                        <b>{{$taxableOrderProduct->product_name}}</b>
                                                                        <br>
                                                                        <small>{{$taxableOrderProduct->product_variant_name}}</small>
                                                                        <small><b>Available Stock : {{$taxableOrderProduct->current_stock ?? 0}}</b> </small>
                                                                    </div>
                                                                    <div class="td" style="display: table-cell;border: 1px solid black;padding: 1px;">
                                                                        {{$taxableOrderProduct->vendor_name}}
                                                                    </div>

                                                                    <div class="td" style="display: table-cell;border: 1px solid black;padding: 1px;">
                                                                        {{$taxableOrderProduct->initial_order_quantity}}
                                                                    </div>

                                                                    <div class="td" style="display: table-cell;border: 1px solid black;padding: 1px;">
                                                                        {{$taxableOrderProduct->unit_rate}}
                                                                    </div>
                                                                    <div class="td" style="display: table-cell;border: 1px solid black;padding: 1px;">
                                                                        {{$taxableOrderProduct->unit_rate * $taxableOrderProduct->quantity}}
                                                                    </div>
                                                                    <div class="td" style="display: table-cell;border: 1px solid black;padding: 1px;">
                                                                        {{$taxableOrderProduct->unit_rate * $taxableOrderProduct->quantity}}
                                                                    </div>
                                                                    <div class="td" style="display: table-cell;border: 1px solid black;padding: 1px;">
                                                                        @if($storePreOrder->status == 'finalized')
                                                                            <select name="delivery_status">
                                                                                <option value="1" {{$taxableOrderProduct->delivery_status == 1? 'selected' : ''}}>Accept</option>
                                                                                <option value="0" {{$taxableOrderProduct->delivery_status == 0? 'selected' : ''}}>Reject</option>
                                                                            </select>
                                                                        @else
                                                                            <badge class="label label-{{returnLabelColor($taxableOrderProduct->delivery_status)}}">
                                                                                {{$taxableOrderProduct->delivery_status_name}}
                                                                            </badge>

                                                                        @endif

                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
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
                                                            : {{$nonTaxableOrderDetails['total_amount']}}</b>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="panel-body">
                                                <div class="table" style="display: table;border-collapse: separate;">
                                                    <div class="thead" style="display: table-header-group; color: white; font-weight: bold;  background-color: grey;" >
                                                        <div class="tr" style="display: table-row;">
                                                            <div class="td" style="display: table-cell; border: 1px solid black; padding: 1px;">Product</div>
                                                            <div class="td" style="display: table-cell;border: 1px solid black;padding: 1px;">Vendor</div>
                                                            <div class="td" style="display: table-cell;border: 1px solid black;padding: 1px;">Initial Order Quantity</div>
                                                            <div class="td" style="display: table-cell;border: 1px solid black;padding: 1px;">Quantity(Dispatching)</div>
                                                            <div class="td" style="display: table-cell;border: 1px solid black;padding: 1px;">Unit Rate</div>
                                                            <div class="td" style="display: table-cell;border: 1px solid black;padding: 1px;">Sub Total</div>
                                                            <div class="td" style="display: table-cell;border: 1px solid black;padding: 1px;">Delivery Status</div>

                                                        </div>
                                                    </div>
                                                    <div class="tbody"  style="display: table-row-group;">
                                                        @if(isset($nonTaxableOrderProducts))
                                                            @foreach($nonTaxableOrderProducts as $nonTaxableOrderProduct)
                                                                <div class="tr" method="post" style="display: table-row;">
                                                                    <div class="td" style="display: table-cell;border: 1px solid black;padding: 1px;">
                                                                        <b>{{$nonTaxableOrderProduct->product_name}}</b>
                                                                        <br>
                                                                        <small>{{$nonTaxableOrderProduct->product_variant_name}}</small>
                                                                        <small><b>Available Stock : {{$nonTaxableOrderProduct->current_stock ?? 0}}</b> </small>
                                                                    </div>
                                                                    <div class="td" style="display: table-cell;border: 1px solid black;padding: 1px;">
                                                                        {{$nonTaxableOrderProduct->vendor_name}}
                                                                    </div>
                                                                    <div class="td" style="display: table-cell;border: 1px solid black;padding: 1px;">
                                                                        {{$nonTaxableOrderProduct->initial_order_quantity}}
                                                                    </div>
                                                                    <div class="td" style="display: table-cell;border: 1px solid black;padding: 1px;">
                                                                        @if($storePreOrder->status == 'finalized')
                                                                            <input type="number" min="0" max="{{$nonTaxableOrderProduct->initial_order_quantity}}" name="dispatch_quantity" value="{{$nonTaxableOrderProduct->quantity}}"/>
                                                                        @else
                                                                            {{$nonTaxableOrderProduct->quantity}}
                                                                        @endif
                                                                    </div>
                                                                    <div class="td" style="display: table-cell;border: 1px solid black;padding: 1px;">
                                                                        {{$nonTaxableOrderProduct->unit_rate}}
                                                                    </div>
                                                                    <div class="td" style="display: table-cell;border: 1px solid black;padding: 1px;">
                                                                        {{$nonTaxableOrderProduct->unit_rate * $nonTaxableOrderProduct->quantity}}
                                                                    </div>
                                                                    <div class="td" style="display: table-cell;border: 1px solid black;padding: 1px;">
                                                                        @if($storePreOrder->status == 'finalized')
                                                                            <select name="delivery_status">
                                                                                <option value="1" {{$nonTaxableOrderProduct->delivery_status == 1? 'selected' : ''}}>Accept</option>
                                                                                <option value="0" {{$nonTaxableOrderProduct->delivery_status == 0? 'selected' : ''}}>Reject</option>
                                                                            </select>
                                                                        @else
                                                                            <badge class="label label-{{returnLabelColor($nonTaxableOrderProduct->delivery_status)}}">
                                                                                {{$nonTaxableOrderProduct->delivery_status_name}}
                                                                            </badge>

                                                                        @endif

                                                                    </div>

                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    @endif

                                    <hr>

                                </div>
                            </div>



                            <div class="panel panel-info">
                                <div class="panel-heading"> <b>Pre-Order Status Log</b></div>
                                <div class="panel-body">

                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th>S.N</th>
                                            <th>Status</th>
                                            <th>Updated By</th>
                                            <th>Updated At</th>
                                            <th>Remarks</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        {{--                                       @foreach($storeOrderStatusLogs as $i => $storeOrderStatusLog)--}}
                                        @forelse($storePreOrderStatusLogs as $i =>$statusLog)
                                            <tr>
                                                <td>{{++$i}}</td>
                                                <td>{{$statusLog->status}}</td>
                                                <td>{{$statusLog->updatedBy->name}}</td>
                                                <td>{{$statusLog->created_at}}</td>
                                                <td>
                                                    <button type="button" class="btn btn-primary show-remark "  data-remark="{!! ucfirst($statusLog->remarks) !!}">View</button>


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
                                </div>
                            </div>


                            <script>
                                $('.show-remark').on('click',function(event){
                                    event.preventDefault();
                                    let remark= $(this).attr('data-remark');
                                    Swal.fire({
                                        title: '<strong>Remark</strong>',
                                        icon: 'info',
                                        html: remark,
                                        showCloseButton: true,
                                        width: '500px',
                                        padding: '10em',
                                    });
                                });

                            </script>
                            )

                        </section>


                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
</div>
<div class="row" style="padding: 20px 0;">
    <div class="col-md-12 text-center">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</div>
