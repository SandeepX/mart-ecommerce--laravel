<div class="box-body">
    <div id="product_list_tbl" style="display: block;height: 300px; overflow-y: auto; overflow-x: hidden;">
        <table class="table table-bordered table-striped" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Product</th>
{{--                <th>Variant Name</th>--}}
                <th>Package</th>
{{--                <th>Max Input Quantity</th>--}}
                <th>Quantity</th>
                <th>Unit Rate</th>
                <th>Subtotal</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody id="selected_products_tbl">
            {{--                @if (isset($warehouseStockTransferProducts))--}}
            {{--                    @foreach($warehouseStockTransferProducts as $warehouseStockTransferProduct)--}}
            {{--                        <tr>--}}
            {{--                            <input type="hidden" name="stock_transfer_details_code[]" id="" value="{{$warehouseStockTransferProduct->stock_transfer_details_code }}">--}}
            {{--                            <input type="hidden" name="warehouse_product_master_code[]" id="" value="{{$warehouseStockTransferProduct->warehouse_product_master_code }}">--}}
            {{--                            <td>--}}
            {{--                                {{ $warehouseStockTransferProduct->product_name }}--}}
            {{--                            </td>--}}
            {{--                            <td>--}}
            {{--                                {{ $warehouseStockTransferProduct->product_variant_name }}--}}
            {{--                            </td>--}}
            {{--                            <td class="product-row-input">--}}
            {{--                                <input type="number"--}}
            {{--                                       name="product_quantity[]"--}}
            {{--                                       value="{{ $warehouseStockTransferProduct->sending_quantity }}"--}}
            {{--                                       min="1"--}}
            {{--                                       data-product_price="{{$warehouseStockTransferProduct->vendor_price}}"--}}
            {{--                                       class="form-control"--}}
            {{--                                       required--}}
            {{--                                >--}}
            {{--                            </td>--}}
            {{--                            <td>{{ $warehouseStockTransferProduct->vendor_price }}</td>--}}
            {{--                            <td class="product-subtotal">{{ $warehouseStockTransferProduct->vendor_price *  $warehouseStockTransferProduct->sending_quantity}}</td>--}}
            {{--                            <td>--}}
            {{--                                <a href="javascript:void(0);" class="btn btn-sm btn-danger remove-row" data-stock_transfer_details_code="{{$warehouseStockTransferProduct->stock_transfer_details_code}}"><i class="fa fa-trash-o"></i></a>--}}
            {{--                            </td>--}}
            {{--                        </tr>--}}
            {{--                    @endforeach--}}
            {{--                @endif--}}

                    <tr>
{{--                        <input type="hidden" name="stock_transfer_details_code[]" id=""--}}
{{--                               value="">--}}
{{--                        <input type="hidden" name="warehouse_product_master_code[]" id=""--}}
{{--                               value="{{$WPMCode }}">--}}
{{--                        <td>--}}
{{--                            {{ $product->product_name }}--}}
{{--                        </td>--}}
{{--                        <td>--}}
{{--                            {{ $product->product_variant_name }}--}}
{{--                        </td>--}}
{{--                        <td class="product-row-input">--}}
{{--                            <input type="number"--}}
{{--                                   name="product_quantity[]"--}}
{{--                                   value="{{ $warehouseStockTransferProduct->sending_quantity }}"--}}
{{--                                   min="1"--}}
{{--                                   data-product_price="{{$warehouseStockTransferProduct->vendor_price}}"--}}
{{--                                   class="form-control"--}}
{{--                                   required--}}
{{--                            >--}}
{{--                        </td>--}}
{{--                        <td>{{ $warehouseStockTransferProduct->vendor_price }}</td>--}}
{{--                        <td class="product-subtotal">{{ $warehouseStockTransferProduct->vendor_price *  $warehouseStockTransferProduct->sending_quantity}}</td>--}}
{{--                        <td>--}}
{{--                            <a href="javascript:void(0);" class="btn btn-sm btn-danger remove-row"--}}
{{--                               data-stock_transfer_details_code="{{$warehouseStockTransferProduct->stock_transfer_details_code}}"><i--}}
{{--                                    class="fa fa-trash-o"></i></a>--}}
{{--                        </td>--}}
                    </tr>

            </tbody>
        </table>
    </div>

</div>


