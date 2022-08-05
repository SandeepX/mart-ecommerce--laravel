<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Order Code</th>
        <th>Total Price</th>
        <th>Acceptable Price</th>
        <th>Store Name</th>
        <th>Store Phone</th>
        <th>Store Mobile</th>
        <th>Store Location</th>
        <th>Warehouse Name</th>
        <th>Delivery Status</th>
        <th>Order Date</th>
    </tr>
    </thead>
    <tbody>
    @forelse($storeOrders as $i => $storeOrder)
        <tr>
            <td>{{++$i}}</td>
            <td>{{$storeOrder->store_order_code}}</td>
            <td>{{getNumberFormattedAmount($storeOrder->total_price)}}</td>
            <td>{{$storeOrder->acceptable_amount ? getNumberFormattedAmount($storeOrder->acceptable_amount): 'N/A' }}</td>
            <td>{{$storeOrder->store->store_name}}
            </td>
            <td>{{$storeOrder->store->store_contact_phone}}</td>
            <td>{{$storeOrder->store->store_contact_mobile}}</td>
            <td>{{$storeOrder->store->store_full_location}}</td>
            <td>{{ $storeOrder->warehouse->warehouse_name }}</td>
            <td>{{$storeOrder->delivery_status}}</td>
            <td>{{ getReadableDate(getNepTimeZoneDateTime($storeOrder->created_at)) }}</td>

        </tr>
    @empty
        <tr>
            <td colspan="100%">
                <p class="text-center"><b>No records found!</b></p>
            </td>
        </tr>
    @endforelse
    </tbody>
</table>
