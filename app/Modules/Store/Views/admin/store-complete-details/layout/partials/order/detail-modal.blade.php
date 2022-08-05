
<div class="modal-header">
    <button
        type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true"><i class="fa fa-times"></i></span>
    </button>
    <h4 class="modal-title"> Details of Store Order : {{ $storeOrder->store_order_code }}</h4>
</div>
<div class="modal-body">
    <div class="box-body">
        <section class="invoice">
            <!-- title row -->
            <div class="row">
                <div class="col-xs-12">
                    <h2 class="page-header">
                        <i class="fa fa-globe"></i> Alpasal Store, Order.
                        <small class="pull-right">{{ date($storeOrder->created_at) }}</small>
                    </h2>
                </div>
                <!-- /.col -->
            </div>

            <!-- info row -->
            <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                    From
                    <address>
                        Store :  <strong>{{ $storeOrder->store->store_name }} ({{ $storeOrder->store->store_code }})</strong><br>
                        Contact : <strong>{{ $storeOrder->store->store_contact_phone }} | {{ $storeOrder->store->store_contact_mobile }}</strong><br>

                    </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                    To
                    <address>
                        <strong>{{$storeOrder->warehouse->warehouse_name}} ({{$storeOrder->wh_code}})</strong><br>
                    </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                    <br>
                    <b>Order Code : </b>{{ $storeOrder->store_order_code }}<br>
                    <b>Total Price <small>(Initial Order Qty)</small> : </b>{{ getNumberFormattedAmount($storeOrder->total_price) }}<br>
                    <b>Acceptable Price <small>(Accepted Order Qty)</small>  : </b>{{ $storeOrder->acceptable_amount ? getNumberFormattedAmount($storeOrder->acceptable_amount): 'N/A' }}<br>
                    <b>Payment Status : </b>{{ $storeOrder->payment_status == '0' ? 'unpaid' : 'paid' }}<br>
                    <b>Delivery Status : </b>  {{ $storeOrder->delivery_status }}<br><br>


                </div>
            </div>
            <!-- /.row -->
            @can('Verify Store Order')

            @php
                $acceptanceStatusBtnColors = [
                  'pending' => 'danger',
                   'accepted' => 'success',
                   'rejected' => 'danger'
                ];
            @endphp

            <div class="row">
                <div class="col-xs-12 table-responsive">
                    @if(count($taxableOrderDetails) > 0)
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            <b>Taxable Products : {{count($taxableOrderDetails) }}</b>
                            <div class="pull-right">

                                <b>SubTotal : {{ getNumberFormattedAmount($taxableItemsData['tax_excluded_amount'])}}</b> &nbsp;&nbsp;&nbsp; |&nbsp;&nbsp;&nbsp;
                                <b>Tax Amount ({{\App\Modules\Store\Models\StoreOrder::VAT_PERCENTAGE_VALUE}} %) : {{getNumberFormattedAmount($taxableItemsData['tax_amount'])}}</b> &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                                <b>Total Amount : {{ getNumberFormattedAmount($taxableItemsData['total_amount'])}}</b>
                            </div>
                        </div>
                        <div class="panel-body">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product Name</th>
                                    <th>Vendor</th>
                                    @if($storeOrder->delivery_status != 'pending')
                                        <th title="Store : Ordered Quantity">
                                            Initial Quantity (Store)
                                        </th>
                                    @endif
                                    <th title="Dispatchable Quantity">
                                        Quantity (Dispatching)
                                    </th>

                                    <th>Unit Rate</th>
                                    <th>Sub Total</th>
                                    <th>Acceptance_status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($taxableOrderDetails as $i => $orderDetail)
                                <tr>
                                    <input id="orderItem{{$i}}" name="order_items[]" type="hidden" class="order_item" value="{{$orderDetail->store_order_detail_code}}">

                                    <td>{{ ++$i }}</td>
                                    <td>
                                        {{ $orderDetail->product->product_name }}<br>
                                        {{ isset($orderDetail->productVariant) ? $orderDetail->productVariant->product_variant_name :'' }}<br>
                                    </td>

                                    <td>
                                        <p title="Vendor">{{ $orderDetail->product->vendor->vendor_name }}</p>
                                    </td>

                                    <td>
                                    @if($storeOrder->delivery_status != 'pending')
                                        <td>
                                            {{ $orderDetail->initial_order_quantity }}
                                        </td>
                                    @endif

                                    <td>  {{ $orderDetail->quantity }}</td>
                                    <td>{{ getNumberFormattedAmount($orderDetail->unit_rate)}}</td>
                                        <td>{{ getNumberFormattedAmount($orderDetail->sub_total) }}</td>

                                    <td>
                                        <badge class="label label-{{$acceptanceStatusBtnColors[$orderDetail->acceptance_status]}}">
                                            {{ ucfirst($orderDetail->acceptance_status)  }}
                                        </badge>
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <br>
                    @endif
                        @if(count($nonTaxableOrderDetails) > 0)
                            @php
                                $total = 0;
                            @endphp
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <b>Non Taxable Products : {{count($nonTaxableOrderDetails)}}</b>
                                    <div class="pull-right">
                                        <b>Total Amount : {{getNumberFormattedAmount($nonTaxableItemsTotal)}}</b>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Product Name</th>
                                            <th>Vendor</th>
                                            @if($storeOrder->delivery_status != 'pending'

                                                )
                                                <th title="Store : Ordered Quantity">
                                                    Initial Quantity (Store)
                                                </th>
                                            @endif
                                            <th title="Dispatchable Quantity">
                                                Quantity (Dispatching)
                                            </th>

                                            <th>Unit Rate</th>
                                            <th>Sub Total</th>
                                            <th>Acceptance_status</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($nonTaxableOrderDetails as $i => $orderDetail)
                                            <tr>
                                                <input id="orderItem{{$i}}"  name="order_items[]" type="hidden" class="order_item" value="{{$orderDetail->store_order_detail_code}}" >

                                                <td>{{ ++$i }}</td>
                                                <td>
                                                    {{ $orderDetail->product->product_name }}<br>
                                                    {{ isset($orderDetail->productVariant) ? $orderDetail->productVariant->product_variant_name : '' }}
                                                </td>

                                                <td>
                                                    <p title="Vendor">{{ $orderDetail->product->vendor->vendor_name }}</p>
                                                </td>
                                                @if($storeOrder->delivery_status != 'pending'

                                                )
                                                    <td>
                                                        {{ $orderDetail->initial_order_quantity }}
                                                    </td>
                                                @endif

                                                <td>
                                                    {{ $orderDetail->quantity }}
                                                </td>

                                                <td>{{ getNumberFormattedAmount($orderDetail->unit_rate)}}</td>
                                                <td>
                                                    {{ getNumberFormattedAmount($orderDetail->sub_total) }}
                                                </td>

                                                <td>
                                                    <badge class="label label-{{$acceptanceStatusBtnColors[$orderDetail->acceptance_status]}}">
                                                        {{ ucfirst($orderDetail->acceptance_status)  }}
                                                    </badge>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                </div>
            </div>
            @endcan
            <br>
            @if($storeOrder->delivery_status=='dispatched' && $storeOrder->storeOrderDispatchDetail)
                <br>

                <div class="row">
                    <div class="col-xs-12 table-responsive">

                        <div class="panel panel-warning">
                            <div class="panel-heading">
                                <b>Store Order Dispatch Detail :</b>
                            </div>
                            <div class="panel-body">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Vehicle Name</th>
                                        <th>Vehicle Type</th>
                                        <th>Vehicle Number</th>
                                        <th>Vehicle Contact Number</th>
                                        <th>Expected Delivery Date</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>{{ucfirst($storeOrder->storeOrderDispatchDetail->vehicle_name)}}</td>
                                        <td>{{ucfirst($storeOrder->storeOrderDispatchDetail->vehicle_type)}}</td>
                                        <td>{{$storeOrder->storeOrderDispatchDetail->vehicle_number}}</td>
                                        <td>{{$storeOrder->storeOrderDispatchDetail->contact_number}}</td>
                                        <td>{{date('d-M-Y H:i:s',strtotime($storeOrder->storeOrderDispatchDetail->expected_delivery_time))}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            @endif
            <br>
            <div class="panel panel-info">
                <div class="panel-heading"> <b>Order Status Log</b></div>
                <div class="panel-body">

                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>S.N</th>
                            <th>Status</th>
                            <th>Updated At</th>
                            <th>Remarks</th>
                        </tr>
                        </thead>
                        <tbody>

                        {{--                                        @foreach($storeOrderStatusLogs as $i => $storeOrderStatusLog)--}}
                        @foreach($storeOrder->statusLogs as $i => $storeOrderStatusLog)
                            <tr>
                                <td>{{++$i}}</td>
                                <td>{{$storeOrderStatusLog->status}}</td>
                                <td>{{$storeOrderStatusLog->updated_at}}</td>
                                <td>
                                    @if($storeOrderStatusLog->status == 'pending')
                                        N / A
                                    @else
                                        <button type="button" class="btn btn-primary show-remark "  data-remark="{!! ucfirst($storeOrderStatusLog->remarks) !!}">View</button>
{{--                                        <div style="height:50px;width:350px;overflow:auto;border:1px solid black;">--}}
{{--                                           {!! $storeOrderStatusLog->remarks !!}--}}
{{--                                        </div>--}}
{{--                                        <button class="btn btn-sm btn-info"  data-toggle="modal" data-target="#statusLogRemarks{{$i}}">--}}
{{--                                            <i class="fa fa-eye"></i>--}}
{{--                                        </button>--}}
                                    @endif
                                </td>
                            </tr>
                            <!-- Modal -->
{{--                            <div id="statusLogRemarks{{$i}}" class="modal fade" role="dialog">--}}
{{--                                <div class="modal-dialog">--}}

{{--                                    <!-- Modal content-->--}}
{{--                                    <div class="modal-content">--}}
{{--                                        <div class="modal-header">--}}
{{--                                            <button type="button" class="close" data-dismiss="modal">&times;</button>--}}
{{--                                            <h4 class="modal-title">Remarks - Order Code : {{$storeOrder->store_order_code}} / Status : {{ucwords($storeOrderStatusLog->status)}}</h4>--}}
{{--                                        </div>--}}
{{--                                        <div class="modal-body">--}}

{{--                                            {!! $storeOrderStatusLog->remarks !!}--}}

{{--                                        </div>--}}
{{--                                        <div class="modal-footer">--}}
{{--                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

{{--                                </div>--}}
{{--                            </div>--}}
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>
{{--<div class="row" style="padding-bottom: 20px !important; margin: 0 !important;">--}}
{{--    <div class="col-md-12 text-center">--}}
{{--        <button type="button" class="btn btn-danger" data-dismiss="modal">back</button>--}}
{{--        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->--}}
{{--    </div>--}}
{{--</div>--}}


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



