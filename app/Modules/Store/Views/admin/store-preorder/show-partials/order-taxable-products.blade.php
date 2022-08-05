<div class="table">
    <div class="thead">
        <div class="tr">
            <div class="td">Product</div>
            <div class="td">Vendor</div>
            <div class="td">Ordered Package Type</div>
            <div class="td">Initial Order Quantity</div>
            <div class="td">Quantity(Dispatching)</div>
            <div class="td">Unit Rate</div>
            <div class="td">Sub Total</div>
            <div class="td">Delivery Status</div>
        </div>
    </div>
    <div class="tbody">
        @if(isset($taxableOrderProducts))
            @foreach($taxableOrderProducts as $taxableOrderProduct)
                <form class="tr {{($taxableOrderProduct->is_active_in_preorder_list == 0) ? 'bg-danger' : '' }}" method="post"
                      action="{{route('warehouse.warehouse-pre-orders.store-orders.detail.update',['storePreOrder'=>$storePreOrder->store_preorder_code,'preOrderDetail'=>$taxableOrderProduct->store_preorder_detail_code])}}"
                      style="@if($taxableOrderProduct->is_active_in_preorder_list == 0) background:#debf6d @elseif($taxableOrderProduct->delivery_status==0) background:#f5917d @endif" >
                    {{csrf_field()}}
                    <div class="td">
                        <input type="hidden"  name="store_preorder_detail_code" value="{{$taxableOrderProduct->store_preorder_detail_code}}"/>
                        <b>{{$taxableOrderProduct->product_name}}</b>
                        <br>
                        <small>{{$taxableOrderProduct->product_variant_name}}</small>
                        @if($storePreOrder->status != "dispatched")
                            <small><b>Available Stock : {{$taxableOrderProduct->current_stock ?? 0}}</b> </small>
                        @endif
                    </div>
                    <div class="td">
                        {{$taxableOrderProduct->vendor_name}}
                    </div>
                    <div class="td">
                        {{$taxableOrderProduct->ordered_package_name ?? $taxableOrderProduct->package_name}}
                        ({{$taxableOrderProduct->package_order}})
                    </div>
                    <div class="td">
                        {{$taxableOrderProduct->initial_order_quantity}}
                    </div>
                    <div class="td">
                            {{$taxableOrderProduct->quantity}}
                    </div>
                    <div class="td">
                        {{roundPrice($taxableOrderProduct->unit_rate)}}
                    </div>
                    <div class="td">
                        {{$taxableOrderProduct->sub_total}}
                    </div>
                    <div class="td">
                            <badge class="label label-{{returnLabelColor($taxableOrderProduct->delivery_status)}}">
                                {{$taxableOrderProduct->delivery_status_name}}
                            </badge>
                    </div>

                </form>
            @endforeach
        @endif
    </div>
</div>
