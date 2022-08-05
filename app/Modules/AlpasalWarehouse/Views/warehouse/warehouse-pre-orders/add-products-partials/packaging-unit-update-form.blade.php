<form method="post" class="package-update-form"
      action="{{route('warehouse.warehouse-pre-orders.update-packaging',[
    'warehousePreOrderCode'=>$warehousePreOrder->warehouse_preorder_listing_code,
    'productCode'=>$product->product_code
    ]
 )}}">
    @csrf
    <div class="modal-body">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Product</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>

            @foreach($warehousePreOrderProducts as $key=>$warehousePreOrderProduct)
                <tr>
                    <td>
                        <input type="hidden"  name="product_variant_code[]" value="{{$warehousePreOrderProduct->product_variant_code}}"/>
                        <input type="hidden"
                               name="product_code" value="{{$warehousePreOrderProduct->product_code}}"/>
                        <h5> {{$warehousePreOrderProduct->product_name}}</h5>
                        <small>{{$warehousePreOrderProduct->product_variant_name}}</small>
                    </td>
                    <td>
                        @if($warehousePreOrderProduct->micro_unit_code)
                            <div class="col-sm-2">
                                <label for="micro_unit_code">
                                    Micro
                                    <br>
                                    <small>{{$warehousePreOrderProduct->micro_package_name}}</small>
                                </label>
                                <input type="hidden" name="micro_unit_code[{{$key}}]" value="0"/>
                                <input type="checkbox" name="micro_unit_code[{{$key}}]"
                                       {{in_array('micro',$warehousePreOrderProduct->disabled_packages) ? 'checked':''}} value="1"/>
                            </div>
                        @else
                            <input type="hidden" name="micro_unit_code[{{$key}}]" value="0"/>
                        @endif

                        @if($warehousePreOrderProduct->unit_code)
                            <div class="col-sm-2">
                                <label for="unit_code">
                                    Unit
                                    <br>
                                    <small>{{$warehousePreOrderProduct->unit_package_name}}</small>
                                </label>
                                <input type="hidden" name="unit_code[{{$key}}]" value="0"/>
                                <input type="checkbox"  name="unit_code[{{$key}}]"
                                       {{in_array('unit',$warehousePreOrderProduct->disabled_packages) ? 'checked':''}} value="1"/>
                            </div>
                            @else
                                <input type="hidden" name="unit_code[{{$key}}]" value="0"/>
                        @endif

                        @if($warehousePreOrderProduct->macro_unit_code)
                            <div class="col-sm-2">
                                <label for="macro_unit_code">
                                    Macro
                                    <br>
                                    <small>{{$warehousePreOrderProduct->macro_package_name}}</small>
                                </label>
                                <input type="hidden" name="macro_unit_code[{{$key}}]" value="0"/>
                                <input type="checkbox"  name="macro_unit_code[{{$key}}]"
                                       {{in_array('macro',$warehousePreOrderProduct->disabled_packages) ? 'checked':''}} value="1"/>
                            </div>
                            @else
                                <input type="hidden" name="macro_unit_code[{{$key}}]" value="0"/>
                        @endif

                        @if($warehousePreOrderProduct->super_unit_code)
                            <div class="col-sm-2">
                                <label for="super_unit_code">
                                    Super
                                    <br>
                                    <small>{{$warehousePreOrderProduct->super_package_name}}</small>
                                </label>
                                <input type="hidden" name="super_unit_code[{{$key}}]" value="0"/>
                                <input type="checkbox"  name="super_unit_code[{{$key}}]"
                                       {{in_array('super',$warehousePreOrderProduct->disabled_packages) ? 'checked':''}} value="1"/>
                            </div>
                            @else
                                <input type="hidden" name="super_unit_code[{{$key}}]" value="0"/>
                        @endif
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button id="saveMassPriceSetting" type="submit" class="btn btn-primary">Save changes</button>
    </div>
</form>
