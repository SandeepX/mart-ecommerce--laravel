<table>
    <thead>

        <tr>
            <th><b>S.N</b></th>
            <th><b>Product Name</b></th>
            <th><b>Packaging</b></th>
            <th><b>Quantity</b></th>
            <th><b>Unit Rate</b></th>
            <th><b>Product Price</b></th>
        </tr>
    </thead>
    <tbody>
        <?php $n = 1; ?>
        @foreach($storePreOrderProducts as $product)
            <tr>
                <td>{{$n++}}</td>
                <td>
                    {{ $product->product_name }}
                    @if (isset($product->product_variant_name))
                        <span>({{ $product->product_variant_name }})</span>
                    @endif
                </td>
                <td>
                    {{$product->ordered_package_name}}
                </td>
                <td>{{ $product->total_ordered_quantity }}</td>
                <td>{{ $product->vendor_price }}</td>
                <td>{{ $product->sub_total }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
