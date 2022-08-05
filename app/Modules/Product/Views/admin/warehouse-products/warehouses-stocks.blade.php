@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
     'page_title'=>$title,
    'sub_title'=> "Manage {$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route('admin.products.index'),
    ])
    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="box-body">
                            <div class="pull-right">
                                <h4>Added On : {{date('Y-m-d',strtotime($productDetail->created_at))}} </h4>
                            </div>
                            <div>
                                <h4>Product : {{$productDetail->product_name}}</h4>
                                <h4>Brand : {{$productDetail->brand->brand_name}}</h4>
                                <h4>Category : {{$productDetail->category->category_name}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">Stock Detail</div>
                        <div class="box-body">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>S.N</th>
                                    <th>Product</th>
                                    <th>Warehouse</th>
                                    <th>Current Stock</th>
                                </tr>
                                </thead>
                                <tbody>

                                @forelse($warehouseProducts as $warehouseProduct)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>
                                            {{$warehouseProduct->getProductProperty('product_name')}}
                                            <br>
                                            <small>{{$warehouseProduct->productVariant ? $warehouseProduct->productVariant->product_variant_name : ''}}</small>
                                        </td>
                                        <td>
                                            {{$warehouseProduct->warehouse->warehouse_name}}
                                        </td>
                                        <td>
                                            {{$warehouseProduct->current_stock}}
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10">
                                            <p class="text-center"><b>No stocks available!</b></p>
                                        </td>

                                    </tr>
                                @endforelse

                                </tbody>
                            </table>
                            {{$warehouseProducts->appends($_GET)->links()}}
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
@endsection

