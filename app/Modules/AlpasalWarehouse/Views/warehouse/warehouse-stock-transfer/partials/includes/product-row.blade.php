@if(isset($product))
    <tr>
        <input type="hidden" name="stock_transfer_details_code[]" id="" value="">
        <input type="hidden" name="warehouse_product_master_code[]" id="" value="{{ $WPMCode }}">
        <td>
            {{ $product->warehouseProductMaster->product->product_name }}
        </td>
        <td>
            {{ $product->warehouseProductMaster->productVariant->product_variant_name }}
        </td>
        <td>
            <select class="form-control">
                @foreach($packageDetails as $packageDetail)
                <option value="{{$packageDetail['package_name']}}" class="">{{$packageDetail['package_name']}}</option>
                @endforeach
            </select>
        </td>
        <td class="product-row-input">
            <input type="number"
                   name="product_quantity[]"
                   value="1"
                   min="1"
                   data-product_price="{{$product->vendor_price}}"
                   class="form-control"
                   required
            >
        </td>
        <td>{{ $product->vendor_price }}</td>
        <td class="product-subtotal">{{ $product->vendor_price }}</td>
        <td>
            <a href="javascript:void(0);" class="btn btn-sm btn-danger remove-row" ><i class="fa fa-trash-o"></i></a>
        </td>
    </tr>
@endif
