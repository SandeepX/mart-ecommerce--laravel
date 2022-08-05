@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>$title.' Brands',
    'sub_title'=> '',
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route.'.brands.index'),
    ])


    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div class="row">
                <div class="col-xs-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    Showing List of Brands for {{ $category->category_name }}
                                </h3>

                            </div>


                            <div class="box-body">
                                <ul>
                                    @foreach ($brands as $brand)
                                        <li>{{ $brand->brand_name }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                </div>
            </div>
                <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>

@endsection

