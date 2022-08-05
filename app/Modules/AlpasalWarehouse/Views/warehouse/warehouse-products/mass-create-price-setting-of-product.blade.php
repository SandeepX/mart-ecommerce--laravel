<style>
    .form-group{
        margin-bottom: 6px !important;
    }
    .alert{
        padding: 5px !important;
    }
    .swal-wide{
        width:300px !important;
        height:200px !important;
    }
</style>

<div class="modal-header">
    <h4 class="modal-title" id="exampleModalLabel"><strong>Product Name: {{$warehouseProductDetail->product->product_name}}</strong> </h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div id="showMassPriceError"></div>
<form method="post" id="formMassPriceSettingOfProduct" action="{{route('warehouse.warehouse-products.store.mass-price-setting',$warehouseProductDetail->product->product_code)}}" enctype="multipart/form-data">
    @csrf
    <div class="modal-body">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Variant</th>
                <th>Mrp</th>
                <th>Admin Margin</th>
                <th>Wholesale Margin</th>
                <th>Retail Margin</th>
            </tr>
            </thead>
            <tbody>
            @if($warehouseProductDetail['has_product_variants'])
                @foreach($warehouseProductDetail['product_variants'] as $productVariant)
                    <tr>
                        <td>
                            {{$warehouseProductDetail->product->product_name}}
                            <small>{{$productVariant->product_variant_name}}</small>

                            <div class="row">
                                <div class="col-sm-12">
                                    @if($productVariant->packaging_info)
                                        <ul>
                                            @foreach($productVariant->packaging_info as $packagingInfo)
                                                <li>{{$packagingInfo}}</li>
                                            @endforeach

                                        </ul>
                                    @else
                                        <strong style="color:red">No Packing Details Found !</strong>
                                    @endif
                                </div>
                            </div>
                            <input type="hidden"  name="product_variant_code[]" value="{{$productVariant->product_variant_code}}"/>
                            <input type="hidden"
                                   name="product_code[]" value="{{$warehouseProductDetail->product->product_code}}"/>
                        </td>
                        <td>
                            <input type="number" min="1" name="mrp[]"/>
                        </td>
                        <td>
                            <select name="admin_margin_type[]">
                                <option value="p">%</option>
                                <option value="f">F</option>
                            </select>
                            <input type="text" aria-label="..." name="admin_margin_value[]">
                        </td>
                        <td>
                            <select name="wholesale_margin_type[]">
                                <option value="p">%</option>
                                <option value="f">F</option>
                            </select>

                            <input type="number" min="0" step=".01" name="wholesale_margin_value[]"/>
                        </td>
                        <td>
                            <select name="retail_margin_type[]">
                                <option value="p">%</option>
                                <option value="f">F</option>
                            </select>
                            <input type="number" min="0" step=".01" name="retail_margin_value[]"/>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td>
                        {{$warehouseProductDetail->product->product_name}}

                        <div class="row">
                            <div class="col-sm-12">
                                @if($warehouseProductDetail->packaging_info)
                                <ul>
                                    @foreach($warehouseProductDetail->packaging_info as $packagingInfo)
                                        <li>{{$packagingInfo}}</li>
                                    @endforeach

                                </ul>
                                @else
                                    <strong style="color:red">No Packing Details Found !</strong>
                                @endif
                            </div>
                        </div>
                        <input type="hidden" name="product_variant_code[]" value=""/>
                        <input type="hidden"
                               name="product_code[]" value="{{$warehouseProductDetail->product->product_code}}"/>
                    </td>
                    <td>
                        <input id="mrp" type="number" min="1" name="mrp[]"/>
                    </td>
                    <td>
                        <select name="admin_margin_type[]">
                            <option value="p">%</option>
                            <option value="f">F</option>
                        </select>
                        <input type="text" aria-label="..." name="admin_margin_value[]">
                    </td>
                    <td style="width: 225px;">

                        <select name="wholesale_margin_type[]">
                            <option value="p">%</option>
                            <option value="f">F</option>
                        </select>

                        <input type="number" min="0" step=".01" name="wholesale_margin_value[]"/>
                    </td>
                    <td style="width: 225px;">
                        <select name="retail_margin_type[]">
                            <option value="p">%</option>
                            <option value="f">F</option>
                        </select>

                        <input type="number" min="0" step=".01" name="retail_margin_value[]"/>
                    </td>
                </tr>

            @endif
            </tbody>
        </table>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button id="saveMassPriceSetting" type="submit" class="btn btn-primary">Save changes</button>
    </div>
</form>

@include('AlpasalWarehouse::warehouse.warehouse-products.show-partials.mass-price-setting-scripts')




