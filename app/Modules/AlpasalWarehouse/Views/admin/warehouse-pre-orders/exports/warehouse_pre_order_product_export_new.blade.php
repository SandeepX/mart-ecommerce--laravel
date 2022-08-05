<table>
    <thead>

    <tr>
        <th><b>S.N</b></th>
        <th><b>Product Name</b></th>
        @for($i=0;$i<$maxNumOfHeadingGroups;$i++)
            <th><b>Packaging</b></th>
            <th><b>Quantity</b></th>
            <th><b>Unit Rate</b></th>
            <th><b>Product Price</b></th>
        @endfor
    </tr>
    </thead>
    <tbody>
    <?php $n = 1; ?>
    {{--{{dd($storePreOrderProducts[0]->product_packagings_price)}}--}}
    @foreach($storePreOrderProducts as $product)
        <tr>
            <td>{{$n++}}</td>
            <td>
                {{ $product->product_name }}
                @if (isset($product->product_variant_name))
                    <span>({{ $product->product_variant_name }})</span>
                @endif
            </td>
            @foreach($product->product_packagings_price as $productPackaging)
                <td>
                    {{$productPackaging['package_name']}}
                </td>
                <td>{{ $productPackaging['package_quantity'] }}</td>
                <td>{{ $productPackaging['package_unit_rate'] }}</td>
                <td>{{ $productPackaging['product_package_price'] }}</td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>
