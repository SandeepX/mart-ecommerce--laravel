@extends('AdminWarehouse::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
        @include("AdminWarehouse::layout.partials.breadcrumb",
        [
        'page_title'=>$title,
        'sub_title'=> "Add Products in  {$title}",
        'icon'=>'home',
        'sub_icon'=>'',
        'manage_url'=>route($base_route.'.index')
        ])

        <section class="content">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-header with-border">

                            <h3 class="box-title">Add Products in Collection
                                : {{$warehouseproductCollection->product_collection_title}}</h3>
                            @can('View WH Product Collection List')
                                <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">
                                    <a href="{{ route($base_route.'.index') }}" style="border-radius: 0px; "
                                       class="btn btn-sm btn-success">
                                        <i class="fa fa-list"></i>
                                        List of {{formatWords($title,true)}}
                                    </a>
                                </div>
                            @endcan
                        </div>

                        <!-- /.box-header -->
                        @include("AdminWarehouse::layout.partials.flash_message")
                        @can('Add Products In WH Product Collection')
                            <div class="box-body">
                                <form class="form-horizontal" id="addProductInCollection" role="form"
                                      action="{{route('warehouse.product-collection.add-products',$warehouseproductCollection->product_collection_code)}}"
                                      enctype="multipart/form-data" method="post">
                                    {{csrf_field()}}

                                    <div class="box-body">
                                        @include('AlpasalWarehouse::warehouse.warehouse-product-collections.add-products.partials.form')
                                    </div>
                                    <!-- /.box-body -->
                                    <div class="box-footer">
                                        <button type="submit" style="width: 49%;margin-left: 17%;"
                                                class="btn btn-block btn-primary">Add
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endcan
                    </div>
                    <!-- /.box -->
                </div>
                <!--/.col (left) -->


                <div class="col-xs-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of Added Products
                            </h3>
                        </div>


                        <div class="box-body">
                            <table id="data-table" class="table table-bordered table-striped" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
{{--                                    <th>Image</th>--}}
                                    <th>Active Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($productsInCollection) && count($productsInCollection) > 0)
                                    @foreach($productsInCollection as $i => $product)
                                        <tr>
                                            <td>{{++$i}}</td>
                                            <td>{{$product->product->product_name}}</td>
{{--                                            <td align="center">--}}
{{--                                                <img src="{{$product->getFeaturedImage()}}"--}}
{{--                                                     alt="{{$product->product_name}}" width="100"--}}
{{--                                                     height="70">--}}
{{--                                            </td>--}}
                                            <td>
                                                @if($product->pivot->is_active)
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
                                                @can('Add Products In WH Product Collection')
                                                    <a href="{{route('warehouse.products.toggle-status',['productCollectionCode'=>$product->pivot->product_collection_code,'productMasterCode'=>$product->pivot->warehouse_product_master_code])}}" class="btn btn-primary changeStatusOfProductInCollection btn-xs" role="button">change status</a>
{{--                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction($activeStatus,route('warehouse.products.toggle-status',['productCollectionCode'=>$product->pivot->product_collection_code,'productMasterCode'=>$product->pivot->warehouse_product_master_code]),'Change Status', 'pencil','primary')!!}--}}

                                                    {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('',route('warehouse.product-collection.remove-product',[
                                             'product_collection_code' =>  $product->pivot->product_collection_code,
                                             'warehouse_product_master_code' => $product->pivot->warehouse_product_master_code
                                           ]),$product,' Product From Collection',$product->product->product_name)!!}
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.row -->
        </section>

    </div>



@endsection

@push('scripts')

    <script>
        $('#addProductInCollection').submit(function (e, params) {
            var localParams = params || {};

            if (!localParams.send) {
                e.preventDefault();
            }


            Swal.fire({
                title: 'Are you sure you want to add new products In collection ?',
                showCancelButton: true,
                confirmButtonText: `Yes`,
                padding:'10em',
                width:'500px'
            }).then((result) => {
                if (result.isConfirmed) {

                    $(e.currentTarget).trigger(e.type, { 'send': true });
                    Swal.fire({
                        title: 'Please wait...',
                        hideClass: {
                            popup: ''
                        }
                    })
                }
            })
        });

        $('.changeStatusOfProductInCollection').click(function (e){
            e.preventDefault();
            var href = $(this).attr('href');
            Swal.fire({
                title: 'Are you sure you want to change product status ?',
                showDenyButton: true,
                confirmButtonText: `Yes`,
                denyButtonText: `No`,
                padding:'10em',
                width:'500px'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                } else if (result.isDenied) {
                    Swal.fire('changes not saved', '', 'info')
                }
            })
        });

    </script>



@endpush

