<table>
    <thead>
    <tr>
        <th colspan="13" style="text-align: center"><strong>Warehouse Name: {{$warehouseName}}</strong></th>
    </tr>
    <tr>
        <th>#</th>
        <th><strong>Product Name</strong></th>
        <th><strong>Variant Name</strong></th>
        <th><strong>Purchase Qty</strong></th>
        <th><strong>Received Qty</strong></th>
        <th><strong>Dispatched Qty(normal)</strong></th>
        <th><strong>Dispacthed Qty(preorder)</strong></th>
        <th><strong>Stock Transfer Qty</strong></th>
        <th><strong>Normal Order Demand Qty</strong></th>
        <th><strong>Preorder Demand Qty </strong></th>
        <th><strong>Actual Stock</strong></th>
        <th><strong>Demand Stock</strong></th>
        <th><strong>Demand Projection</strong></th>
    </tr>
    </thead>
    <tbody>
    @forelse($demandProjection as $i => $datum)
        <tr>
            <td>{{++$i}}</td>
            <td>{{$datum->product_name}}({{$datum->product_code}})</td>
            <td>{{($datum->product_variant_name)? $datum->product_variant_name:"N/A"}} </td>
            <td>{{($datum->total_purchase_qty)? $datum->total_purchase_qty:0}}</td>
            <td>{{($datum->total_received_qty)? $datum->total_received_qty:0}}</td>
            <td>{{($datum->normal_order_dispacthed_qty)? $datum->normal_order_dispacthed_qty:0}}</td>
            <td>{{($datum->pre_order_dispatched_qty)? $datum->pre_order_dispatched_qty:0}}</td>
            <td>{{($datum->total_stock_transfer_qty)? $datum->total_stock_transfer_qty:0}}</td>
            <td>{{($datum->normal_order_demand_qty)? $datum->normal_order_demand_qty:0}}</td>
            <td>{{($datum->demand_preorder_qty)? $datum->demand_preorder_qty:0}}</td>
            <td>{{($datum->actual_stock)? $datum->actual_stock:0}}</td>
            <td>{{($datum->demand_stock)? $datum->demand_stock:0}}</td>
            <td class="{{(($datum->demand_projection) && $datum->demand_projection < 0) ? 'unavaiable-stock':''}}" >{{($datum->demand_projection)? $datum->demand_projection:0}}</td>
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
