<div class="box-body">
    <table class="table table-bordered table-striped" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th rowspan="2">#</th>
            <th rowspan="2">Name</th>
            <th colspan="2" class="text-center">Normal Order</th>
            <th colspan="2" class="text-center">Pre Order</th>
            <th colspan="2" class="text-center">Total Order</th>
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
        @forelse($orderProducts as $key =>$product)
            <tr>
                <td>{{$loop->index + 1}}</td>
                <td>
                  <strong>Product Name: </strong>{{$product->product_name}}
                    @if($product->product_variant_name)
                        ({{$product->product_variant_name}})
                    @endif
                    <br/>
                    <strong>Vendor Name:</strong> {{$product->vendor_name}}<br/>
                </td>
                <td><span class="label label-success">{{isset($product->normal_order_packaging_qty) ? $product->normal_order_packaging_qty : 'N/A'}}</span></td>
                <td>{{isset($product->normal_order_amount) ? getNumberFormattedAmount($product->normal_order_amount) : 'N/A'}}</td>
                <td><span class="label label-info">{{isset($product->pre_order_packaging_qty) ? $product->pre_order_packaging_qty : 'N/A'}}</span></td>
                <td>{{isset($product->pre_order_amount) ? getNumberFormattedAmount($product->pre_order_amount) : 'N/A'}}</td>
                <td><span class="label label-primary">{{isset($product->total_packaging_qty) ? $product->total_packaging_qty : 'N/A'}}</span></td>
                <td>{{isset($product->total_amount) ? getNumberFormattedAmount($product->total_amount) : 'N/A'}}</td>
                <td>
                    <a target="_blank" href="{{route('admin.wh-dispatch-report.product.stores-lists',
                                [
                                    'warehouseCode'=>$filterParameters['warehouse_code'],
                                    'productCode'=>$product->product_code,
                                    'product_variant_code'=>$product->product_variant_code
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
    {{$orderProducts->appends($_GET)->links()}}
</div>
