@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>$title,
    'sub_title'=> "Manage {$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route.'.index'),
    ])


    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default">

                        <div class="panel-body">
                            <form action="{{route('admin.product-collections.index')}}" method="get">

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
                </div>
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of {{formatWords($title,true)}}
                            </h3>

                            @can('Create Product Collection')
                                <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                    <a href="{{ route('admin.product-collections.create') }}"
                                       style="border-radius: 0px; " class="btn btn-sm btn-info">
                                        <i class="fa fa-plus-circle"></i>
                                        Add New {{formatWords($title,false)}}
                                    </a>
                                </div>
                            @endcan


                        </div>


                        <div class="box-body">
                            @can('View Product Collection List')
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
                                @forelse($productCollections as $i => $productCollection)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>{{$productCollection->product_collection_title}}</td>
                                        <td>{{$productCollection->product_collection_subtitle}}</td>
                                        <td align="center">
                                            <img src="{{asset($productCollection->uploadFolder.$productCollection->product_collection_image)}}"
                                                 alt="{{$productCollection->product_collection_title}}" width="100"
                                                 height="70">
                                        </td>
                                        <td>{{$productCollection->products_count}}</td>
                                        <td>
                                            @if($productCollection->is_active)
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
                                            @can('Change Product Collection Status')
                                            {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction($activeStatus,route('admin.product-collections.toggle-status', $productCollection->product_collection_code),'Change Status', 'pencil','primary')!!}
                                            @endcan

                                            @can('Show Product Collection')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('View ',route('admin.product-collections.show', $productCollection->product_collection_code),'View Product Collection Details', 'eye','info')!!}
                                            @endcan

                                            @can('Add Products In Product Collection')
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Products ',route('admin.product-collection.show.add-products', $productCollection->product_collection_code),'Add / Show Products', 'plus','warning')!!}
                                                @endcan
                                            @can('Update Product Collection')
                                                 {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ',route('admin.product-collections.edit', $productCollection->product_collection_code),'Edit Product Collection', 'pencil','primary')!!}
                                            @endcan


                                            @can('Delete Product Collection')
                                                {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete',route('admin.product-collections.destroy',$productCollection->product_collection_code),$productCollection,'Product Collection',$productCollection->product_collection_title)!!}
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
                                {{$productCollections->links()}}

                            </table>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>



@endsection
