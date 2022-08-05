<table>
    <thead>
    <tr>
        <td colspan="9" style="text-align: center"><strong>Warehouse Name:{{$warehouseName}}</strong></td>
    </tr>
    <tr>
        <th><strong>S.N</strong></th>
        <th><strong>Product Name</strong></th>
        <th><strong>Vendor Name</strong></th>
        <th><strong>Stock Action</strong></th>
        <th><strong>Reference</strong></th>
        <th><strong>In</strong></th>
        <th><strong>OUT</strong></th>
        <th><strong>Current Stock</strong></th>
        <th><strong>Created At</strong></th>
    </tr>
    </thead>
    <tbody>
    @foreach($warehouseProductStatements as $i => $warehouseProductStatement)
        <tr>
            <td>{{++$i}}</td>
            <td>
                    {{$warehouseProductStatement->warehouseProductMaster->product->product_name}}
                    @if($warehouseProductStatement->warehouseProductMaster->product_variant_code)
                        ({{$warehouseProductStatement->warehouseProductMaster->productVariant->product_variant_name}})
                    @endif
            </td>
            <td><small>{{$warehouseProductStatement->warehouseProductMaster->vendor->vendor_name}}</small></td>

            <td>{{ ucwords(str_replace('-',' ',$warehouseProductStatement->action))}}</td>
            <td>
                @if($warehouseProductStatement->reference_code)
                        {{$warehouseProductStatement->reference_code}}
                        @if($warehouseProductStatement->link_data['value'])
                            <small>({{$warehouseProductStatement->link_data['value']}}) </small>
                        @endif
                @else
                    Ref: N/A
                @endif
            </td>
            <td style="text-align:center">
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
            <td style="text-align:center">
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
