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
        @if(isset($nonTaxableOrderProducts))
            @foreach($nonTaxableOrderProducts as $nonTaxableOrderProduct)
                <form class="tr "  method="post"
                      action="{{route('warehouse.warehouse-pre-orders.store-orders.detail.update',['storePreOrder'=>$storePreOrder->store_preorder_code,'preOrderDetail'=>$nonTaxableOrderProduct->store_preorder_detail_code])}}" style="@if($nonTaxableOrderProduct->is_active_in_preorder_list == 0) background:#debf6d @elseif($nonTaxableOrderProduct->delivery_status==0) background:#f5917d @endif">
                    {{csrf_field()}}
                    <div class="td">
                        <b>{{$nonTaxableOrderProduct->product_name}}</b>
                        <br>
                        <small>{{$nonTaxableOrderProduct->product_variant_name}}</small>
                        @if($storePreOrder->status != "dispatched")
                        <small><b>Available Stock : {{$nonTaxableOrderProduct->current_stock ?? 0}}</b> </small>
                        @endif
                    </div>
                    <div class="td">
                        {{$nonTaxableOrderProduct->vendor_name}}
                    </div>
                    <div class="td">
                        {{$nonTaxableOrderProduct->ordered_package_name ?? $nonTaxableOrderProduct->package_name}}
                        ({{$nonTaxableOrderProduct->package_order}})
                    </div>
                    <div class="td">
                        {{$nonTaxableOrderProduct->initial_order_quantity}}
                    </div>
                    <div class="td" style="width: 22%;">
                        @if(($storePreOrder->status == 'finalized'||$storePreOrder->status == 'processing') && $nonTaxableOrderProduct->is_active_in_preorder_list)
                            <input style="width: 40%; float: left; margin-right: 10px;" type="number" min="0" max="{{$nonTaxableOrderProduct->initial_order_quantity}}" name="dispatch_quantity" class="dispatch_quantity" value="{{$nonTaxableOrderProduct->quantity}}"/>
                            <button style="float: left;" type="submit"  class="float-right">Update</button>
                        @else
                            {{$nonTaxableOrderProduct->quantity}}
                        @endif
                    </div>
                    <div class="td">
                        {{roundPrice($nonTaxableOrderProduct->unit_rate) }}
                    </div>
                    <div class="td">
                        {{ roundPrice($nonTaxableOrderProduct->sub_total)}}
                    </div>
                    <div class="td">
                        @if(($storePreOrder->status == 'finalized'||$storePreOrder->status == 'processing') && $nonTaxableOrderProduct->is_active_in_preorder_list)
                            <select name="delivery_status" class="delivery_status">
                                <option value="1" {{$nonTaxableOrderProduct->delivery_status == 1? 'selected' : ''}}>Accept</option>
                                <option value="0" {{$nonTaxableOrderProduct->delivery_status == 0? 'selected' : ''}}>Reject</option>
                            </select>
                        @else
                            <badge class="label label-{{returnLabelColor($nonTaxableOrderProduct->delivery_status)}}">
                                {{$nonTaxableOrderProduct->delivery_status_name}}
                            </badge>

                        @endif

                    </div>
{{--                    <div class="td action">--}}
{{--                        @if($storePreOrder->status == 'finalized' && $nonTaxableOrderProduct->is_active_in_preorder_list)--}}
{{--                            <button type="submit" >Update</button>--}}
{{--                        @endif--}}
{{--                    </div>--}}
                </form>
            @endforeach
        @endif
    </div>
</div>


