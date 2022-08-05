<table>
    <thead>

    <tr>
        <th><b>S.N</b></th>
        <th><b>Product Name</b></th>
        {{--        @for($i=0;$i<$maxNumOfHeadingGroups;$i++)--}}
        <th><b>Packaging Stock</b></th>
        {{--        @endfor--}}
    </tr>
    </thead>
    <tbody>
    <?php $n = 1; ?>
    @foreach($vendorWiseProducts as $product)
        <tr>
            <td>{{$n++}}</td>
            <td>
                {{ $product->product_name }}
                @if (isset($product->product_variant_name))
                    <span>({{ $product->product_variant_name }})</span>
                @endif
            </td>
            <td>
                {{$product->product_packaging_detail}}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
