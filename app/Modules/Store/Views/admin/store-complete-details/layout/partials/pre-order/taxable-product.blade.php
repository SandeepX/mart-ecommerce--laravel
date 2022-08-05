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
    <div class="tbody" style="display: table-row-group;">
        @if(isset($taxableOrderProducts))
            @foreach($taxableOrderProducts as $taxableOrderProduct)
                <div class="tr" method="post" style="display: table-row;" >
                        <div class="td" style="display: table-cell;border: 1px solid black;padding: 1px;">
{{--                            <input type="hidden"  name="store_preorder_detail_code" value="{{$taxableOrderProduct->store_preorder_detail_code}}"/>--}}
                            <b>{{$taxableOrderProduct->product_name}}</b>
                            <br>
                            <small>{{$taxableOrderProduct->product_variant_name}}</small>
                            <small><b>Available Stock : {{$taxableOrderProduct->current_stock ?? 0}}</b> </small>
                        </div>
                        <div class="td" style="display: table-cell;border: 1px solid black;padding: 1px;">
                            {{$taxableOrderProduct->vendor_name}}
                        </div>

                    <div class="td" style="display: table-cell;border: 1px solid black;padding: 1px;">
                        {{$taxableOrderProduct->initial_order_quantity}}
                    </div>

                    <div class="td" style="display: table-cell;border: 1px solid black;padding: 1px;">
                        {{$taxableOrderProduct->unit_rate}}
                    </div>
                    <div class="td" style="display: table-cell;border: 1px solid black;padding: 1px;">
                        {{$taxableOrderProduct->unit_rate * $taxableOrderProduct->quantity}}
                    </div>
                    <div class="td" style="display: table-cell;border: 1px solid black;padding: 1px;">
                        {{$taxableOrderProduct->unit_rate * $taxableOrderProduct->quantity}}
                    </div>
                    <div class="td" style="display: table-cell;border: 1px solid black;padding: 1px;">
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
                </div>
            @endforeach
        @endif
    </div>
</div>


{{--<div class="table" style="display: table;border-collapse: separate;">--}}
{{--    <div class="thead" style="display: table-header-group; color: white; font-weight: bold;  background-color: grey;">--}}
{{--        <div class="tr" style="display: table-row;">--}}
{{--            <div class="td" style="display: table-cell; border: 1px solid black; padding: 1px;">Product</div>--}}
{{--            <div class="td" style="display: table-cell;border: 1px solid black;padding: 1px;">Vendor</div>--}}
{{--            <div class="td" style="display: table-cell;border: 1px solid black;padding: 1px;">Initial Order Quantity</div>--}}
{{--            <div class="td" style="display: table-cell;border: 1px solid black;padding: 1px;">Quantity(Dispatching)</div>--}}
{{--            <div class="td" style="display: table-cell;border: 1px solid black;padding: 1px;">Unit Rate</div>--}}
{{--            <div class="td" style="display: table-cell;border: 1px solid black;padding: 1px;">Sub Total</div>--}}
{{--            <div class="td" style="display: table-cell;border: 1px solid black;padding: 1px;">Delivery Status</div>--}}
{{--            <div class="td" style="display: table-cell;border: 1px solid black;padding: 1px;">Action</div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <div class="tbody" style="display: table-row-group;">--}}
{{--        <form class="tr" style="display: table-row;" method="post" action="{{route('warehouse.warehouse-pre-orders.store-orders.detail.update',['storePreOrder'=>$storePreOrder->store_preorder_code,'preOrderDetail'=>$taxableOrderProduct->store_preorder_detail_code])}}">--}}
{{--            {{csrf_field()}}--}}
{{--            <div class="td" style="display: table-cell;border: 1px solid black;padding: 1px;">--}}
{{--                <input type="hidden" name="store_preorder_detail_code" value="SPOD1051" />--}}
{{--                <b>Gulab jamun Mix 180g*78pcs</b>--}}
{{--                <br>--}}
{{--                <small></small>--}}
{{--                <small><b>Available Stock : 1</b> </small>--}}
{{--            </div>--}}
{{--            <div class="td" style="display: table-cell;--}}
{{--                                                                                                                        border: 1px solid black;--}}
{{--                                                                                                                        padding: 1px;">--}}
{{--                Bambino--}}
{{--            </div>--}}
{{--            <div class="td" style="display: table-cell;--}}
{{--                                                                                                                        border: 1px solid black;--}}
{{--                                                                                                                        padding: 1px;">--}}
{{--                14--}}
{{--            </div>--}}
{{--            <div class="td" style="display: table-cell;--}}
{{--                                                                                                                        border: 1px solid black;--}}
{{--                                                                                                                        padding: 1px;">--}}
{{--                14--}}
{{--            </div>--}}
{{--            <div class="td" style="display: table-cell;--}}
{{--                                                                                                                        border: 1px solid black;--}}
{{--                                                                                                                        padding: 1px;">--}}
{{--                8583.19--}}
{{--            </div>--}}
{{--            <div class="td" style="display: table-cell;--}}
{{--                                                                                                                        border: 1px solid black;--}}
{{--                                                                                                                        padding: 1px;">--}}
{{--                120164.66--}}
{{--            </div>--}}
{{--            <div class="td" style="display: table-cell;--}}
{{--                                                                                                                        border: 1px solid black;--}}
{{--                                                                                                                        padding: 1px;">--}}
{{--                <badge class="label label-success">--}}
{{--                    Accepted--}}
{{--                </badge>--}}


{{--            </div>--}}
{{--            <div class="td action" style="display: table-cell;--}}
{{--                                                                                                                        border: 1px solid black;--}}
{{--                                                                                                                        padding: 1px;">--}}
{{--            </div>--}}
{{--        </form>--}}
{{--        <form class="tr" style="display: table-row;" method="post" action="http://backend.allkhata.com/warehouse/warehouse-pre-orders/store-orders/SPO1014/update-detail/SPOD1050">--}}
{{--            <input style="width: 100px;" type="hidden" name="_token" value="8qk0MPdNV5smdbFgXugSqoFZK6EEBatehBmMgtMA">--}}
{{--            <div class="td" style="display: table-cell;--}}
{{--                                                                                                                        border: 1px solid black;--}}
{{--                                                                                                                        padding: 1px;">--}}
{{--                <input type="hidden" name="store_preorder_detail_code" value="SPOD1050" />--}}
{{--                <b>Roasted Vermicelli 150g*112pcs</b>--}}
{{--                <br>--}}
{{--                <small></small>--}}
{{--                <small><b>Available Stock : 3</b> </small>--}}
{{--            </div>--}}
{{--            <div class="td" style="display: table-cell;--}}
{{--                                                                                                                        border: 1px solid black;--}}
{{--                                                                                                                        padding: 1px;">--}}
{{--                Bambino--}}
{{--            </div>--}}
{{--            <div class="td" style="display: table-cell;--}}
{{--                                                                                                                        border: 1px solid black;--}}
{{--                                                                                                                        padding: 1px;">--}}
{{--                22--}}
{{--            </div>--}}
{{--            <div class="td" style="display: table-cell;--}}
{{--                                                                                                                        border: 1px solid black;--}}
{{--                                                                                                                        padding: 1px;">--}}
{{--                22--}}
{{--            </div>--}}
{{--            <div class="td" style="display: table-cell;--}}
{{--                                                                                                                        border: 1px solid black;--}}
{{--                                                                                                                        padding: 1px;">--}}
{{--                4007.96--}}
{{--            </div>--}}
{{--            <div class="td" style="display: table-cell;--}}
{{--                                                                                                                        border: 1px solid black;--}}
{{--                                                                                                                        padding: 1px;">--}}
{{--                88175.12--}}
{{--            </div>--}}
{{--            <div class="td" style="display: table-cell;--}}
{{--                                                                                                                        border: 1px solid black;--}}
{{--                                                                                                                        padding: 1px;">--}}
{{--                <badge class="label label-success">--}}
{{--                    Accepted--}}
{{--                </badge>--}}


{{--            </div>--}}
{{--            <div class="td action" style="display: table-cell;--}}
{{--                                                                                                                        border: 1px solid black;--}}
{{--                                                                                                                        padding: 1px;">--}}
{{--            </div>--}}
{{--        </form>--}}
{{--    </div>--}}
{{--</div>--}}
