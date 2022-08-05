<div class="table">
    <div class="thead">
        <div class="tr">
            <div class="td">Product</div>
            <div class="td">Vendor</div>
            <div class="td">Initial Order Quantity</div>
            <div class="td">Quantity(Dispatching)</div>
            <div class="td">Unit Rate</div>
            <div class="td">Sub Total</div>
            <div class="td">Delivery Status</div>
            <div class="td">Action</div>
        </div>
    </div>
    <div class="tbody">
        @if(isset($nonTaxableOrderProducts))
            @foreach($nonTaxableOrderProducts as $nonTaxableOrderProduct)
                <form class="tr" method="post"
                      action="{{route('warehouse.warehouse-pre-orders.store-orders.detail.update',['storePreOrder'=>$storePreOrder->store_preorder_code,'preOrderDetail'=>$nonTaxableOrderProduct->store_preorder_detail_code])}}">
                    {{csrf_field()}}
                    <div class="td">
                        <b>{{$nonTaxableOrderProduct->product_name}}</b>
                        <br>
                        <small>{{$nonTaxableOrderProduct->product_variant_name}}</small>
                        <small><b>Available Stock : {{$nonTaxableOrderProduct->current_stock ?? 0}}</b> </small>
                    </div>
                    <div class="td">
                        {{$nonTaxableOrderProduct->vendor_name}}
                    </div>
                    <div class="td">
                        {{$nonTaxableOrderProduct->initial_order_quantity}}
                    </div>
                    <div class="td">
                        @if($storePreOrder->status == 'finalized')
                            <input type="number" min="0" max="{{$nonTaxableOrderProduct->initial_order_quantity}}" name="dispatch_quantity" value="{{$nonTaxableOrderProduct->quantity}}"/>
                        @else
                            {{$nonTaxableOrderProduct->quantity}}
                        @endif
                    </div>
                    <div class="td">
                        {{$nonTaxableOrderProduct->unit_rate}}
                    </div>
                    <div class="td">
                        {{$nonTaxableOrderProduct->unit_rate * $nonTaxableOrderProduct->quantity}}
                    </div>
                    <div class="td">
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
                    <div class="td action">
                        @if($storePreOrder->status == 'finalized')
                            <button type="submit">Update</button>
                        @endif
                    </div>
                </form>
            @endforeach
        @endif
    </div>
</div>
