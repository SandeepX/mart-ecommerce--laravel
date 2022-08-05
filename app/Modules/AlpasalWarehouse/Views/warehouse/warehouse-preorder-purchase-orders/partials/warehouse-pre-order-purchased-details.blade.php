@if($hasOrderBeenPlaced)
    <div class="box-header with-border">
    <h3 class="box-title">Purchased Products In This Order</h3>
    <br/>
    <div class="col-md-6 col-sm-12" style="float: left">
        <strong> Order Date :</strong>  {{$warehousePurchaseOrdersDetails->order_date}} <br/>
        <strong> Warehouse Name :</strong> {{$warehousePurchaseOrdersDetails->warehouse->warehouse_name}} <br/>
        <strong> Order Status :</strong> {{$warehousePurchaseOrdersDetails->status}}<br/>
    </div>
    <div class="col-md-6 col-sm-12" style="float: right">
        <strong> Warehouse Order Code :</strong>  {{$warehousePurchaseOrdersDetails->warehouse_order_code}}<br/>
        <strong> Vendor :</strong>  {{$warehousePurchaseOrdersDetails->vendor->vendor_name}}<br/>

    </div>
</div>
<div class="box-body">
    <table id="product-order-list-tbl"
           class="table table-striped table-responsive">
        <thead class="bg-primary">
        <tr>
            <th>S.N</th>
            <th>Product</th>
            <th>Variant</th>
            <th>Quantity</th>
            <th>Unit Rate</th>
        </tr>
        </thead>
        <tbody>
        @forelse($warehousePurchaseOrdersProductsDetails as $i => $warehousePurchaseOrdersDetail)
            <tr>
                <td>{{++$i}}</td>
                <td>{{$warehousePurchaseOrdersDetail->product_name}}</td>
                <td>{{$warehousePurchaseOrdersDetail->variant_name}}</td>
                <td>{{$warehousePurchaseOrdersDetail->quantity}}</td>
                <td>{{ getNumberFormattedAmount($warehousePurchaseOrdersDetail->unit_rate)}}</td>
            </tr>
        @empty
            <tr>
                <td colspan="100%">No Records found</td>
            </tr>
        @endforelse

        </tbody>
    </table>
</div>
@endif
