
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">
        Price Info of {{$warehouseProductDetail['product']['product_name']}}
        {{$warehouseProductDetail['productVariant'] ? $warehouseProductDetail['productVariant']['product_variant_name'] : ''}}
    </h4>
</div>
<div class="modal-body">
    {{-- @include('AlpasalWarehouse::warehouse.warehouse-products.show-partials.price-history-table')--}}
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>S.N</th>
            @if($warehouseProductDetail->has_product_variants)
                <th>Variant</th>
            @else
                <th>Product</th>
            @endif
            <th>Price Setting</th>
        </tr>
        </thead>
        <tbody>

        @if($warehouseProductDetail->has_product_variants)
            @foreach($warehouseProductDetail->product_variants as $productVariant)

                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>
                        {{$warehouseProductDetail->getProductProperty('product_name')}}
                        <br>
                        <small>{{$productVariant['product_variant_name']}}</small>

                        <div class="row">
                            <div class="col-sm-12">
                                @if($productVariant->packaging_info)
                                    <ul>
                                        @foreach($productVariant->packaging_info as $packagingInfo)
                                            <li>{{$packagingInfo}}</li>
                                        @endforeach

                                    </ul>
                                @else
                                    <strong style="color:red">No Packing Details Found !</strong>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($productVariant->warehouseProductPriceMaster)
                            <div>MRP: {{$productVariant->warehouseProductPriceMaster['mrp']}}</div>
                            <div>Admin Margin({{$productVariant->warehouseProductPriceMaster['admin_margin_type']}}): {{$productVariant->warehouseProductPriceMaster['admin_margin_value']}}</div>
                            <div>Wholesale Margin({{$productVariant->warehouseProductPriceMaster['wholesale_margin_type']}}): {{$productVariant->warehouseProductPriceMaster['wholesale_margin_value']}}</div>
                            <div>Retail Margin({{$productVariant->warehouseProductPriceMaster['retail_margin_type']}}): {{$productVariant->warehouseProductPriceMaster['retail_margin_value']}}</div>
                        @else
                            <p>N/A</p>
                        @endif

                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td>1</td>
                <td>
                    {{$warehouseProductDetail->getProductProperty('product_name')}}

                    <div class="row">
                        <div class="col-sm-12">
                            @if($warehouseProductDetail->packaging_info)
                                <ul>
                                    @foreach($warehouseProductDetail->packaging_info as $packagingInfo)
                                        <li>{{$packagingInfo}}</li>
                                    @endforeach

                                </ul>
                            @else
                                <strong style="color:red">No Packing Details Found !</strong>
                            @endif
                        </div>
                    </div>
                </td>
                <td>
                    @if($warehouseProductDetail->warehouseProductPriceMaster)
                        <div>MRP: {{$warehouseProductDetail->warehouseProductPriceMaster['mrp']}}</div>
                        <div>Admin Margin({{$warehouseProductDetail->warehouseProductPriceMaster['admin_margin_type']}}): {{$warehouseProductDetail->warehouseProductPriceMaster['admin_margin_value']}}</div>
                        <div>Wholesale Margin({{$warehouseProductDetail->warehouseProductPriceMaster['wholesale_margin_type']}}): {{$warehouseProductDetail->warehouseProductPriceMaster['wholesale_margin_value']}}</div>
                        <div>Retail Margin({{$warehouseProductDetail->warehouseProductPriceMaster['retail_margin_type']}}): {{$warehouseProductDetail->warehouseProductPriceMaster['retail_margin_value']}}</div>
                    @else
                        <p>N/A</p>
                    @endif
                </td>
            </tr>

        @endif

        </tbody>
    </table>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    {{-- <button type="button" class="btn btn-primary">Save changes</button>--}}
</div>

