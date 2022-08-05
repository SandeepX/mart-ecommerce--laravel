@if(!$hasOrderBeenPlaced)
    <div class="box-header with-border">
        <h3 class="box-title">Add New {{$title}}</h3>
        <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">
            @can('Place Order For Pre Order')
                <a href="{{ route('warehouse.warehouse-pre-orders.place-order.export',['warehousePreOrderCode'=>$warehousePreOrderListingCode,'vendorCode'=>$vendorCode]) }}" style="border-radius: 0px; " class="btn btn-sm btn-success">
                    <i class="fa fa-file-excel-o"></i>
                    Download Excel File
                </a>
            @endcan
            @can('View List Of Vendors For Pre Orders')
                <a href="#" style="border-radius: 0px; " class="btn btn-sm btn-primary">
                    <i class="fa fa-list"></i>
                    List of {{formatWords($title,true)}}
                </a>
            @endcan
        </div>
    </div>

    @can('Place Order For Pre Order')
        <div class="box-body">
            <form id="purchase-order-form" class="form-horizontal" role="form" action="{{route('warehouse.warehouse-pre-orders.place-order.store',['warehousePreOrderCode'=>$warehousePreOrderListingCode,'vendorCode'=>$vendorCode])}}" method="post">
                {{csrf_field()}}

                <div class="box-body">
                    <table id="product-order-list-tbl"
                           class="table table-condensed table-bordered table-striped table-responsive items_table">
                        <thead class="bg-primary">
                        <tr>
                            <th>S.N</th>
                            <th style="width:30%">Product</th>
                            <th style=" width:14%">Variant</th>
                            <th style=" width:7%">Est. Qty</th>
                            <th style=" width:7%">Quantity</th>
                            <th style="width:15%">Price</th>
                            <th style="width:15%">Subtotal</th>
                        </tr>
                        </thead>
                        <tbody id="product-order-list-tbl-body" style="font-size: 16px;font-weight: bold;overflow: scroll;">
                        @forelse($storePreOrderProducts as $i => $product)
                            <tr>
                                <td>{{++$i}}</td>
                                <td>
                                    {{$product->product_name}}
                                    <input type="hidden" class="form-control" style="width:75px" name="product_code[]"
                                           value="{{$product['product_code']}}">
                                    <br>
                                    <small><b>Available Stock : {{$product->current_stock ?? 0}}</b> </small>
                                </td>
                                <td>
                                    {{$product['product_variant_name']}}
                                    <input type="hidden" class="form-control" style="width:75px" name="product_variant_code[]"
                                           value="{{$product['product_variant_code']}}">
                                </td>
                                <td>
                                    {{$product['total_ordered_quantity']}}
                                </td>
                                <td>
                                    @if($hasOrderBeenPlaced)
                                        {{$product['total_ordered_quantity']}}
                                    @else
                                        <input style="width:75px" name="quantity[]" min="0"  type="number" value="{{ old('quantity.'.$loop->index)??$product['total_ordered_quantity']}}">
                                    @endif

                                </td>
                                <td>
                                    {{getNumberFormattedAmount($product['vendor_price'])}}
                                </td>
                                <td>
                                    {{getNumberFormattedAmount($product['sub_total'])}}
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="100%">
                                    <p class="text-center"><b>No records found!</b></p>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    @if($hasOrderBeenPlaced)
                        <a style="width: 49%;margin-left: 17%;" href="javascript:void(0)" class="btn btn-block btn-success">Order Already Placed</a>
                    @else
                        <button id="purchase-order-submit" type="submit" style="width: 49%;margin-left: 17%;" class="btn btn-block btn-primary">Place Order</button>
                    @endif
                </div>
            </form>
        </div>
    @endcan
@endif
