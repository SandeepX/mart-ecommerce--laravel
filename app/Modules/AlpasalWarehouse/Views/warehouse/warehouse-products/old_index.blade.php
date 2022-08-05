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
                <div class="col-xs-12">
                    <div class="panel panel-default">

                        <div class="panel-body">
                            <form action="{{route('warehouse.warehouse-products.index')}}" method="get">
                                <div class="col-xs-6">
                                    <label for="vendor">Vendor</label>
                                    <select id="vendor" name="vendor" class="form-control">
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


                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label for="product_name">Product</label>
                                        <input type="text" class="form-control" name="product_name" id="product_name"
                                               value="{{$filterParameters['product_name']}}">
                                    </div>
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

                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Package</th>
                                    <th>Price</th>
                                    <th>Active Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($products as $i => $product)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>
                                            <strong>Name </strong> : {{$product->product_name}}<br>
                                            <strong>Brand </strong> : {{$product->brand->brand_name}}<br>
                                            <strong>Category </strong> : {{$product->category->category_name}}<br>
                                            <strong>Vendor </strong> : {{$product->vendor->vendor_name}}
                                        </td>
                                        <td>{{$product->product_code}}</td>
                                        <td>{{$product->package->packageType ? $product->package->packageType->package_name : 'No Package Type'}}</td>
                                        <td>
                                        @if(count($product->priceList) > 0)

                                            <!-- Trigger the modal with a button -->
                                                <button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#productPrice{{$i}}">
                                                    <i class="fa fa-eye"></i>
                                                    Price
                                                </button>

                                                <!-- Modal -->
                                                <div id="productPrice{{$i}}" class="modal fade" role="dialog">
                                                    <div class="modal-dialog">

                                                        <!-- Modal content-->
                                                        <div style="overflow:auto;" class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title">Product : {{$product->product_name}} </h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                @foreach($product->priceList as $price)
                                                                    @if($price->productVariant)
                                                                        <strong>Variant </strong> : {{$price->productVariant->product_variant_name}}<br>
                                                                    @endif
                                                                    <strong>MRP </strong> : {{$price->mrp}}<br>
                                                                    <strong>Admin Margin </strong> : {{$price->admin_margin_value}} ({{$price->admin_margin_type == "p" ? "%" : "flat" }})<br>
                                                                    <strong>Whole Sale Margin </strong> : {{$price->wholesale_margin_value}} ({{$price->wholesale_margin_type == "p" ? "%" : "flat" }})<br>
                                                                    <strong>Retail Margin </strong> : {{$price->retail_store_margin_value}} ({{$price->retail_store_margin_type == "p" ? " %" : "flat" }})
                                                                    <hr>
                                                                @endforeach
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                            @else
                                                <strong> N/A </strong>
                                            @endif
                                        </td>
                                        <td>
                                            @if($product->is_active)
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

                                        </td>
                                        <td>

                                            {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction($activeStatus,route('admin.products.toggle-status', $product->product_code),'Change Status', 'pencil','primary')!!}

                                            @can('Show Product')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Details ',route('admin.products.show', $product->product_code),'Details', 'eye','info')!!}
                                            @endcan
                                        </td>
                                    </tr>

                                @empty
                                    <tr>
                                        <td colspan="10">
                                            <p class="text-center"><b>No records found!</b></p>
                                        </td>

                                    </tr>
                                @endforelse
                                </tbody>


                            </table>
                            {{$products->appends($_GET)->links()}}
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
@endsection
