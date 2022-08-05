<div class=" box-body" >
    <table class="table table-bordered table-striped" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th rowspan="2">#</th>
            <th rowspan="2">Name</th>
            <th colspan="2" class="text-center">Normal Order</th>
            <th colspan="2" class="text-center">Pre Order</th>
            <th colspan="2" class="text-center">Total Rejected</th>
            <th rowspan="2">Action</th>
        </tr>

        <tr>
            <th>Quantity</th>
            <th>Amount</th>
            <th>Quantity</th>
            <th>Amount</th>
            <th>Quantity</th>
            <th>Amount</th>
        </tr>

        </thead>
        <tbody>
        @forelse($warehouseRejectedItemData as $i => $datum)
            <tr>
                <td>{{++$i}}</td>
                <td>
                    {{$datum->product_name}}
                    {{($datum->product_variant_name) ? '('.$datum->product_variant_name.')':''}} <br>
                    <strong>Vendor:</strong>  {{$datum->vendor_name}}
                </td>
                <td>
                                            <span class="badge bg-secondary">
                                               {{($datum->total_normal_packaging_rejected_qty)? $datum->total_normal_packaging_rejected_qty:0}}
                                            </span>
                </td>
                <td>Rs.{{($datum->total_normal_rejected_price)? $datum->total_normal_rejected_price:0}}</td>
                <td>
                                            <span class="badge bg-secondary">
                                                {{($datum->total_preorder_packaging_rejected_qty)? $datum->total_preorder_packaging_rejected_qty:0}}
                                            </span>
                </td>
                <td>Rs.{{($datum->total_preorder_rejected_price)? $datum->total_preorder_rejected_price:0}}</td>

                <td>
                                            <span class="badge bg-secondary">
                                               {{($datum->total_rejected_packaging_qty)? $datum->total_rejected_packaging_qty:0}}
                                            </span>
                </td>
                <td>Rs.{{($datum->total_rejected_price)? $datum->total_rejected_price:0}}</td>

                <td>
                    <a href="{{route('admin.rejected-item-report.product.stores-lists',
                                            [
                                                'warehouseCode'=>$filterParameters['warehouse_code'],
                                                'productCode'=>$datum->product_code,
                                                'product_variant_code'=> $datum->product_variant_code
                                             ])
                                            }}" class="btn btn-info btn-xs">
                        <i class="fa fa-eye"></i> View Stores
                    </a>
                </td>

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

    {{$warehouseRejectedItemData->appends($_GET)->links()}}
</div>
