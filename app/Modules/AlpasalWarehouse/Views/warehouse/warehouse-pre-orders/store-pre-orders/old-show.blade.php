@extends('AdminWarehouse::layout.common.masterlayout')
@push('css')
    <style>
        main {
            display: table-row-group
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

                        <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">
                            <a href="{{ route('warehouse.warehouse-pre-orders.store-orders',$storePreOrder['warehouse_preorder_listing_code']) }}" style="border-radius: 0px; " class="btn btn-sm btn-success">
                                <i class="fa fa-list"></i>
                                List of Store Orders
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
                                        Store : <strong>{{ $storePreOrder->store->store_name }} ({{ $storePreOrder->store->store_code }})</strong><br>
                                        Contact : <strong>{{ $storePreOrder->store->store_contact_phone }} | {{ $storePreOrder->store->store_contact_mobile }}</strong><br>
                                    </address>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 invoice-col">
                                    To
                                    <address>
                                        <strong>{{$storePreOrder->warehousePreOrderListing->warehouse->warehouse_name}} ({{$storePreOrder->warehousePreOrderListing->warehouse->warehouse_code}})</strong>
                                    </address>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 invoice-col">
                                    <br>
                                    <b>Pre-Order Code : </b>{{ $storePreOrder->store_preorder_code }}<br>
                                    <b>Total Price <small>(Initial Order Qty)</small> : </b>{{ $storePreOrder->storePreOrderView->total_price }}<br>
                                    <b>Acceptable Price <small>(Accepted Order Qty)</small>  : </b>{{ $storePreOrder->acceptable_amount ? $storeOrder->acceptable_amount : 'N/A' }}<br>
                                    <b>Payment Status : </b>{{ $storePreOrder->payment_status == '0' ? 'unpaid' : 'paid' }}<br>
                                    <b>Status : </b> {{ ucwords($storePreOrder->status) }}<br><br>

                                    {{-- @if($storeOrder->delivery_status == 'processing'||$storeOrder->delivery_status == 'dispatched')
                                         <a target="_blank" class="btn btn-xs btn-primary" href="{{route('warehouse.store.orders.pdf',$storeOrder->store_order_code) }}">View Payment Bill</a>
                                         <a class="btn btn-xs btn-primary" href="{{route('warehouse.store.orders.pdf',[$storeOrder->store_order_code,'action'=>'download']) }}">Download Payment Bill</a>
                                     @endif--}}

                                </div>

                            </div>


                            <!-- /.row -->

                            <div class="row">
                                <div class="col-xs-12 table-responsive">

                                    <div class="panel panel-danger">
                                        @if(isset($taxableOrderDetails))
                                            <div class="panel-heading">
                                                <b>Taxable Products : {{count($taxableOrderProducts) }}</b>
                                                <div class="pull-right">

                                                    <b>SubTotal : {{$taxableOrderDetails['tax_excluded_amount']}}</b>
                                                    &nbsp;&nbsp;&nbsp; |&nbsp;&nbsp;&nbsp;
                                                    <b>Tax Amount (13 %)  : {{$taxableOrderDetails['tax_amount']}}</b>
                                                    &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                                                    <b>Total Amount : {{$taxableOrderDetails['total_amount']}}</b>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="row">
                                                        <div class="col-sm-2">
                                                            <h5>Product</h5>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <h5>Vendor</h5>
                                                        </div>
                                                        <div class="col-sm-1">
                                                            <h5>Order Quantity</h5>
                                                        </div>
                                                        <div class="col-sm-1">
                                                            <h5>Quantity(Dispatching)</h5>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <h5>Unit Rate</h5>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <h5>Sub Total</h5>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <h5>Delivery Status</h5>
                                                        </div>
                                                    </div>

                                                    @forelse($taxableOrderProducts as $taxableOrderProduct)
                                                        <form action="#"
                                                              method="post" class="price-update-form">
                                                            {{csrf_field()}}
                                                            <div class="row">
                                                                <div class="col-sm-2">
                                                                    <input type="hidden"  name="store_preorder_detail_code" value="{{$taxableOrderProduct->store_preorder_detail_code}}"/>
                                                                    <h5> {{$taxableOrderProduct->product_name}}</h5>
                                                                    <small>{{$taxableOrderProduct->product_variant_name}}</small>
                                                                </div>
                                                                <div class="col-sm-2">
                                                                    {{$taxableOrderProduct->vendor_name}}
                                                                </div>
                                                                <div class="col-sm-1">
                                                                    {{$taxableOrderProduct->initial_order_quantity}}
                                                                </div>
                                                                <div class="col-sm-1">
                                                                    <input type="hidden"  name="dispatch_quantity" value="{{$taxableOrderProduct->quantity}}"/>
                                                                </div>
                                                                <div class="col-sm-2">
                                                                    {{$taxableOrderProduct->unit_rate}}
                                                                </div>
                                                                <div class="col-sm-2">
                                                                    {{$taxableOrderProduct->unit_rate * $taxableOrderProduct->quantity}}
                                                                </div>
                                                                <div class="col-sm-2">
                                                                    <select name="delivery_status">
                                                                        <option value="1" {{$taxableOrderProduct->delivery_status == 1? 'selected' : ''}}>Accept</option>
                                                                        <option value="0" {{$taxableOrderProduct->delivery_status == 0? 'selected' : ''}}>Reject</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    @empty
                                                    @endforelse
                                                </div>
                                                <br>

                                                {{--   @if(count($nonTaxableOrderDetails) > 0)
                                                       @php
                                                           $total = 0;
                                                       @endphp
                                                       <div class="panel panel-primary">
                                                           <div class="panel-heading">
                                                               <b>Non Taxable Products : {{count($nonTaxableOrderDetails)}}</b>
                                                               <div class="pull-right">
                                                                   <b>Total Amount : {{$nonTaxableItemsTotal}}</b>
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
                                                                               {{ $orderDetail->product_name }}<br>
                                                                               {{ isset($orderDetail->product_variant_name) ? $orderDetail->product_variant_name : '' }}
                                                                               <small><b>Available Stock : {{$orderDetail->current_stock}}</b> </small>
                                                                           </td>

                                                                           <td>
                                                                               <p title="Vendor">{{ $orderDetail->vendor_name }}</p>
                                                                           </td>
                                                                           @if($storeOrder->delivery_status != 'pending'

                                                                           )
                                                                               <td>
                                                                                   {{ $orderDetail->initial_order_quantity }}
                                                                               </td>
                                                                           @endif

                                                                           <td>
                                                                               @if($storeOrder->delivery_status == 'pending'
                                                                                   || $storeOrder->delivery_status == 'under-verification'
                                                                                   )
                                                                                   <input style="width:100px;" min="1" max="{{$orderDetail->current_stock}}"  type="number" value="{{ $orderDetail->quantity }}" name="dispatchable_quantity[]">
                                                                               @else
                                                                                   {{ $orderDetail->quantity }}
                                                                               @endif

                                                                           </td>

                                                                           <td>{{ roundPrice($orderDetail->unit_rate)}}</td>
                                                                           <td>
                                                                               {{ $orderDetail->sub_total }}
                                                                           </td>

                                                                           <td>
                                                                               @if($storeOrder->delivery_status == 'pending' || $storeOrder->delivery_status == 'under-verification' )

                                                                                   <select id="acceptance_status" style="width:132px;" class="form-control select2" name="acceptance_status[]"  >

                                                                                       <option {{($orderDetail->acceptance_status == 'pending') ? 'selected' :''  }} value = 'pending' >Pending</option>
                                                                                       <option {{($orderDetail->acceptance_status == 'accepted') ? 'selected' : '' }} value='accepted'>Accepted</option>
                                                                                       <option {{($orderDetail->acceptance_status == 'rejected') ? 'selected' :''  }} value ='rejected'>Rejected</option>
                                                                                   </select>

                                                                               @else

                                                                                   <badge class="label label-{{$acceptanceStatusBtnColors[$orderDetail->acceptance_status]}}">
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
                                                   @endif--}}



                                            </div>
                                        </div>


                                        <br>

                            {{-- <div class="panel panel-info">
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
                                         --}}{{--                                       @foreach($storeOrderStatusLogs as $i => $storeOrderStatusLog)--}}{{--
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
                             </div>--}}

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
    <script>
        $(document).ready(function() {
            $('.summernote').summernote({
                height: 150, // set editor height
                minHeight: null, // set minimum height of editor
                maxHeight: null, // set maximum height of editor
                focus: false // set focus to editable area after initializing summernote
            });
        });

    </script>
@endpush
