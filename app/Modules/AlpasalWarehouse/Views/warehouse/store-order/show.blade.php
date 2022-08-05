@extends('AdminWarehouse::layout.common.masterlayout')
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
                                Details of Store Order : {{ $storeOrder->store_order_code }}
                            </h3>
                        </div>
                        <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">
                            <a href="{{ route('warehouse.store.orders.index') }}" style="border-radius: 0px; " class="btn btn-sm btn-success">
                                <i class="fa fa-list"></i>
                                List of Store Orders
                            </a>
                        </div>

                        <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">
                            <a href="#" style="border-radius: 0px; " class="btn btn-sm btn-primary" data-toggle="modal" data-target="#viewRemarksModal">
                                <i class="fa fa-list"></i>
                              Remarks Lists
                            </a>
                        </div>
                    </div>

                    <div class="box-body">
                        <section class="invoice">
                            <!-- title row -->
                            <div class="row">
                                <div class="col-xs-12">
                                    <h2 class="page-header">
                                        <i class="fa fa-globe"></i> Alpasal Store, Order.
                                        <small class="pull-right">{{ getReadableDate(getNepTimeZoneDateTime($storeOrder->created_at)) }}</small>
                                    </h2>
                                </div>
                                <!-- /.col -->
                            </div>

                            @include('AdminWarehouse::layout.partials.flash_message')

                            @if (Session::has('stock_unavailable_items'))
                                <div class="alert alert-danger">
                                    <strong style="text-decoration: underline">Stock Unavailable Products</strong><br>
                                    @foreach(session('stock_unavailable_items') as $stock_unavailable_item)
                                        <strong>{{$stock_unavailable_item}}</strong><br>
                                    @endforeach
                                </div>
                            @endif

                        <!-- info row -->
                            <div class="row invoice-info">
                                <div class="col-sm-4 invoice-col">
                                    From
                                    <address>
                                        Store : <strong>{{ $storeOrder->store->store_name }} ({{ $storeOrder->store->store_code }})</strong><br>
                                        Contact : <strong>{{ $storeOrder->store->store_contact_phone }} | {{ $storeOrder->store->store_contact_mobile }}</strong><br>
                                    </address>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 invoice-col">
                                    To
                                    <address>
                                        <strong>{{$storeOrder->warehouse->warehouse_name}} ({{$storeOrder->warehouse->warehouse_code}})</strong>
                                    </address>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 invoice-col">
                                    <br>
                                    <b>Order Code : </b>{{ $storeOrder->store_order_code }}<br>
                                    <b>Total Price <small>(Initial Order Qty)</small> : </b>{{ getNumberFormattedAmount($storeOrder->total_price) }}<br>
                                    <b>Acceptable Price <small>(Accepted Order Qty)</small>  : </b>{{ $storeOrder->acceptable_amount ? getNumberFormattedAmount($storeOrder->acceptable_amount) : 'N/A' }}<br>
                                    <b>Payment Status : </b>{{ $storeOrder->payment_status == '0' ? 'unpaid' : 'paid' }}<br>
                                    <b>Delivery Status : </b> {{ $storeOrder->delivery_status }}<br><br>

                                    @if($storeOrder->delivery_status == 'processing' || $storeOrder->delivery_status == 'dispatched' || $storeOrder->delivery_status == 'cancelled')
                                        <a target="_blank" class="btn btn-xs btn-primary" href="{{route('warehouse.store.orders.pdf',$storeOrder->store_order_code) }}">View Payment Bill</a>
                                        <a class="btn btn-xs btn-primary" href="{{route('warehouse.store.orders.pdf',[$storeOrder->store_order_code,'action'=>'download']) }}">Download Payment Bill</a>
                                    @endif

                                </div>
                            </div>

                            <!-- /.row -->

{{--                            @can('Verify Store Order')--}}
                                <form id="filter_form" action="{{ route('warehouse.store.orders.update-delivery-status', $storeOrder->store_order_code) }}" method="POST"   >

                                    @csrf
                                    @php
                                        $acceptanceStatusBtnColors = [
                                            'pending' =>'danger',
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

                                                            <b>SubTotal : {{getNumberFormattedAmount($taxableItemsData['tax_excluded_amount'])}}</b>
                                                            &nbsp;&nbsp;&nbsp; |&nbsp;&nbsp;&nbsp;
                                                            <b>Tax Amount ({{\App\Modules\Store\Models\StoreOrder::VAT_PERCENTAGE_VALUE}} %)  : {{getNumberFormattedAmount($taxableItemsData['tax_amount'])}}</b>
                                                            &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                                                            <b>Total Amount : {{getNumberFormattedAmount($taxableItemsData['total_amount'])}}</b>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Product Name</th>
                                                                <th>Vendor</th>
                                                                <th>Package Name</th>

                                                                <th title="Store : Ordered Quantity">
                                                                    Initial Quantity (Store)
                                                                </th>

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




                                                                    <input id="orderItem{{$i}}"  name="order_items[]" type="hidden" class="order_item" value="{{$orderDetail->store_order_detail_code}}" >

                                                                    <td>{{ ++$i }}</td>
                                                                    <td>


                                                                        {{ $orderDetail->product_name }}<br>
                                                                        {{ isset($orderDetail->product_variant_name) ? $orderDetail->product_variant_name :'' }}<br>
                                                                        <small><b>Available Stock : {{$orderDetail->current_stock}}</b> </small>
                                                                    </td>

                                                                    <td>
                                                                        <p title="Vendor">{{ $orderDetail->vendor_name }}</p>
                                                                    </td>
                                                                    <td>
                                                                        {{$orderDetail->package_name ?? $orderDetail->old_package_name}}
                                                                        (B.L : {{$orderDetail->package_order}})
                                                                        <br>
                                                                        <small>{{$orderDetail->package_code}}</small>
                                                                    </td>

                                                                    <td>
                                                                        {{ $orderDetail->initial_order_quantity }}
                                                                    </td>

                                                                    <td>
                                                                        @if($storeOrder->delivery_status == 'accepted'|| $storeOrder->delivery_status == 'processing' )
                                                                            <input style="width:100px;" min="1" max="{{$orderDetail->initial_order_quantity}}"  type="number" value="{{ $orderDetail->quantity }}" name="dispatchable_quantity[]">
                                                                        @else
                                                                            {{ $orderDetail->quantity }}
                                                                        @endif

                                                                    </td>

                                                                    <td>{{ getNumberFormattedAmount($orderDetail->unit_rate)}}</td>
                                                                    <td>{{ getNumberFormattedAmount($orderDetail->sub_total) }}</td>

                                                                    <td>

                                                                        @if($storeOrder->delivery_status == 'accepted' || $storeOrder->delivery_status == 'processing' )

                                                                            <select id="acceptance_status" style="width:132px;" class="form-control select2" name="acceptance_status[]"  >

                                                                                <option {{($orderDetail->acceptance_status == 'accepted') ? 'selected' : '' }} value='accepted'>Accepted</option>
                                                                                <option {{($orderDetail->acceptance_status == 'rejected') ? 'selected' :''  }} value ='rejected'>Rejected</option>
                                                                            </select>

                                                                        @else

                                                                            <badge class="label label-{{returnLabelColor($orderDetail->acceptance_status)}}">
                                                                                {{ ucfirst($orderDetail->acceptance_status)  }}
                                                                            </badge>
                                                                        @endif

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
                                                                <th>Package Name</th>

                                                                <th title="Store : Ordered Quantity">
                                                                    Initial Quantity (Store)
                                                                </th>

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
                                                                        {{ $orderDetail->product_name }}<br>
                                                                        {{ isset($orderDetail->product_variant_name) ? $orderDetail->product_variant_name : '' }}
                                                                        <small><b>Available Stock : {{$orderDetail->current_stock}}</b> </small>
                                                                    </td>

                                                                    <td>
                                                                        <p title="Vendor">{{ $orderDetail->vendor_name }}</p>
                                                                    </td>

                                                                    <td>
                                                                        {{$orderDetail->package_name ?? $orderDetail->old_package_name}}
                                                                        (B.L : {{$orderDetail->package_order}})
                                                                        <br>
                                                                        <small>{{$orderDetail->package_code}}</small>
                                                                    </td>


                                                                    <td>
                                                                        {{ $orderDetail->initial_order_quantity }}
                                                                    </td>


                                                                    <td>
                                                                        @if($storeOrder->delivery_status == 'accepted'|| $storeOrder->delivery_status == 'processing')
                                                                            <input style="width:100px;" min="1" max="{{$orderDetail->initial_order_quantity}}"  type="number" value="{{ $orderDetail->quantity }}" name="dispatchable_quantity[]">
                                                                        @else
                                                                            {{ $orderDetail->quantity }}
                                                                        @endif

                                                                    </td>

                                                                    <td>{{ getNumberFormattedAmount($orderDetail->unit_rate)}}</td>
                                                                    <td>
                                                                        {{ getNumberFormattedAmount($orderDetail->sub_total) }}
                                                                    </td>

                                                                    <td>
                                                                        @if($storeOrder->delivery_status == 'accepted' || $storeOrder->delivery_status == 'processing' )

                                                                            <select id="acceptance_status" style="width:132px;" class="form-control select2" name="acceptance_status[]"  >
                                                                                <option {{($orderDetail->acceptance_status == 'accepted') ? 'selected' : '' }} value='accepted'>Accepted</option>
                                                                                <option {{($orderDetail->acceptance_status == 'rejected') ? 'selected' :''  }} value ='rejected'>Rejected</option>
                                                                            </select>

                                                                        @else

                                                                            <badge class="label label-{{returnLabelColor($orderDetail->acceptance_status)}}">
                                                                                {{ ucfirst($orderDetail->acceptance_status)  }}
                                                                            </badge>
                                                                        @endif

                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            @endif



                                            @php
                                                $statusFormHideCondition = ($storeOrder->delivery_status == 'cancelled' || $storeOrder->delivery_status == 'dispatched'
                                                   || $storeOrder->delivery_status == 'ready_to_dispatch'
                                                );
                                            @endphp


                                            {{--                                            @if($storeOrder->delivery_status == 'accepted' || $storeOrder->delivery_status == 'processing' )--}}
                                            {{--                                                <hr>--}}
                                            {{--                                                <div class="alert alert-warning">--}}
                                            {{--                                                    <strong>While Finalizing Order !</strong><br>--}}
                                            {{--                                                    <ul>--}}
                                            {{--                                                        <li>--}}
                                            {{--                                                            <strong>please do not leave acceptance status of any product to pending (set acceptance status to either accepted or rejected.)</strong>--}}
                                            {{--                                                        </li>--}}

                                            {{--                                                    </ul>--}}


                                            {{--                                                </div>--}}

                                            {{--                                                <hr>--}}
                                            {{--                                            @endif--}}


{{--                                            @if($storeOrder->delivery_status == 'ready_to_dispatch')--}}
{{--                                                @include(''.$module.'.warehouse.store-order.store_order_dispatch_detail')--}}
{{--                                            @endif--}}

                                            @if(!$statusFormHideCondition)
                                                @include(''.$module.'.warehouse.store-order.status-form')
                                            @endif
                                        </div>
                                    </div>


                                </form>


{{--                            @endcan--}}

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
                                                        <td>{{ucfirst($storeOrder->storeOrderDispatchDetail->driver_name)}}</td>
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
                                        {{--                                       @foreach($storeOrderStatusLogs as $i => $storeOrderStatusLog)--}}
                                        @foreach($storeOrder->statusLogs as $i =>$storeOrderStatusLog)
                                            <tr>
                                                <td>{{++$i}}</td>
                                                <td>{{$storeOrderStatusLog->status}}</td>
                                                <td>{{$storeOrderStatusLog->updated_at}}</td>
                                                <td>
                                                    @if($storeOrderStatusLog->status == 'pending')
                                                        N / A
                                                    @else
                                                        <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#statusLogRemarks{{$i}}">
                                                            <i class="fa fa-eye"></i>
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                            <!-- Modal -->
                                            <div id="statusLogRemarks{{$i}}" class="modal fade" role="dialog">
                                                <div class="modal-dialog">

                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title">Remarks - Order Code : {{$storeOrder->store_order_code}} / Status : {{ucwords($storeOrderStatusLog->status)}}</h4>
                                                        </div>
                                                        <div class="modal-body">

                                                            {!! $storeOrderStatusLog->remarks !!}

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </section>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>

    @include('AlpasalWarehouse::warehouse.store-order.remarks-lists')

@endsection

@push('scripts')
    <script>

        $(document).ready(function() {
            $("#check_all_order_items").click(function() {
                $(".order_item").prop('checked', $(this).prop('checked'));
            });
        });

        // $('.summernote').summernote({
        //     height: 150, // set editor height
        //     minHeight: null, // set minimum height of editor
        //     maxHeight: null, // set maximum height of editor
        //     focus: false // set focus to editable area after initializing summernote
        // });

        $('#order_status_submit').on('click',function(e){
            e.preventDefault();
            var status = $('#delivery_status').val();
            Swal.fire({
                title: 'Are you sure you want to change the store order delivery state to ' +status+ '?',
                showDenyButton: true,
                //showCancelButton: true,
                confirmButtonText: `Okay`,
                denyButtonText: `No wait`,
                confirmButtonColor: 'Green',
                cancelButtonColor: 'Red',
                width: 600,
                padding: '5em',
                // imageUrl: ,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $('#filter_form').submit();
                    Swal.fire('Saved!', '', 'success')
                } else if (result.isDenied) {
                    Swal.fire('Changes are not saved', '', 'info')
                }
            })
        });

    </script>
@endpush

