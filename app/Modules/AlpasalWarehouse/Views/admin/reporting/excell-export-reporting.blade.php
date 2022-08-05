<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Pre Order Name</th>
        <th>Store Pre Order Code</th>
        <th>Store Name</th>
        <th>Store Phone</th>
        <th>Store Mobile</th>
        <th>Store Location</th>
        <th>Warehouse Name</th>
        <th>Amount</th>
        <th>Current Balance</th>
    </tr>
    </thead>
    <tbody>
    @foreach($warehousePreOrders as $i => $warehousePreOrder)
        <tr>
            <td>{{++$i}}</td>
            <td>{{$warehousePreOrder->pre_order_name}}</td>
            <td>{{$warehousePreOrder->store_preorder_code}}</td>
            <td>{{$warehousePreOrder->store_name}}
            </td>
            <td>{{$warehousePreOrder->phone}}</td>
            <td>{{$warehousePreOrder->mobile}}</td>
            <td>{{$warehousePreOrder->store_full_location}}</td>
            <td>{{$warehousePreOrder->warehouse_name}}</td>
            <td>Rs. {{$warehousePreOrder->amount}}</td>
            <td>Rs. {{$warehousePreOrder->current_balance}}</td>

        </tr>
    @endforeach
    </tbody>
</table>
