<table>
    <thead>
    <tr>
        <td colspan="3" style="text-align: center"><strong>Warehouse Name:{{$warehouseProductMaster->warehouse->warehouse_name}}</strong></td>
        <td colspan="3" style="text-align: center"><strong>Product</strong>:
            Product: <strong>{{$warehouseProductMaster->product->product_name}}</strong>
            @if($warehouseProductMaster->product_variant_code)
                <strong> ({{$warehouseProductMaster->productVariant->product_variant_name}})</strong>
            @endif
        </td>
        <td colspan="1" style="text-align: center"><strong>Current Stock:{{$warehouseProductMaster->current_stock}}</strong></td>
    </tr>
    <tr>
        <th><strong>S.N</strong></th>
        <th>Stock Action</th>
        <th>Reference</th>
        <th style="text-align: center">IN</th>
        <th style="text-align: center">OUT</th>
        <th>Current Stock</th>
        <th>Created At</th>
    </tr>
    </thead>
    <tbody>
    @foreach($warehouseProductStatements as $i => $warehouseProductStatement)
        <tr>
            <td>{{++$i}}</td>
            <td><strong>{{ ucwords(str_replace('-',' ',$warehouseProductStatement->action))}}</strong></td>
            <td>
                @if($warehouseProductStatement->reference_code)
                        {{$warehouseProductStatement->reference_code}}
                        @if($warehouseProductStatement->link_data['value'])
                            <small>({{$warehouseProductStatement->link_data['value']}}) </small>
                        @endif
                @else
                    <span class="label label-danger">Ref: N/A</span>
                @endif
            </td>
            <td class="text-center">
                @if($warehouseProductStatement->stock_changing_type == 'in')
                    @if($warehouseProductStatement->package)
                        {{$warehouseProductStatement->package}}
                    @else
                        {{ $warehouseProductStatement->quantity}}
                        <br/>
                        (Packaging: N/A)
                    @endif
                @endif
            </td>
            <td class="text-center">
                @if($warehouseProductStatement->stock_changing_type == 'out')
                    @if($warehouseProductStatement->package)
                        {{$warehouseProductStatement->package}}
                    @else
                        {{ $warehouseProductStatement->quantity}}
                        <br/>
                        (Packaging: N/A)
                    @endif
                @endif
            </td>
            <td>{{$warehouseProductStatement->current_stock}}</td>
            <td>
                {{getReadableDate(getNepTimeZoneDateTime($warehouseProductStatement->created_at))}}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
