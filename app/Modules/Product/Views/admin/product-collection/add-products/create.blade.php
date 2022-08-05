@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
        @include("Admin::layout.partials.breadcrumb",
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
                                : {{$productCollection->product_collection_title}}</h3>
                            @can('View Product Collection List')
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
                        @include("Admin::layout.partials.flash_message")
                        <div class="box-body">
                            <form class="form-horizontal" role="form" id="addProduct"
                                  action="{{route('admin.product-collection.add-products',$productCollection->product_collection_code)}}"
                                  enctype="multipart/form-data" method="post">
                                {{csrf_field()}}

                                <div class="box-body">
                                    @include(''.$module.'.admin.product-collection.add-products.partials.form')
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <button type="submit" style="width: 49%;margin-left: 17%;"
                                            class="btn btn-block btn-primary addProductInCollection">Add
                                    </button>
                                </div>
                            </form>


                        </div>


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
                                    <th>Active Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($productsInCollection) && count($productsInCollection) > 0)
                                    @foreach($productsInCollection as $i => $product)
                                        <tr>
                                            <td>{{++$i}}</td>
                                            <td>{{$product->product_name}}</td>
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
                                                @can('Add Products In Product Collection')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction($activeStatus,route('admin.collection.products.toggle-status',['productCollectionCode'=>$product->pivot->product_collection_code,'productCode'=>$product->pivot->product_code]),'Change Status', 'pencil','primary')!!}
                                                @endcan

                                                @can('Add Products In Product Collection')
                                                    {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('',route('admin.product-collection.remove-product',[
                                             'product_collection_code' =>  $productCollection->product_collection_code,
                                             'product_code' => $product->product_code
                                           ]),$product,' Product From Collection',$product->product_name)!!}
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
        $('#addProduct').submit(function (e, params) {
            var localParams = params || {};

            if (!localParams.send) {
                e.preventDefault();
            }


            Swal.fire({
                title: 'Are you sure you want to add new product in collection ?',
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
    </script>

@endpush


