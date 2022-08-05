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
            <div id="showFlashMessage"></div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        @can('Add Products To WH Pre Order')
                            <div class="panel-heading">
                                Pre-Order Setting : <b>{{$warehousePreOrder->pre_order_name}}</b>({{$warehousePreOrder->warehouse_preorder_listing_code}})
                                |  Start Date ({{$warehousePreOrder->getStartTime('Y-m-d h:i A')}}
                                to {{$warehousePreOrder->getEndTime('Y-m-d h:i A')}})  |
                                Finalization Time : {{$warehousePreOrder->getFinalizationTime('Y-m-d h:i A')}}
                                <br>

                                @can('Clone Warehouse Products')
                                    <a id="pre-order-clone-button" href="{{route('warehouse.warehouse-pre-orders.clone-products',['preOrderListingCode'=>$warehousePreOrder->warehouse_preorder_listing_code])}}" class="btn btn-success btn-xs float-right">Import Warehouse Products</a>
                                @endcan

                                @can('Clone Vendor Products')
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#cloneByVendor">
                                        Clone Products BY Vendor
                                    </button>
                                @endcan

                                <!-- Modal -->
                                @include('AlpasalWarehouse::warehouse.warehouse-pre-orders.add-products-partials.clone-by-vendor-code-modal')


                            </div>

                            <div class="panel-body">
                                <div class="col-xs-12 col-md-4">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="panel panel-default">

                                                <div class="panel-body">
                                                    <form id="product-filter-form">
                                                        <div class="col-xs-12">
                                                            <label for="vendor">Vendor</label>
                                                            <select id="vendor" name="vendor" class="form-control select2">
                                                                <option value="">
                                                                    All
                                                                </option>

                                                                @foreach($vendors as $vendor)
                                                                    <option value="{{$vendor->vendor_code}}">
                                                                        {{ucwords($vendor->vendor_name)}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-xs-12">

                                                            <label for="category_code">Category</label>
                                                            <select class="form-control select2" id="category_code" name="category_codes[]" multiple="multiple">
                                                                <option value="" disabled>All categories</option>
                                                                @foreach($categories as $category)
                                                                    <option value="{{$category->category_code}}">{{$category->category_name}}</option>
                                                                @endforeach
                                                            </select>

                                                        </div>

                                                        <div class="col-xs-12">

                                                            <label for="brand_code">Brand</label>
                                                            <select class="form-control select2" id="brand_code" name="brand_code">
                                                                <option value="" selected >All Brands</option>
                                                                @foreach($brands as $brand)
                                                                    <option value="{{$brand->brand_code}}">{{$brand->brand_name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-xs-12">

                                                            <label for="product_name">Product Name</label>
                                                            <input type="text" id="product_name" name="product_name" class="form-control"
                                                                   placeholder="Product name">
                                                        </div>


                                                        <br><br>

                                                        <div class="col-xs-12">
                                                            <div class="form-group">
                                                                <button type="submit" class="btn btn-block btn-primary form-control">Filter</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xs-12">
                                            <div class="panel panel-primary">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">
                                                        List of Products
                                                    </h3>

                                                </div>


                                                <div class="box-body">


                                                    <div id="product_list_tbl">
                                                        @include('AlpasalWarehouse::warehouse.warehouse-pre-orders.add-products-partials.products-list-tbl')
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.row -->
                                </div>

                                <div class="col-xs-12 col-md-8">
                                    <div class="panel panel-default">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <form method="get" action="{{route('warehouse.warehouse-pre-orders.add-products',$warehousePreOrder->warehouse_preorder_listing_code)}}">

                                                    <div class="col-sm-6">
                                                          <div class="form-group ml-2">
                                                               <label class="control-label">Vendor Name</label>
                                                               <input class="form-control" name="vendor_name"
                                                                      value="{{(isset($filterParameters['vendor_name']) ? $filterParameters['vendor_name'] : '' ) }}">
                                                          </div>
                                                    </div>
                                                    <div class="col-sm-6 ">
                                                        <div class="form-group">
                                                            <label class="control-label">Product Name</label>
                                                            <input class="form-control ml-2" name="product_name"
                                                                   value="{{(isset($filterParameters['product_name']) ? $filterParameters['product_name'] : '' ) }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-1">
                                                        <div class="form-group">
                                                          <button class="btn btn-primary btn-sm" type="submit">Filter</button>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-1">
                                                        <div class="form-group">
                                                            <a href="{{route('warehouse.warehouse-pre-orders.add-products',$warehousePreOrder->warehouse_preorder_listing_code)}}" class="btn btn-danger btn-sm" >Clear</a>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        <div class="panel-body">
                                            <div id="pre-order-product_list_tbl">
                                                @include('AlpasalWarehouse::warehouse.warehouse-pre-orders.add-products-partials.pre-order-products-tbl')
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @include('AlpasalWarehouse::warehouse.warehouse-pre-orders.add-products-partials.price-setting-modal')
                                @include('AlpasalWarehouse::warehouse.warehouse-pre-orders.add-products-partials.price-update-modal')
                                @include('AlpasalWarehouse::warehouse.warehouse-pre-orders.add-products-partials.packaging-unit-update-modal')

                            </div>
                        @endcan
                    </div>
                </div>
            </div>

        </section>
        <!-- /.content -->
    </div>
@endsection
@push('scripts')
    @include('AlpasalWarehouse::warehouse.warehouse-pre-orders.add-products-partials.warehouse-pre-order-script')
    @include('AlpasalWarehouse::warehouse.warehouse-pre-orders.add-products-partials.clone-by-vendor-scripts')

    <script>
        $('#priceSettingModal').on('shown.bs.modal', function () {
            $(this).find('.modal-dialog').css({width:'auto',
                height:'auto',
                'max-height':'100%'});
        });

        $('#priceUpdateModal').on('shown.bs.modal', function () {
            $(this).find('.modal-dialog').css({width:'auto',
                height:'auto',
                'max-height':'100%'});
        });

        $('#packageUpdateModal').on('shown.bs.modal', function () {
            $(this).find('.modal-dialog').css({width:'auto',
                height:'auto',
                'max-height':'100%'});
        });

    </script>
@endpush
