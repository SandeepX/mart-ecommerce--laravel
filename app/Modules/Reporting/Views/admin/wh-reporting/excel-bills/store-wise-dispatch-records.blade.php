<table>
    <thead>
    <tr>
        <th colspan="4"><strong>Warehouse Name:</strong>{{$warehouseName}}</th>
        <th colspan="4"><strong>ProductName:</strong> {{$productName}} @if($productVariantName)( {{$productVariantName}}) @endif</th>
    </tr>
    <tr>
        <th rowspan="2"><strong>S.N</strong></th>
        <th rowspan="2"><strong>Store Name</strong></th>
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
    @if(isset($dispatchRecordsStoreWise))
        @foreach($dispatchRecordsStoreWise as $stores)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$stores->store_name}}</td>
                <td>{{isset($stores->normal_order_packaging_qty) ? $stores->normal_order_packaging_qty : 'N/A'}}</td>
                <td style="text-align: left">{{isset($stores->normal_order_amount) ? getNumberFormattedAmount($stores->normal_order_amount) : 'N/A'}}</td>
                <td style="text-align: left">{{isset($stores->pre_order_packaging_qty) ? $stores->pre_order_packaging_qty : 'N/A'}}</td>
                <td  style="text-align: left">{{isset($stores->pre_order_amount) ? getNumberFormattedAmount($stores->pre_order_amount) : 'N/A'}}</td>
                <td style="text-align: left">{{isset($stores->total_packaging_qty) ? $stores->total_packaging_qty : 'N/A'}}</td>
                <td  style="text-align: left">{{isset($stores->total_amount) ? getNumberFormattedAmount($stores->total_amount) : 'N/A'}}</td>
            </tr>
        @endforeach
    @endif

    </tbody>
</table>
