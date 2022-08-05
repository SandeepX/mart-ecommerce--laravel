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
                @can('View List Of Wh Products')
                    <div class="col-xs-12">
                        <div class="panel panel-default">

                            <div class="panel-body">
                                <form action="{{route('warehouse.warehouse-products.index')}}" method="get">
                                    <div class="col-xs-4">
                                        <label for="vendor">Vendor</label>
                                        <select id="vendor" name="vendor" class="form-control select2">
                                            <option value="">
                                                All
                                            </option>

                                            @foreach($vendors as $vendor)
                                                <option value="{{$vendor->vendor_code}}"
                                                    {{$vendor->vendor_code == $filterParameters['vendor_code'] ?'selected' :''}}>
                                                    {{ucwords($vendor->vendor_name)}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <label for="product_name">Product</label>
                                            <input type="text" class="form-control" name="product_name" id="product_name"
                                                   value="{{$filterParameters['product_name']}}">
                                        </div>
                                    </div>

                                    <div class="col-xs-4">
                                        <label for="status">Status</label>
                                        <select class="form-control" id="status" name="status">
                                            <option value="" {{is_null($filterParameters['status'])? 'selected' :''}}>Select All</option>
                                            <option value="1" {{(isset($filterParameters['status']) && $filterParameters['status']== 1)? 'selected' :''}}>Active</option>
                                            <option value="0" {{(isset($filterParameters['status']) && $filterParameters['status']== 0)? 'selected' :''}}>Inactive</option>
                                        </select>
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
                @endcan
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of Products
                            </h3>

                        </div>

                        <div class="box-body">
                            @can('Change WH Product Status')
                                <div class="pull-left">
                                    <button class="btn btn-primary btn-sm"  data-toggle="modal" data-target="#warehouseProductStatusModal">
                                        Change warehouse whole product status
                                    </button>
                                </div>
                            @endcan
                            <div class="pull-left">
                                <button class="btn btn-primary btn-sm"  data-toggle="modal" data-target="#warehouseProductsMicroDisableModal">
                                    <i class="fa fa-question-circle"> Disable/Enable Micro Packaging Of All product</i>
                                </button>
                            </div>


                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                @forelse($warehouseProducts as $i => $warehouseProduct)

                                    <tr>

                                        <td>{{++$i}}</td>
                                        <td>
                                            <strong>Name </strong> : {{$warehouseProduct->getProductProperty('product_name')}}<br>
                                            <strong>Brand </strong> : {{$warehouseProduct->getProductProperty('brand_name')}}<br>
                                            <strong>Category </strong> : {{$warehouseProduct->getProductProperty('category_name')}}<br>
                                            <strong>Vendor </strong> : {{$warehouseProduct->getProductProperty('vendor_name')}}<br>
                                            <strong>Total Product variant</strong> : {{$warehouseProduct->totalVariants}}<br>
                                            <strong>Code</strong> : {{$warehouseProduct->getProductProperty('product_code')}}<br>
{{--                                            <strong>Package</strong> : {{$warehouseProduct->product->package->packageType ? $warehouseProduct->product->package->packageType->package_name : 'No Package Type'}}<br>--}}
                                        </td>
                                          <td>
                                            <div>
                                                @if($warehouseProduct->active_product > 0)
                                                    <span class="label label-success">Active</span>
                                                @else
                                                    <span class="label label-danger">Inactive</span>
                                                @endif

                                            </div>
                                            <div style="padding-top: 5px;">
                                                <div style="font-size : 12px;">Total Active Product: <strong>{{$warehouseProduct->active_product}}</strong></div>
                                                <div style="font-size : 12px;">Total Inactive Product:<strong> {{$warehouseProduct->inactive_product}}</strong></div>
                                            </div>
                                        </td>

                                        <td>
                                            <button type="button" class="btn btn-primary btn-xs price-info-btn"
                                                    data-wpm-code="{{$warehouseProduct['warehouse_product_master_code']}}">
                                                Price Info
                                            </button>
                                            <a href="{{route('warehouse.warehouse-products.create.mass-price-setting',$warehouseProduct->product_code)}}" style="border-radius: 3px; " class="btn btn-xs btn-info price-setting-btn" data-toggle="modal" data-target="#priceSettingModal">
                                                Price Setting
                                            </a>

                                            <a href="{{route('warehouse.warehouse-products.edit.mass-packaging-disable-list',$warehouseProduct->product_code)}}" style="border-radius: 3px; " class="btn btn-xs btn-info package-disable-btn" data-toggle="modal" data-target="#packageDisableModal">
                                               Packaging Disable List
                                            </a>

                                            {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Details ',route('warehouse.warehouse-products.show', $warehouseProduct->product_code),'Details', 'eye','info')!!}

                                              <button class="btn btn-primary btn-sm changeStatusBtn " style="padding-top: 1px;padding-bottom: 1px;" data-toggle="modal" data-product-name="{{$warehouseProduct->getProductProperty('product_name')}}" data-product-code="{{$warehouseProduct->product_code}}" data-target="#exampleModal">
                                                Change product status
                                              </button>



                                        </td>
                                    </tr>


{{--                            @can('View List of Wh Products')--}}
{{--                                <table class="table table-bordered table-striped" cellspacing="0" width="100%">--}}
{{--                                    <thead>--}}
{{--                                        <tr>--}}
{{--                                            <th>#</th>--}}
{{--                                            <th>Name</th>--}}
{{--                                            <th>Code</th>--}}
{{--                                            <th>Package</th>--}}
{{--                                            <th>Status</th>--}}
{{--                                            <th>Action</th>--}}
{{--                                        </tr>--}}
{{--                                    </thead>--}}
{{--                                    <tbody>--}}

{{--                                    @forelse($warehouseProducts as $i => $warehouseProduct)--}}

{{--                                        <tr>--}}

{{--                                            <td>{{++$i}}</td>--}}
{{--                                            <td>--}}
{{--                                                <strong>Name </strong> : {{$warehouseProduct->getProductProperty('product_name')}}<br>--}}
{{--                                                <strong>Brand </strong> : {{$warehouseProduct->getProductProperty('brand_name')}}<br>--}}
{{--                                                <strong>Category </strong> : {{$warehouseProduct->getProductProperty('category_name')}}<br>--}}
{{--                                                <strong>Vendor </strong> : {{$warehouseProduct->getProductProperty('vendor_name')}}<br>--}}
{{--                                                <strong>Total Product variant</strong> : {{$warehouseProduct->totalVariants}}--}}
{{--                                            </td>--}}
{{--                                            <td>{{$warehouseProduct->getProductProperty('product_code')}}</td>--}}
{{--                                            <td>{{$warehouseProduct->product->package->packageType ? $warehouseProduct->product->package->packageType->package_name : 'No Package Type'}}</td>--}}
{{--                                            <td>--}}
{{--                                                <div>--}}
{{--                                                    @if($warehouseProduct->active_product > 0)--}}
{{--                                                        <span class="label label-success">Active</span>--}}
{{--                                                    @else--}}
{{--                                                        <span class="label label-danger">Inactive</span>--}}
{{--                                                    @endif--}}

{{--                                                </div>--}}
{{--                                                <div style="padding-top: 5px;">--}}
{{--                                                    <div style="font-size : 12px;">Total Active Product: <strong>{{$warehouseProduct->active_product}}</strong></div>--}}
{{--                                                    <div style="font-size : 12px;">Total Inactive Product:<strong> {{$warehouseProduct->inactive_product}}</strong></div>--}}
{{--                                                </div>--}}
{{--                                            </td>--}}

{{--                                            <td>--}}
{{--                                                <button type="button" class="btn btn-primary price-info-btn"--}}
{{--                                                        data-wpm-code="{{$warehouseProduct['warehouse_product_master_code']}}">--}}
{{--                                                    Price Info--}}
{{--                                                </button>--}}
{{--                                                @can('View WH Product Detail')--}}
{{--                                                    {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Details ',route('warehouse.warehouse-products.show', $warehouseProduct->product_code),'Details', 'eye','info')!!}--}}
{{--                                                @endcan--}}

{{--                                                @can('Change WH Product Status')--}}
{{--                                                  <button class="btn btn-primary btn-sm changeStatusBtn " style="padding-top: 1px;padding-bottom: 1px;" data-toggle="modal" data-product-name="{{$warehouseProduct->getProductProperty('product_name')}}" data-product-code="{{$warehouseProduct->product_code}}" data-target="#exampleModal">--}}
{{--                                                    Change product status--}}
{{--                                                  </button>--}}
{{--                                                @endcan--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}

                                    @empty
                                        <tr>
                                            <td colspan="10">
                                                <p class="text-center"><b>No records found!</b></p>
                                            </td>

                                        </tr>
                                    @endforelse
                                    </tbody>


                                </table>
                                {{$warehouseProducts->appends($_GET)->links()}}
{{--                            @endcan--}}
                        </div>
                    </div>
                </div>
                @include(''.$module.'.warehouse.warehouse-products.show-partials.price-info-modal')
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    Change status of product: <strong class="modal-title productName" id="exampleModalLabel"></strong>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="showFlashMessageModal"></div>
                    <form class="warehouseProduct" method="post" id="statusChangeForm" >
                        @csrf

                        <input type="hidden" name="productCode" id="productCode" value=""/>

                        <div class="form-group">
                            <label class="control-label">Change Status</label>
                            <select type="text" class="form-control input-sm" value="" name="is_active" id="is_active" required autocomplete="off">
                                <option value="">select status</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" id="changeStatus" class="btn btn-success">Change</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>


    <div class="modal fade bd-example-modal-lg" id="priceSettingModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            </div>
        </div>
    </div>

    @include(''.$module.'.warehouse.warehouse-products.packaging.mass-packaging-disable-modal')

    @include(''.$module.'.warehouse.warehouse-products.warehouse-all-product-change-modal')


    @include(''.$module.'.warehouse.warehouse-products.index-partials.disable-enable-micro-packaging-modal')
@endsection

@push('scripts')

    @include(''.$module.'.warehouse.warehouse-products.show-partials.product-status-change-scripts')
    @include(''.$module.'.warehouse.warehouse-products.show-partials.show-scripts')
    @include(''.$module.'.warehouse.warehouse-products.packaging.packaging-disable-scripts')

    <script>
        $(document).ready(function(){
            /*$('a[data-toggle="priceSettingModal"]').click(function(e) {
                e.preventDefault();
                var target = $(this).attr('data-target');
                $(`${target} .modal-content`).html('');
                let url = $(this).attr('href');
                $(`${target} .modal-content`).load(url, function(result) {
                    $(target).show();
                });
            });*/
            $(document).on('click','.price-setting-btn',function (e){
                e.preventDefault();
                var target = $(this).attr('data-target');
                $(`${target} .modal-content`).html('');
                let url = $(this).attr('href');
                $(`${target} .modal-content`).load(url, function(result) {
                    $(target).show();
                });
            });

            $(document).on('click','.package-disable-btn',function (e){
                e.preventDefault();
                var target = $(this).attr('data-target');
                $(`${target} .modal-content`).html('');
                let url = $(this).attr('href');
                $(`${target} .modal-content`).load(url, function(result) {
                    $(target).show();
                });
            });
        });

        $('#priceSettingModal').on('shown.bs.modal', function () {
            $(this).find('.modal-dialog').css({width:'auto',
                height:'auto',
                'max-height':'100%'
            });
        });

    </script>


@endpush
