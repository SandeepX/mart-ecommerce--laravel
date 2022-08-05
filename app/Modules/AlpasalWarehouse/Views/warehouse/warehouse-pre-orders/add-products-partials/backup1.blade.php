<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-sm-2">
                    <h5>Product</h5>
                </div>
                <div class="col-sm-8">
                    <h5>Action</h5>
                </div>
            </div>

            <form action="{{route('warehouse.warehouse-pre-orders.update-price',['warehousePreOrderCode'=>$warehousePreOrder->warehouse_preorder_listing_code,'productCode'=>$product->product_code])}}"
                  method="post" class="price-update-form">
                @foreach($warehousePreOrderProducts as $warehousePreOrderProduct)
                    <div class="form-div">

                        <div class="row">

                            <div class="col-sm-2">
                                <input type="hidden"  name="product_variant_code" value="{{$warehousePreOrderProduct->product_variant_code}}"/>
                                <input type="hidden"
                                       name="product_code" value="{{$warehousePreOrderProduct->product_code}}"/>
                                <h5> {{$warehousePreOrderProduct->product_name}}</h5>
                                <small>{{$warehousePreOrderProduct->product_variant_name}}</small>
                            </div>

                            @if($warehousePreOrderProduct->micro_unit_code)
                                <div class="col-sm-2">
                                    <label for="micro_unit_code">
                                        Micro
                                        <br>
                                        <small>{{$warehousePreOrderProduct->micro_package_name}}</small>
                                    </label>
                                    <input type="checkbox"  name="micro_unit_code" id="micro_unit_code"/>
                                </div>
                            @endif

                            @if($warehousePreOrderProduct->unit_code)
                                <div class="col-sm-2">
                                    <label for="unit_code">
                                        Unit
                                        <br>
                                        <small>{{$warehousePreOrderProduct->unit_package_name}}</small>
                                    </label>
                                    <input type="checkbox"  name="unit_code" id="unit_code"/>
                                </div>
                            @endif

                            @if($warehousePreOrderProduct->macro_unit_code)
                                <div class="col-sm-2">
                                    <label for="macro_unit_code">
                                        Macro
                                        <br>
                                        <small>{{$warehousePreOrderProduct->micro_package_name}}</small>
                                    </label>
                                    <input type="checkbox"  name="macro_unit_code" id="macro_unit_code"/>
                                </div>
                            @endif

                            @if($warehousePreOrderProduct->super_unit_code)
                                <div class="col-sm-2">
                                    <label for="super_unit_code">
                                        Micro
                                        <br>
                                        <small>{{$warehousePreOrderProduct->micro_package_name}}</small>
                                    </label>
                                    <input type="checkbox"  name="super_unit_code" id="super_unit_code"/>
                                </div>
                            @endif


                        </div>
                        <hr>
                    </div>
                @endforeach
                <div class="col-sm-3">
                    <button type="submit" class="btn btn-primary update-price-btn">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


