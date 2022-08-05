<table>
    <thead>
    <tr>
        <th colspan="2"><strong>Warehouse Name: </strong>{{$warehouseName}} </th>
        <th colspan="2"><strong>Store Name:  </strong>{{$storeName}}</th>
        <th colspan="3"><strong>Product Name: </strong>{{$productName}} @if($productVariantName)( {{$productVariantName}}) @endif </th>
    </tr>
    <tr>
        <th><strong>S.N</strong></th>
        <th style="text-align: center"><strong>Order Date</strong></th>
        <th style="text-align: center"><strong>Order Type</strong></th>
        <th style="text-align: center"><strong>Order Code</strong></th>
        <th><strong>Quantity</strong></th>
        <th style="text-align: center"><strong>Unit Rate</strong></th>
        <th style="text-align: center"><strong>Order Amount</strong></th>
    </tr>
    </thead>
    <tbody>
    @if(isset($dispatchStatementProductWise))
        @foreach($dispatchStatementProductWise as $product)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td style="text-align: left">{{getReadableDate(getNepTimeZoneDateTime($product->order_date))}}</td>
                <td style="text-align: left">{{ucwords(str_replace('_',' ',$product->order_type))}}</td>
                <td style="text-align: left">{{$product->order_code}}</td>
                <td>{{$product->package_quantity}}</td>
                <td style="text-align: left">{{getNumberFormattedAmount($product->unit_rate)}}</td>
                <td  style="text-align: left">{{getNumberFormattedAmount($product->order_amount)}}</td>
            </tr>
        @endforeach
    @endif

    </tbody>
</table>
