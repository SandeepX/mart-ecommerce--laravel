<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-sm-2">
                    <h5>Product</h5>
                </div>
                <div class="col-sm-1">
                    <h5>Mrp</h5>
                </div>
                <div class="col-sm-1">
                    <h5>Admin Margin</h5>
                </div>
                <div class="col-sm-1">
                    <h5>Wholesale Margin</h5>
                </div>
                <div class="col-sm-1">
                    <h5>Retail Margin</h5>
                </div>
                <div class="col-sm-1">
                    <h5>Min Order Quantity</h5>
                </div>
                <div class="col-sm-1">
                    <h5>Max Order Quantity</h5>
                </div>
                <div class="col-sm-1">
                    <h5>Active</h5>
                </div>
                <div class="col-sm-3">
                    <h5>Action</h5>
                </div>
            </div>

            @foreach($warehousePreOrderProducts as $warehousePreOrderProduct)
                <div class="form-div">
                    <form action="{{route('warehouse.warehouse-pre-orders.update-price',['warehousePreOrderCode'=>$warehousePreOrder->warehouse_preorder_listing_code,'productCode'=>$product->product_code])}}"
                          method="post" class="price-update-form">
                        <div class="row">

                            <div class="col-sm-2">
                                <input type="hidden"  name="product_variant_code" value="{{$warehousePreOrderProduct->product_variant_code}}"/>
                                <input type="hidden"
                                       name="product_code" value="{{$warehousePreOrderProduct->product_code}}"/>
                                <h5> {{$warehousePreOrderProduct->product_name}}</h5>
                                <small>{{$warehousePreOrderProduct->product_variant_name}}</small>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <ul>
                                            @foreach($warehousePreOrderProduct->packaging_info as $packagingInfo)
                                                <li>{{$packagingInfo}}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <input type="number" class="form-control" name="mrp"  min="0" value="{{$warehousePreOrderProduct->mrp}}">
                            </div>
                            <div class="col-sm-1">
                                <select name="admin_margin_type">
                                    <option value="p" {{$warehousePreOrderProduct->admin_margin_type == 'p'? 'selected' : ''}}>%</option>
                                    <option value="f" {{$warehousePreOrderProduct->admin_margin_type == 'f'? 'selected' : ''}}>F</option>
                                </select>
                                <input type="number" class="form-control" name="admin_margin_value"  min="0" value="{{$warehousePreOrderProduct->admin_margin_value}}">

                            </div>
                            <div class="col-sm-1">
                                <select name="wholesale_margin_type">
                                    <option value="p" {{$warehousePreOrderProduct->wholesale_margin_type == 'p'? 'selected' : ''}}>%</option>
                                    <option value="f" {{$warehousePreOrderProduct->wholesale_margin_type == 'f'? 'selected' : ''}}>F</option>
                                </select>
                                <input type="number" class="form-control" name="wholesale_margin_value"  min="0" value="{{$warehousePreOrderProduct->wholesale_margin_value}}">
                            </div>
                            <div class="col-sm-1">
                                <select name="retail_margin_type">
                                    <option value="p" {{$warehousePreOrderProduct->retail_margin_type == 'p'? 'selected' : ''}}>%</option>
                                    <option value="f" {{$warehousePreOrderProduct->retail_margin_type == 'f'? 'selected' : ''}}>F</option>
                                </select>

                                <input type="number" class="form-control" name="retail_margin_value"  min="0"  value="{{$warehousePreOrderProduct->retail_margin_value}}">
                            </div>

                            <div class="col-sm-1">
                                <input type="number" class="form-control"  min="1" name="min_order_quantity" value="{{$warehousePreOrderProduct->min_order_quantity}}">
                            </div>

                            <div class="col-sm-1">
                                <input type="number" class="form-control"  min="1" name="max_order_quantity"  value="{{$warehousePreOrderProduct->max_order_quantity}}">
                            </div>
                            <div class="col-sm-1 active-status-div">
                                @if($warehousePreOrderProduct->warehouse_preorder_product_code)
                                    @if($warehousePreOrderProduct->is_active == 1)
                                        @php
                                            $activeStatus = 'Deactivate';
                                        @endphp
                                        <span class="label label-success">On</span>
                                    @else
                                        @php
                                            $activeStatus = 'Activate';
                                        @endphp
                                        <span class="label label-danger">Off</span>
                                    @endif
                                @endif

                            </div>
                            <div class="col-sm-3">
                                <button type="submit" class="btn btn-primary update-price-btn">
                                    Update
                                </button>

                                @if($warehousePreOrderProduct->warehouse_preorder_product_code)
                                    <a href="{{route('warehouse.warehouse-pre-orders.product.toggle-status',['warehousePreOrderCode'=>$warehousePreOrderProduct->warehouse_preorder_listing_code,'preOrderProductCode'=>$warehousePreOrderProduct->warehouse_preorder_product_code])}}" class="btn btn-primary toggle-status-btn">
                                        <i class="fa fa-pencil">
                                            {{$activeStatus}}
                                        </i>
                                    </a>
{{--                                    <a href="{{route('warehouse.warehouse-pre-orders.product.destroy',['warehousePreOrderCode'=>$warehousePreOrderProduct->warehouse_preorder_listing_code,'preOrderProductCode'=>$warehousePreOrderProduct->warehouse_preorder_product_code])}}" class="btn btn-primary delete-pre-product-btn">--}}
{{--                                        <i class="fa fa-trash">--}}
{{--                                        </i>--}}
{{--                                    </a>--}}
                                @endif


                            </div>
                        </div>
                    </form>
                    <hr>
                </div>
            @endforeach
        </div>
    </div>
</div>


