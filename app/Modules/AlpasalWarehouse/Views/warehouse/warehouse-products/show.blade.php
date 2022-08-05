@extends('AdminWarehouse::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('AdminWarehouse::layout.partials.breadcrumb',
    [
     'page_title'=>$title,
    'sub_title'=> "Manage {$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route.'index'),
    ])
    <!-- Main content -->
        <section class="content">
            @include('AdminWarehouse::layout.partials.flash_message')
            <div class="row">
                @can('View WH Product Detail')
                    <div class="col-xs-12">
                        <div class="panel panel-primary">
                            <div class="box-body">
                                <div class="pull-right">
                                    <h4>Added On : {{date('Y-m-d',strtotime($warehouseProductDetail->created_at))}} </h4>
                                </div>
                                <div>
                                    <h4>Product : {{$warehouseProductDetail->getProductProperty('product_name')}}</h4>
                                    <h4>Brand : {{$warehouseProductDetail->getProductProperty('brand_name')}}</h4>
                                    <h4>Category : {{$warehouseProductDetail->getProductProperty('category_name')}}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan

                @include(''.$module.'.warehouse.warehouse-products.show-partials.stock-history-modal')
                @can('View WH Product Detail')
                    <div class="col-xs-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">Price Setting</div>
                            <div class="box-body">
                                <table class="table table-bordered">
                                    <thead>

                                    <tr>
                                        <th>S.N</th>
                                        @if($warehouseProductDetail->has_product_variants)
                                            <th>Variant</th>
                                        @else
                                            <th>Product</th>
                                        @endif
                                        <th>Current Stock</th>
                                        <th>Order Limit</th>
                                        <th>Price Setting</th>
                                        <th>Is Active</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>


                                    @if($warehouseProductDetail->has_product_variants)

                                        @foreach($warehouseProductDetail->product_variants as $productVariant)

                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>
                                                    {{$warehouseProductDetail->getProductProperty('product_name')}}
                                                    <br>
                                                    <small>{{$productVariant['product_variant_name']}}</small>
                                                </td>
                                                <td>
                                                    {{$productVariant->getCurrentProductStock()}}
                                                </td>
                                                <td>

                                                    <div>Min Limit: {{ isset($productVariant['min_order_quantity'])? $productVariant['min_order_quantity']:'N/A'}} </div>
                                                    <div>Max limit: {{isset($productVariant['max_order_quantity'])? $productVariant['max_order_quantity']:'N/A'}} </div>
                                                </td>

                                                <td>
                                                    @if(isset($productVariant->warehouseProductPriceMaster))
                                                        <div>MRP: {{$productVariant->warehouseProductPriceMaster['mrp']}}</div>
                                                        <div>Admin Margin({{$productVariant->warehouseProductPriceMaster['admin_margin_type']}}): {{$productVariant->warehouseProductPriceMaster['admin_margin_value']}}</div>
                                                        <div>Wholesale Margin({{$productVariant->warehouseProductPriceMaster['wholesale_margin_type']}}): {{$productVariant->warehouseProductPriceMaster['wholesale_margin_value']}}</div>
                                                        <div>Retail Margin({{$productVariant->warehouseProductPriceMaster['retail_margin_type']}}): {{$productVariant->warehouseProductPriceMaster['retail_margin_value']}}</div>
                                                    @else
                                                        <p>N/A</p>
                                                    @endif

                                                </td>

                                                <td>
                                                    @if((isset($productVariant['is_active']) && $productVariant['is_active'] === 1))
                                                        <a href="{{route('warehouse.warehouse-products-status.toggle',$productVariant->warehouse_product_master_code)}}"><span class="label label-success">Yes</span></a>
                                                    @else
                                                        <a href="{{route('warehouse.warehouse-products-status.toggle',$productVariant->warehouse_product_master_code)}}"><span class="label label-danger">No</span></a>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-primary stock-history-btn btn-sm"
                                                            data-wpm-code="{{$productVariant['warehouse_product_master_code']}}">
                                                        Stock History
                                                    </button>
                                                    <button type="button" class="btn btn-primary price-history-btn btn-sm"
                                                            data-wpm-code="{{$productVariant['warehouse_product_master_code']}}">
                                                        Price History
                                                    </button>

                                                    @can('Price Setting For WH Product')
                                                        <button type="button" class="btn btn-primary update-price-btn btn-sm"
                                                                data-wpm-code="{{$productVariant['warehouse_product_master_code']}}"
                                                                data-product-name="{{$productVariant['product_variant_name']}}">
                                                            Price Setting
                                                        </button>
                                                    @endcan

                                                    @can('Set Quantity Limit For WH Product')
                                                        <button type="button" class="btn btn-primary btn-xs setLimit" data-toggle="modal" data-wpmc="{{ $productVariant['warehouse_product_master_code'] }}" data-target="#setLimitOnQuantityModal">
                                                            Set Quantity Limit
                                                        </button>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td>1</td>
                                            <td>{{$warehouseProductDetail->getProductProperty('product_name')}}</td>
                                            <td>
                                                {{$warehouseProductDetail->getCurrentProductStock()}}
                                            </td>
                                            <td>
                                                <div>Min Limit: {{isset($warehouseProductDetail->min_order_quantity)? $warehouseProductDetail->min_order_quantity:'N/A'}}</div>
                                                <div>Max Limit: {{isset($warehouseProductDetail->max_order_quantity)? $warehouseProductDetail->max_order_quantity:'N/A'}}</div>
                                            </td>
                                            <td>
                                                @if($warehouseProductDetail->warehouseProductPriceMaster)
                                                    <div>MRP: {{$warehouseProductDetail->warehouseProductPriceMaster['mrp']}}</div>
                                                    <div>Admin Margin({{$warehouseProductDetail->warehouseProductPriceMaster['admin_margin_type']}}): {{$warehouseProductDetail->warehouseProductPriceMaster['admin_margin_value']}}</div>
                                                    <div>Wholesale Margin({{$warehouseProductDetail->warehouseProductPriceMaster['wholesale_margin_type']}}): {{$warehouseProductDetail->warehouseProductPriceMaster['wholesale_margin_value']}}</div>
                                                    <div>Retail Margin({{$warehouseProductDetail->warehouseProductPriceMaster['retail_margin_type']}}): {{$warehouseProductDetail->warehouseProductPriceMaster['retail_margin_value']}}</div>

                                                @else
                                                    <p>N/A</p>
                                                @endif
                                            </td>

                                            <td>
                                                @if($warehouseProductDetail->is_active==1)

                                                    <a href="{{route('warehouse.warehouse-products-status.toggle',$warehouseProductDetail->warehouse_product_master_code)}}"><span class="label label-success">Yes</span></a>
                                                @else
                                                    <a href="{{route('warehouse.warehouse-products-status.toggle',$warehouseProductDetail->warehouse_product_master_code)}}"><span class="label label-danger">No</span></a>
                                                @endif
                                            </td>

                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm stock-history-btn"
                                                        data-wpm-code="{{$warehouseProductDetail['warehouse_product_master_code']}}">
                                                    Stock History
                                                </button>
                                                <button type="button" class="btn btn-primary btn-sm price-history-btn"
                                                        data-wpm-code="{{$warehouseProductDetail['warehouse_product_master_code']}}">
                                                    Price History
                                                </button>
                                                @can('Price Setting For WH Product')
                                                    <button type="button" class="btn btn-primary btn-sm update-price-btn"
                                                            data-wpm-code="{{$warehouseProductDetail['warehouse_product_master_code']}}"
                                                            data-product-name="{{$warehouseProductDetail->getProductProperty('product_name')}}">
                                                        Price Setting
                                                    </button>
                                                @endcan

                                                @can('Set Quantity Limit For WH Product')
                                                    <button type="button" class="btn btn-primary btn-xs setLimit" data-toggle="modal" data-wpmc="{{$warehouseProductDetail->warehouse_product_master_code}}" data-target="#setLimitOnQuantityModal">
                                                        Set Quantity Limit
                                                    </button>
                                                @endcan
                                        </tr>

                                    @endif

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endcan
                @include(''.$module.'.warehouse.warehouse-products.show-partials.update-price-setting-modal')
                @include(''.$module.'.warehouse.warehouse-products.show-partials.price-history-modal')
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>

    <!-- Modal -->
    <div class="modal fade" id="setLimitOnQuantityModal" tabindex="-1" role="dialog" aria-labelledby="setLimitOnQuantityModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="setLimitOnQuantityModalLabel"> Warehouse Product Quantity limit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{route('warehouse.warehouse-products.set-qty-limit-store')}}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" value="" id="warehouseProductCode" name="warehouse_product_master_code">
                        <div>
                            <label for="min_order_quantity">Min Order Limit Quantity:</label>               &nbsp;

                            <input id="min_order_quantity" type="number" min="1"
                                   name="min_order_quantity" value=""  id="min_order_quantity" />
                        </div>
                        <br>
                        <div>
                            <label for="max_order_quantity">Max Order Limit Quantity:</label>                            &nbsp;
                            <input  type="number" value="" min="1"  name="max_order_quantity"  id="max_order_quantity" />
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">save Qty Limit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script>

        $('.setLimit').on('click',function (){
            $("#warehouseProductCode").val($(this).attr('data-wpmc'));
        });

    </script>

    @include(''.$module.'.warehouse.warehouse-products.show-partials.show-scripts')

@endpush

