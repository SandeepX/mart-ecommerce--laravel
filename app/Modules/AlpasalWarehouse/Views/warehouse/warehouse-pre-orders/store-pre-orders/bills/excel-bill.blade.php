<table>
    <thead>
    <tr>
        <th><strong>S.N</strong></th>
        <th><strong>Pre-Order Code</strong></th>
        <th><strong>Store</strong></th>
        <th><strong>Product</strong></th>
        <th><strong>Product Variant</strong></th>
        <th><strong>Unit</strong></th>
        <th><strong>Quantity</strong></th>
        <th><strong>Rate</strong></th>
        <th><strong>Tax Included</strong></th>
        <th><strong>Sub Total</strong></th>
    </tr>
    </thead>
    <tbody>
    @if(isset($storePreOrderProducts))
        @foreach($storePreOrderProducts as $storePreOrderProduct)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$storePreOrderProduct->store_preorder_code}}</td>
                <td>{{$storePreOrderProduct->store_name}}</td>
                <td>{{$storePreOrderProduct['product_name']}}</td>
                <td>{{$storePreOrderProduct['product_variant_name']}}</td>
                <td>
                    {{$storePreOrderProduct['ordered_package_name'] ?? $storePreOrderProduct['package_name']}}
                    (B.L : {{$storePreOrderProduct['package_order']}})
                </td>
                <td>{{$storePreOrderProduct['quantity']}}</td>
                <td>{{roundPrice($storePreOrderProduct['tax_unit_rate'])}}</td>
                <td>{{$storePreOrderProduct['tax_percent']}}</td>
                <td>{{roundPrice($storePreOrderProduct['tax_sub_total'])}}</td>
            </tr>
        @endforeach
        <tr>
            <td></td>
            <td colspan="7">
                Total Price
            </td>
            <td >{{roundPrice($total_order_price)}}</td>
        </tr>
    @endif

    </tbody>
</table>
