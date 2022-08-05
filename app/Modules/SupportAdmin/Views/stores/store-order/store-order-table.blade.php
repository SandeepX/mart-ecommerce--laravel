<div class="card card-default bg-panel">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="row">
                    <div class="col-md-5">
                        <h3 style="margin-left:10px; font-weight: bold;">List of Store Orders</h3>
                    </div>
                    <div class="col-md-3">
                        <h3 style="font-weight: bold;">{{$storeOrders->total() }}</h3>
                        <p>Total Store Orders</p>
                    </div>

                    <div class="col-md-4">
                        <a style="margin-top: 30px !important;" class="btn btn-danger" data-toggle="collapse" href="#collapseFilter" role="button" aria-expanded="false" aria-controls="collapseExample">
                            <i class="fa  fa-filter"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="panel panel-default collapse" id="collapseFilter" style="background-color: #E4E4E4">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form id="order_filter_form" action="{{route('support-admin.store-order',$storeCode)}}" method="GET">
                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="store_order_code">Store Order Code</label>
                                        <input type="text" class="form-control" name="store_order_code" id="store_order_code"
                                               value="{{$filterParameters['store_order_code']}}">
                                    </div>
                                </div>
                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="delivery_status">Delivery Status</label>
                                        <select name="delivery_status" class="form-control select2" id="delivery_status">
                                            <option value="" {{!isset($filterParameters['delivery_status'][0])?'selected':''}}>All</option>
                                            @foreach($storeOrderDeliveryStatuses as $key=>$deliveryStatus)
                                                <option value="{{$deliveryStatus}}"
                                                    {{ isset($filterParameters['delivery_status'][0]) && $deliveryStatus == $filterParameters['delivery_status'][0] ?'selected' :''}}>
                                                    {{$key}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="payment_status">Payment Status</label>
                                        <select name="payment_status" class="form-control select2" id="payment_status">
                                            <option value="" {{!isset($filterParameters['payment_status'][0])?'selected': ''}}>All</option>
                                            @foreach($paymentStatuses as $paymentStatus)
                                                <option value="{{$paymentStatus}}"
                                                    {{isset($filterParameters['payment_status'][0]) && $paymentStatus == $filterParameters['payment_status'][0] ?'selected' :''}}>
                                                    {{ucwords($paymentStatus)}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="order_date_from">Order Date From</label>
                                        <input type="date" class="form-control" name="order_date_from" id="order_date_from"
                                               value="{{$filterParameters['order_date_from']}}">
                                    </div>

                                </div>

                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="order_date_to">Order Date To</label>
                                        <input type="date" class="form-control" name="order_date_to" id="order_date_to"
                                               value="{{$filterParameters['order_date_to']}}">
                                    </div>
                                </div>

                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="price_condition">Price Condition</label>
                                        <select name="price_condition" class="form-control select2" id="price_condition">
                                            <option value="" {{$filterParameters['price_condition'] == ''}}>All</option>
                                            @foreach($priceConditions as $key=>$priceCondition)
                                                <option value="{{$priceCondition}}"
                                                    {{$priceCondition == $filterParameters['price_condition'] ?'selected' :''}}>
                                                    {{ucwords($key)}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="total_price">Price</label>
                                        <input type="number" min="0" class="form-control" name="total_price" id="total_price"
                                               value="{{$filterParameters['total_price']}}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <button type="button" class="btn btn-primary" id="order-filter-btn">View Orders</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
<div class="col-xs-12">
    <div class="panel panel-default">
        <table id="admin-store-orders-table" class="table table-bordered table-striped" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>#</th>
                <th>Order Code</th>
                <th>Total Price</th>
                <th>Acceptable Price</th>
                {{-- <th>Store Name</th> --}}
                <th>Payment Status</th>
                <th>Delivery Status</th>
                <th>Order Date</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <div id="orders">

                @forelse($storeOrders as $i => $storeOrder)
                    <tr>
                        <td>{{++$i}}</td>
                        <td>{{$storeOrder->store_order_code}}</td>
                        <td>{{$storeOrder->total_price}}</td>
                        <td>{{$storeOrder->acceptable_amount}}</td>
                        {{--                                    <td>{{$storeOrder->store->store_name}}</td>--}}
                        <td>{{ $storeOrder->getLatestTranslatedOfflinePaymentStatus() }}</td>
                        <td>{{$storeOrder->delivery_status}}</td>
                        <td>{{$storeOrder->created_at}}</td>

                        <td>


                            <a>
                                <button data-toggle="modal" value="{{$storeOrder->store_order_code}}" data-url="{{route('support-admin.store-order-details',['storeOrderCode'=> $storeOrder->store_order_code])}}" data-target="#modal-target1" id="order_view_btn" data-placement="left" data-tooltip="true" title="Details" class="btn btn-xs btn-info">
                                    <span class="fa fa-eye"></span>
                                    Details
                                </button>
                            </a>

                            <div class="modal fade" id="modal-target1" >
                                <div class="modal-dialog" style="width: 80% !important; height: 90vh; overflow: scroll;">
                                    <div class="order-detail-modal-content" style="background-color: white" >
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="100%">
                            <p class="text-center"><b>No records found!</b></p>
                        </td>
                    </tr>
                @endforelse
            </div>
            </tbody>
        </table>
        <div class="pagination" id="order-pagination">
            @if(isset($storeOrders))
                {{$storeOrders->appends($_GET)->links()}}
            @endif
        </div>

    </div>
</div>
    </div>
</div>









