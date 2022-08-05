@if(isset($store))
    <div class="col-xs-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">
                    List Of Store wise PreOrders
                </h3>
            </div>
                <div class="box-body">
                    <div class="panel panel-primary">
                        @if(isset($store) && $store->count())
                        <div class="col-xs-3">
                            <div>Store Name: {{$store->store_name}}</div>
                            <div>Store Code: {{$store->store_code}}</div>
                            <div>Store Status: {{$store->status}}</div>
                        </div>
                        @endif
                        @if(isset($preOrder) && $preOrder->count())
                        <div class="col-xs-3">
                            <div>Pre Order Listing Code: {{$preOrder->warehouse_preorder_listing_code}}</div>
                            <div>Pre Order Opened By Warehouse: {{$preOrder->warehouse_name}}</div>
                            <div>Pre Order Start Time: {{$preOrder->start_time}}</div>
                            <div>Pre Order End Time: {{$preOrder->end_time}}</div>
                            <div>Pre Order Finalization Time: {{$preOrder->finalization_time}}</div>
                            <div style="color: red">Pre Order Status: {{ucfirst($preOrder->status)}}</div>
                        </div>
                                <div class="col-xs-3">
                                    <div>Pre Order Name: {{$preOrder->pre_order_name}}</div>
                                    <div>Pre Order Code: {{$preOrder->store_preorder_code}}</div>
                                    <div>Total products on Preorder: {{$preOrder->total_products}}</div>
                                    @foreach($deactiveProducts as $deactiveProduct)
                                    <div>Total no of deactive Products In Preorder: {{isset($deactiveProduct->total_deactive_products_in_preorder) ? $deactiveProduct->total_deactive_products_in_preorder : 0 }}</div>
                                    <div>Total no of deactive Products In Warehouse: {{isset($deactiveProduct->total_deactive_products) ? $deactiveProduct->total_deactive_products : 0 }}</div>
                                    @endforeach
                                    @foreach($deletedProducts as $deletedProduct)
                                    <div>Total no of deleted Products: {{isset($deletedProduct->total_deleted_products) ? $deletedProduct->total_deleted_products : 0 }}</div>
                                    @endforeach
                                </div>
                         @endif
                         @if(isset($amount) && $amount->count())
                        <div class="col-xs-3">
                            <div>Total amount deducted on Preorder: {{getNumberFormattedAmount($amount->total_price)}}</div>
                            @foreach($activeProducts as $activeProduct)
                                <div>Total Taxable Amount: {{getNumberFormattedAmount($amount->total_price - $activeProduct->sum_of_active_products)}}</div>
                            <div>Sum Of all active Product inside Preorder: {{isset($activeProduct->sum_of_active_products) ? getNumberFormattedAmount($activeProduct->sum_of_active_products) : 0 }}</div>
                            @endforeach
                            <div>Total amount to be Refunded: {{getNumberFormattedAmount($amount->total_price)}}</div>
                        </div>
                        @endif
                    </div>
                    @if(isset($preOrderProducts) && $preOrderProducts->count())
                    <table id="data-table" class="table table-bordered table-striped" cellspacing="0"
                           width="100%">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Is Taxable</th>
                            <th>Unit Rate</th>
                            <th>Sub Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($preOrderProducts as $i => $preOrderProduct)
                            <tr>
                                <td>{{++$i}}</td>
                                <td>{{$preOrderProduct->product_name}} ({{$preOrderProduct->product_code}})</td>
                                <td>{{$preOrderProduct->quantity}}</td>
                                @if($preOrderProduct->is_taxable == 1)
                                <td><span >&#10004;</span></td>
                                @elseif($preOrderProduct->is_taxable == 0)
                                <td><span>&#10006;</span></td>
                                @endif
                                <td>{{getNumberFormattedAmount($preOrderProduct->unit_rate)}}</td>
                                <td>{{getNumberFormattedAmount($preOrderProduct->quantity * $preOrderProduct->unit_rate)}}</td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>
                     @endif
                </div>
        </div>
    </div>

@endif
