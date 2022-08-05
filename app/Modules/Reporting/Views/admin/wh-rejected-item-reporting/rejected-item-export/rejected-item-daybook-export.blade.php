
<table>
    <thead>
    <tr>
        <td colspan="9" style="text-align: center"><strong>ALL REJECTED ITEM DAYBOOK REPORT</strong></td>
    </tr>
    <tr>
        <th><strong>S.N</strong></th>
        <th><strong>Product Name</strong></th>
        <th><strong>Vendor Name</strong></th>
        <th><strong>Store Name</strong></th>
        <th><strong>Order Date</strong></th>
        <th><strong>Order Type</strong></th>
        <th><strong>Quantity</strong></th>
        <th><strong>Unit Rate</strong></th>
        <th><strong>Order Amount</strong></th>
    </tr>


    </thead>
    <tbody>
    @if(isset($rejectedItemStatement))
        @foreach($rejectedItemStatement as $key => $rejectedItemDaybook)
            <tr>
                <td>{{++$key}}</td>
                <td>
                    {{$rejectedItemDaybook->product_name}}
                    @if($rejectedItemDaybook->product_variant_name)
                        ({{$rejectedItemDaybook->product_variant_name}})
                    @endif

                </td>
                <td>{{$rejectedItemDaybook->vendor_name}}</td>
                <td>{{$rejectedItemDaybook->store_name}}  ({{$rejectedItemDaybook->store_code}})</td>
                <td>{{getReadableDate($rejectedItemDaybook->order_date)}}</td>
                <td>{{ucwords(str_replace('_',' ',$rejectedItemDaybook->order_type))}}<br/>
                        ({{$rejectedItemDaybook->order_code}})
                </td>
                <td>{{$rejectedItemDaybook->rejected_qty}}</td>
                <td>{{getNumberFormattedAmount($rejectedItemDaybook->unit_rate)}}</td>
                <td>{{getNumberFormattedAmount($rejectedItemDaybook->total_amount)}}</td>
            </tr>
        @endforeach
    @else
        <tr>
            <td>No records found </td>
        </tr>
    @endif

    </tbody>
</table>
