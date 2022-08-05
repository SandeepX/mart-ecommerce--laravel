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
