@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>$title.' Warehouses',
    'sub_title'=> '',
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route.'.warehouses.index'),
    ])


    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                Showing List of Warehouses for {{ $store->store_name }}
                            </h3>

                        </div>


                        <div class="box-body">
                            <ul>
                                @foreach ($store->warehouses as $warehouse)
                                    <li>{{ $warehouse->warehouse_name }}</li>
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

