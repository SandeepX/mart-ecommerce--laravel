<table>
    <thead>
    <tr>
        <td colspan="9" style="text-align: center"><strong>Warehouse Name:</strong>{{$warehouseName}}</td>
    </tr>
    <tr>
        <th><strong>S.N</strong></th>
        <th><strong>Product Name</strong></th>
        <th><strong>Vendor Name</strong></th>
        <th style="text-align: center"><strong>Store Name</strong></th>
        <th style="text-align: center"><strong>Order Date</strong></th>
        <th style="text-align: center"><strong>Order Type</strong></th>
        <th><strong>Quantity</strong></th>
        <th style="text-align: center"><strong>Unit Rate</strong></th>
        <th style="text-align: center"><strong>Order Amount</strong></th>
    </tr>
    </thead>
    <tbody>
    @if(isset($dispatchStatements))
        @foreach($dispatchStatements as $dispatchStatement)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>
                    {{$dispatchStatement->product_name}}
                    @if($dispatchStatement->product_variant_name)
                        ({{$dispatchStatement->product_variant_name}})
                    @endif
                </td>
                <td>{{$dispatchStatement->vendor_name}}</td>
                <td>{{$dispatchStatement->store_name}}  ({{$dispatchStatement->store_code}})</td>
                <td style="text-align: left">{{getReadableDate(getNepTimeZoneDateTime($dispatchStatement->order_date))}}</td>
                <td style="text-align: left">{{ucwords(str_replace('_',' ',$dispatchStatement->order_type))}} ({{$dispatchStatement->order_code}})</td>
                <td>{{$dispatchStatement->package_quantity}}</td>
                <td style="text-align: left">{{getNumberFormattedAmount($dispatchStatement->unit_rate)}}</td>
                <td  style="text-align: left">{{getNumberFormattedAmount($dispatchStatement->order_amount)}}</td>
            </tr>
        @endforeach
    @endif

    </tbody>
</table>
