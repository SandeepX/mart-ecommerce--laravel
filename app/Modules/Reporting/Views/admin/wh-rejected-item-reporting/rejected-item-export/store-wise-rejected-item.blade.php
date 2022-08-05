
<table>
    <thead>
    <tr>
        <td colspan="9" style="text-align: center"><strong>Warehouse Name:</strong> {{$warehouseName}}
            /<strong>Rejected Product Name:</strong> {{$productName}}
            {{$productVariantName }}  Report / (store wise)
        </td>
    </tr>
    <tr>
        <th rowspan="2"><strong>S.N</strong></th>
        <th rowspan="2"><strong>Product Name</strong></th>
        <th colspan="2" style="text-align: center"><strong>Normal Order</strong></th>
        <th colspan="2" style="text-align: center"><strong>Pre Order</strong></th>
        <th colspan="2" style="text-align: center"><strong>Total Order</strong></th>
        <th rowspan="2"><strong>Item Rejected Date</strong></th>
    </tr>
    <tr>
        <th><strong>Quantity</strong></th>
        <th><strong>Amount</strong></th>
        <th><strong>Quantity</strong></th>
        <th><strong>Amount</strong></th>
        <th><strong>Quantity</strong></th>
        <th><strong>Amount</strong></th>
    </tr>

    </thead>
    <tbody>
    @if(isset($rejectedItemStoreWise))
        @foreach($rejectedItemStoreWise as $key => $value)
            <tr>
                <td>{{++$key}}</td>
                <td>
                    {{$value->product_name}}
                    @if($value->product_variant_name)
                        ({{$value->product_variant_name}})
                    @endif
                </td>
                <td>{{isset($value->total_normal_rejected_packaging_qty) ? $value->total_normal_rejected_packaging_qty : 'N/A'}}</td>
                <td style="text-align: left">{{isset($value->total_normal_rejected_price) ? getNumberFormattedAmount($value->total_normal_rejected_price) : 'N/A'}}</td>
                <td>{{isset($value->total_preorder_rejected_packaging_qty) ? $value->total_preorder_rejected_packaging_qty : 'N/A'}}</td>
                <td style="text-align: left">{{isset($value->total_preorder_rejected_price) ? getNumberFormattedAmount($value->total_preorder_rejected_price) : 'N/A'}}</td>
                <td>{{isset($value->total_packaging_qty) ? $value->total_packaging_qty : 'N/A'}}</td>
                <td style="text-align: left">{{isset($value->total_rejected_price) ? getNumberFormattedAmount($value->total_rejected_price) : 'N/A'}}</td>
                <td>{{$value->updated_at}}</td>
            </tr>
        @endforeach
    @else
        <tr>
            <td>No records found </td>
        </tr>
    @endif

    </tbody>
</table>
