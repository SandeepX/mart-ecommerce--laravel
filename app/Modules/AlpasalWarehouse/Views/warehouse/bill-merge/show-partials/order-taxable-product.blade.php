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
        @if(isset($taxableOrderProducts))
            @foreach($taxableOrderProducts as $taxableOrderProduct)
                <form class="tr" method="post"
                      action="{{route('warehouse.warehouse-pre-orders.store-orders.detail.update',['storePreOrder'=>$storePreOrder->store_preorder_code,'preOrderDetail'=>$taxableOrderProduct->store_preorder_detail_code])}}">
                    {{csrf_field()}}
                    <div class="td">
                        <input type="hidden"  name="store_preorder_detail_code" value="{{$taxableOrderProduct->store_preorder_detail_code}}"/>
                        <b>{{$taxableOrderProduct->product_name}}</b>
                        <br>
                        <small>{{$taxableOrderProduct->product_variant_name}}</small>
                        <small><b>Available Stock : {{$taxableOrderProduct->current_stock ?? 0}}</b> </small>
                    </div>
                    <div class="td">
                        {{$taxableOrderProduct->vendor_name}}
                    </div>
                    <div class="td">
                        {{$taxableOrderProduct->initial_order_quantity}}
                    </div>
                    <div class="td">
                        @if($storePreOrder->status == 'finalized')
                            <input type="number" min="0" max="{{$taxableOrderProduct->initial_order_quantity}}" name="dispatch_quantity" value="{{$taxableOrderProduct->quantity}}"/>
                        @else
                            {{$taxableOrderProduct->quantity}}
                        @endif
                    </div>
                    <div class="td">
                        {{$taxableOrderProduct->unit_rate}}
                    </div>
                    <div class="td">
                        {{$taxableOrderProduct->unit_rate * $taxableOrderProduct->quantity}}
                    </div>
                    <div class="td">
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
