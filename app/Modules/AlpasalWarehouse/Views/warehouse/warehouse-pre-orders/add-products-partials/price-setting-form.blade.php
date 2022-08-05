<table class="table table-bordered">
    <thead>
    <tr>
        <th>Variant</th>
        <th>Mrp</th>
        <th>Admin Margin</th>
        <th>Wholesale Margin</th>
        <th>Retail Margin</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
        @if($hasVariants)
            @foreach($productVariants as $productVariant)
                <tr>
                    <td>
                        {{$product->product_name}}
                        <small>{{$productVariant->product_variant_name}}</small>

                        <div class="row">
                            <div class="col-sm-12">
                                <ul>
                                    @foreach($productVariant->packaging_info as $packagingInfo)
                                        <li>{{$packagingInfo}}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <input type="hidden"  name="product_variant_code[]" value="{{$productVariant->product_variant_code}}"/>
                        <input type="hidden"
                               name="product_code[]" value="{{$product->product_code}}"/>
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
            @endforeach
        @else
            <tr>
                <td>
                    {{$product->product_name}}


                    <div class="row">
                        <div class="col-sm-12">
                            <ul>
                                @foreach($product->packaging_info as $packagingInfo)
                                    <li>{{$packagingInfo}}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <input type="hidden" name="product_variant_code[]" value=""/>
                    <input type="hidden"
                           name="product_code[]" value="{{$product->product_code}}"/>
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


