@extends('AdminWarehouse::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('AdminWarehouse::layout.partials.breadcrumb',
    [
    'page_title'=>$title,
    'sub_title'=> "Manage {$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route.'.index'),
    ])


    <!-- Main content -->
        <section class="content">
            @include('AdminWarehouse::layout.partials.flash_message')
            <div class="row">
                @can('View WH Product Collection List')
                    <div class="col-xs-12">
                        <div class="panel panel-default">

                            <div class="panel-body">
                                <form action="{{route('warehouse.warehouse-product-collections.index')}}" method="get">

                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <label for="collection_title">Title</label>
                                            <input type="text" class="form-control" name="collection_title" id="collection_title" value="{{$filterParameters['collection_title']}}">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary form-control">Filter</button>
                                </form>
                            </div>
                        </div>
                    @endcan
                </div>
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of {{formatWords($title,true)}}
                            </h3>

                            @can('Create WH Product Collection')
                                <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                    <a href="{{ route('warehouse.warehouse-product-collections.create') }}"
                                       style="border-radius: 0px; " class="btn btn-sm btn-info">
                                        <i class="fa fa-plus-circle"></i>
                                        Add New {{formatWords($title,false)}}
                                    </a>
                                </div>
                            @endcan
                        </div>

                        @can('View WH Product Collection List')
                        <div class="box-body">
                            <table class="table table-bordered table-striped" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Subtitle</th>
                                    <th>Image</th>
                                    <th>No.of Products</th>
                                    <th>Active Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($warehouseproductCollections as $i => $warehouseproductCollection)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>{{$warehouseproductCollection->product_collection_title}}</td>
                                        <td>{{$warehouseproductCollection->product_collection_subtitle}}</td>
                                        <td align="center">
                                            <img src="{{asset($warehouseproductCollection->uploadFolder.$warehouseproductCollection->product_collection_image)}}"
                                                 alt="{{$warehouseproductCollection->product_collection_title}}" width="100"
                                                 height="70">
                                        </td>
                                        <td>{{$warehouseproductCollection->qualifiedWarehouseProductMasters->count()}}</td>
                                        <td>
                                            @if($warehouseproductCollection->is_active)
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
                                            @can('Change WH Product Collection Status')
                                                <a href="{{route('warehouse.whproduct.toggle-status', $warehouseproductCollection->product_collection_code)}}" class="btn btn-primary changeStatusOfProduct btn-xs" role="button">change status</a>

{{--                                            {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction($activeStatus,route('warehouse.whproduct.toggle-status', $warehouseproductCollection->product_collection_code),'Change Status', 'pencil','primary')!!}--}}
                                            @endcan
                                            @can('Show WH Product Collection')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('View ',route('warehouse.warehouse-product-collections.show', $warehouseproductCollection->product_collection_code),'View Product Collection Details', 'eye','info')!!}
                                            @endcan

                                            @can('Add Products In WH Product Collection')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Products ',route('warehouse.product-collection.show.add-products', $warehouseproductCollection->product_collection_code),'Add / Show Products', 'plus','warning')!!}
                                            @endcan
                                            @can('Update WH Product Collection')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ',route('warehouse.warehouse-product-collections.edit', $warehouseproductCollection->product_collection_code),'Edit Product Collection', 'pencil','primary')!!}
                                            @endcan


                                            @can('Delete WH Product Collection')
                                                {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete',route('warehouse.warehouse-product-collections.destroy',$warehouseproductCollection->product_collection_code),$warehouseproductCollection,'Product Collection',$warehouseproductCollection->product_collection_title)!!}
                                            @endcan


                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%">
                                            <p class="text-center"><b>No records found!</b></p>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                                {{$warehouseproductCollections->links()}}

                            </table>
                        </div>
                        @endcan
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>



@endsection

@push('scripts')

    <script>
        $('.changeStatusOfProduct').click(function (e){
            e.preventDefault();
            var href = $(this).attr('href');
            Swal.fire({
                title: 'Are you sure you want to change product collection status ?',
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
        })

    </script>
@endpush
