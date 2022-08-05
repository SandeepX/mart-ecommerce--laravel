<div class="box-body">
    <table class="table table-bordered table-striped" cellspacing="0"
           width="100%">
        <thead>
        <tr>
            <th>#</th>
            <th>Product Name (Variant Name)</th>
            <th>Vendor Name</th>
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
                <td>
                    <a href="{{route('admin.wh-stock-report.warehouse-product-master.detail',[
                                                    'warehouseCode' => $filterParameters['warehouse_code'],
                                                    'warehouseProductMasterCode' => $warehouseProductStatement->warehouse_product_master_code
                                                    ])}}">
                        {{$warehouseProductStatement->warehouseProductMaster->product->product_name}}
                        @if($warehouseProductStatement->warehouseProductMaster->product_variant_code)
                            ({{$warehouseProductStatement->warehouseProductMaster->productVariant->product_variant_name}})
                        @endif
                    </a>
                </td>
                <td><small>{{$warehouseProductStatement->warehouseProductMaster->vendor->vendor_name}}</small></td>

                <td><strong>{{ ucwords(str_replace('-',' ',$warehouseProductStatement->action))}}</strong></td>
                <td>
                    @if($warehouseProductStatement->reference_code)
                        @if($warehouseProductStatement->link_data['link'])
                            <a target="_blank" href="{{$warehouseProductStatement->link_data['link']}}">
                                {{$warehouseProductStatement->reference_code}}
                            </a>
                            @if($warehouseProductStatement->link_data['value'])
                                <small>({{$warehouseProductStatement->link_data['value']}}) </small>
                            @endif
                        @else
                            {{$warehouseProductStatement->reference_code}}
                            @if($warehouseProductStatement->link_data['value'])
                                <small>({{$warehouseProductStatement->link_data['value']}}) </small>
                            @endif
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
    {{$warehouseProductStatements->appends($_GET)->links()}}
</div>
