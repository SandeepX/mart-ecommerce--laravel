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
    @if(isset($dispatchRecords))
        @foreach($dispatchRecords as $dispatchRecord)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>
                    {{$dispatchRecord->product_name}}
                    @if($dispatchRecord->product_variant_name)
                        ({{$dispatchRecord->product_variant_name}})
                    @endif
                </td>
                <td>{{$dispatchRecord->vendor_name}}</td>
                <td>{{isset($dispatchRecord->normal_order_packaging_qty) ? $dispatchRecord->normal_order_packaging_qty : 'N/A'}}</td>
                <td style="text-align: left">{{isset($dispatchRecord->normal_order_amount) ? getNumberFormattedAmount($dispatchRecord->normal_order_amount) : 'N/A'}}</td>
                <td>{{isset($dispatchRecord->pre_order_packaging_qty) ? $dispatchRecord->pre_order_packaging_qty : 'N/A'}}</td>
                <td style="text-align: left">{{isset($dispatchRecord->pre_order_amount) ? getNumberFormattedAmount($dispatchRecord->pre_order_amount) : 'N/A'}}</td>
                <td>{{isset($dispatchRecord->total_packaging_qty) ? $dispatchRecord->total_packaging_qty : 'N/A'}}</td>
                <td style="text-align: left">{{isset($dispatchRecord->total_amount) ? getNumberFormattedAmount($dispatchRecord->total_amount) : 'N/A'}}</td>
            </tr>
        @endforeach
    @endif

    </tbody>
</table>
