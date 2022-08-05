
<table>
    <thead>
    <tr>
        <td colspan="9" style="text-align: center"><strong>ALL REJECTED ITEM PRODUCT WISE EPORT</strong>
            <br><strong>Warehouse Name:</strong> {{$warehouseName}} / <strong>Store Name:</strong> {{$storeName}} /
            /<strong>Rejected Product Name:</strong> {{$productName}}
            {{$productVariantName }}  Report / (product wise)
        </td>
    </tr>
    <tr>
        <th><strong>S.N</strong></th>
        <th><strong>Order Date</strong></th>
        <th><strong>Order Type</strong></th>
        <th><strong>Order Code</strong></th>
        <th><strong>Quantity</strong></th>
        <th><strong>Unit Rate</strong></th>
        <th><strong>Order Amount</strong></th>
    </tr>
    </thead>
    <tbody>
    @if(isset($rejectedItemReportProductWise))
        @foreach($rejectedItemReportProductWise as $key => $value)
            <tr>
                <td>{{++$key}}</td>
                <td>{{getReadableDate($value->order_date)}}</td>
                <td>{{ucwords(str_replace('_',' ',$value->order_type))}}</td>
                <td>  ({{$value->order_code}})</td>
                <td>{{$value->rejected_qty}}</td>
                <td>{{getNumberFormattedAmount($value->unit_rate)}}</td>
                <td>{{getNumberFormattedAmount($value->total_amount)}}</td>
            </tr>
        @endforeach
    @else
        <tr>
            <td>No records found </td>
        </tr>
    @endif

    </tbody>
</table>
