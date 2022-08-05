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
    <h4 class="modal-title" id="exampleModalLabel"><strong>Product Name: {{$warehouseProductDetail[0]->product_name}}</strong> </h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div id="showFlashMessagePackageUpdateModal"></div>
<form method="post" id="formMassPriceSettingOfProduct" action="{{route('warehouse.warehouse-products.update.mass-packaging-disable-list',$warehouseProductDetail[0]->product_code)}}"
      class="package-update-form">
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
                @foreach($warehouseProductDetail as $key=>$warehouseProduct)
                    <tr>
                        <td>
                            {{$warehouseProduct->product_name}}
                            <small>{{$warehouseProduct->product_variant_name}}</small>
                            <input type="hidden"  name="product_variant_code[]" value="{{$warehouseProduct->product_variant_code}}"/>
                            <input type="hidden"
                                   name="product_code[]" value="{{$warehouseProduct->product_code}}"/>
                        </td>
                        <td>
                            <div class="row">
                                @if($warehouseProduct->micro_unit_code)
                                    <div class="col-sm-2">
                                        <label for="micro_unit_code">
                                            Micro
                                            <br>
                                            <small>{{$warehouseProduct->micro_unit_name}}</small>
                                        </label>
                                        <input type="hidden" name="micro_unit_code[{{$key}}]" value="0"/>
                                        <input type="checkbox" name="micro_unit_code[{{$key}}]"
                                               {{isset($warehouseProduct->disabled_packages) && in_array('micro',$warehouseProduct->disabled_packages) ? 'checked':''}} value="1"/>
                                    </div>
                                @else
                                    <input type="hidden" name="micro_unit_code[{{$key}}]" value="0"/>
                                @endif

                                @if($warehouseProduct->unit_code)
                                    <div class="col-sm-2">
                                        <label for="unit_code">
                                            Unit
                                            <br>
                                            <small>{{$warehouseProduct->unit_name}}</small>
                                        </label>
                                        <input type="hidden" name="unit_code[{{$key}}]" value="0"/>
                                        <input type="checkbox"  name="unit_code[{{$key}}]"
                                               {{isset($warehouseProduct->disabled_packages) && in_array('unit',$warehouseProduct->disabled_packages) ? 'checked':''}} value="1"/>
                                    </div>
                                @else
                                    <input type="hidden" name="unit_code[{{$key}}]" value="0"/>
                                @endif

                                @if($warehouseProduct->macro_unit_code)
                                    <div class="col-sm-2">
                                        <label for="macro_unit_code">
                                            Macro
                                            <br>
                                            <small>{{$warehouseProduct->macro_unit_name}}</small>
                                        </label>
                                        <input type="hidden" name="macro_unit_code[{{$key}}]" value="0"/>
                                        <input type="checkbox"  name="macro_unit_code[{{$key}}]"
                                               {{isset($warehouseProduct->disabled_packages) && in_array('macro',$warehouseProduct->disabled_packages) ? 'checked':''}} value="1"/>
                                    </div>
                                @else
                                    <input type="hidden" name="macro_unit_code[{{$key}}]" value="0"/>
                                @endif

                                @if($warehouseProduct->super_unit_code)
                                    <div class="col-sm-2">
                                        <label for="super_unit_code">
                                            Super
                                            <br>
                                            <small>{{$warehouseProduct->super_unit_name}}</small>
                                        </label>
                                        <input type="hidden" name="super_unit_code[{{$key}}]" value="0"/>
                                        <input type="checkbox"  name="super_unit_code[{{$key}}]"
                                               {{isset($warehouseProduct->disabled_packages) && in_array('super',$warehouseProduct->disabled_packages) ? 'checked':''}} value="1"/>
                                    </div>
                                @else
                                    <input type="hidden" name="super_unit_code[{{$key}}]" value="0"/>
                                @endif
                            </div>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <ul>
                                            @foreach($warehouseProduct->packaging_info as $packagingInfo)
                                                <li>{{$packagingInfo}}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
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

{{--@include('AlpasalWarehouse::warehouse.warehouse-products.show-partials.mass-price-setting-scripts')--}}




