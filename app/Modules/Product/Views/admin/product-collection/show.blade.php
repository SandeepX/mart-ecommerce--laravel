@extends('Admin::layout.common.masterlayout')
@section('content')
<div class="content-wrapper">
    @include("Admin::layout.partials.breadcrumb",
    [
    'page_title'=>formatWords($title,true),
    'sub_title'=> "Show the {$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route.'.index'),
    ])

    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-success">
                    <div class="box-header with-border">

                        <h3 class="box-title">Details of {{$title}} : {{$productCollection->product_collection_title}}</h3>
                        @can('View Product Collection List')
                        <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">
                            <a href="{{ route($base_route.'.index') }}" style="border-radius: 0px; " class="btn btn-sm btn-primary">
                                <i class="fa fa-list"></i>
                                List of {{formatWords($title,true)}}
                            </a>
                        </div>
                        @endcan
                    </div>

                    <!-- /.box-header -->
                    @include("Admin::layout.partials.flash_message")
                    <div class="box-body">
                        <div class="col-md-12">
                            <div class="card">
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="pull-left">
                                        <h4>Name : {{$productCollection->product_collection_title}}</h4>
                                        <h4>Subtitle : {{$productCollection->product_collection_subtitle}}</h4>
                                        <h4>Remarks : {{$productCollection->remarks}}</h4>
                                    </div>
                                    <div class="pull-right">

                                        <img src="{{asset($productCollection->uploadFolder.$productCollection->product_collection_image)}}" alt="{{$productCollection->product_collection_title}}" width="250px" height="100px">

                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->

                        </div>
                    </div>
                </div>
                <!-- /.box -->
            </div>
            <!--/.col (left) -->

            <div class="col-md-12">

                <!-- general form elements -->
                <div class="box box-success">
                    <div class="box-header with-border">

                        <h3 class="box-title">List of Added Products </h3>

                    </div>

                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="col-md-12">
                            <div class="card">
                                <!-- /.card-header -->
                                <div class="card-body">
                                @if(isset($productCollection->products) && count($productCollection->products) > 0)
                                    <table id="data-table" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                         @foreach($productCollection->products as $i =>  $product)
                                           <tr>
                                           <td>
                                           {{++$i}}
                                           </td>
                                           <td>
                                              {{$product->product_name}}
                                           </td>
                                           </tr>
                                         @endforeach


                                        </tbody>
                                    </table>
                                    @else
                                     <h3 style="color:red">No Products Added </h3>
                                    @endif
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->

                        </div>
                    </div>
                </div>
                <!-- /.box -->
            </div>

        </div>
        <!-- /.row -->
    </section>

</div>



@endsection
