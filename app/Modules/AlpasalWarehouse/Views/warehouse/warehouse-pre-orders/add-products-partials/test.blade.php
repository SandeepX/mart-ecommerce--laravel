<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-sm-2">
                    <h5>Variant</h5>
                </div>
                <div class="col-sm-2">
                    <h5>Mrp</h5>
                </div>
                <div class="col-sm-2">
                    <h5>Admin Margin</h5>
                </div>
                <div class="col-sm-2">
                    <h5>Wholesale Margin</h5>
                </div>
                <div class="col-sm-2">
                    <h5>Retail Margin</h5>
                </div>
                <div class="col-sm-2">
                    <h5>Action</h5>
                </div>
            </div>
            @foreach($warehousePreOrderProducts as $warehousePreOrderProduct)
                <form>
                    <div class="row">
                        <div class="col-sm-2">
                            <input type="hidden"  name="product_variant_code" value="{{$warehousePreOrderProduct->product_variant_code}}"/>
                            <input type="hidden"
                                   name="product_code" value="{{$warehousePreOrderProduct->product_code}}"/>
                            <h5> {{$warehousePreOrderProduct->product_name}}</h5>
                            <small>{{$warehousePreOrderProduct->product_variant_name}}</small>
                        </div>
                        <div class="col-sm-2">
                            <input type="number" min="1" name="mrp" value="{{$warehousePreOrderProduct->mrp}}"/>
                        </div>
                        <div class="col-sm-2">
                            <select name="admin_margin_type">
                                <option value="p" {{$warehousePreOrderProduct->admin_margin_type == 'p'? 'selected' : ''}}>%</option>
                                <option value="f" {{$warehousePreOrderProduct->admin_margin_type == 'f'? 'selected' : ''}}>F</option>
                            </select>
                            <input type="text" aria-label="..." name="admin_margin_value" value="{{$warehousePreOrderProduct->admin_margin_value}}">
                        </div>
                        <div class="col-sm-2">
                            <select name="wholesale_margin_type">
                                <option value="p" {{$warehousePreOrderProduct->wholesale_margin_type == 'p'? 'selected' : ''}}>%</option>
                                <option value="f" {{$warehousePreOrderProduct->wholesale_margin_type == 'f'? 'selected' : ''}}>F</option>
                            </select>

                            <input type="number" min="0" step=".01" name="wholesale_margin_value" value="{{$warehousePreOrderProduct->wholesale_margin_value}}"/>
                        </div>
                        <div class="col-sm-2">
                            <select name="retail_margin_type">
                                <option value="p" {{$warehousePreOrderProduct->retail_margin_type == 'p'? 'selected' : ''}}>%</option>
                                <option value="f" {{$warehousePreOrderProduct->retail_margin_type == 'f'? 'selected' : ''}}>F</option>
                            </select>

                            <input type="number" min="0" step=".01" name="retail_margin_value" value="{{$warehousePreOrderProduct->retail_margin_value}}"/>
                        </div>
                        <div class="col-sm-2">
                            <button type="submit" class="btn btn-primary">
                                Update
                            </button>
                        </div>
                    </div>
                </form>

                <hr>
            @endforeach

        </div>
    </div>
</div>



