
<table>
    <thead>
    <tr>
        <td colspan="9" style="text-align: center"><strong>Warehouse Name:</strong>{{$warehouseName}}</td>
    </tr>
    <tr>
        <th rowspan="2"><strong>S.N</strong></th>
        <th rowspan="2"><strong>Product Name</strong></th>
        <th rowspan="2"><strong>Vendor Name</strong></th>
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
    @if(isset($warehouseRejectedItemData))
        @foreach($warehouseRejectedItemData as $key => $rejectedItemRecords)
            <tr>
                <td>{{++$key}}</td>
                <td>
                    {{$rejectedItemRecords->product_name}}
                    @if($rejectedItemRecords->product_variant_name)
                        ({{$rejectedItemRecords->product_variant_name}})
                    @endif

                </td>
                <td>{{$rejectedItemRecords->vendor_name}}</td>
                <td>{{isset($rejectedItemRecords->total_normal_packaging_rejected_qty) ? $rejectedItemRecords->total_normal_packaging_rejected_qty : 'N/A'}}</td>
                <td style="text-align: left">{{isset($rejectedItemRecords->total_normal_rejected_price) ? getNumberFormattedAmount($rejectedItemRecords->total_normal_rejected_price) : 'N/A'}}</td>
                <td>{{isset($rejectedItemRecords->total_preorder_packaging_rejected_qty) ? $rejectedItemRecords->total_preorder_packaging_rejected_qty : 'N/A'}}</td>
                <td style="text-align: left">{{isset($rejectedItemRecords->total_preorder_rejected_price) ? getNumberFormattedAmount($rejectedItemRecords->total_preorder_rejected_price) : 'N/A'}}</td>
                <td>{{isset($rejectedItemRecords->total_rejected_packaging_qty) ? $rejectedItemRecords->total_rejected_packaging_qty : 'N/A'}}</td>
                <td style="text-align: left">{{isset($rejectedItemRecords->total_rejected_price) ? getNumberFormattedAmount($rejectedItemRecords->total_rejected_price) : 'N/A'}}</td>
                <td>{{$rejectedItemRecords->updated_at}}</td>
            </tr>
        @endforeach
    @else
        <tr>
            <td>No records found </td>
        </tr>
    @endif

    </tbody>
</table>
