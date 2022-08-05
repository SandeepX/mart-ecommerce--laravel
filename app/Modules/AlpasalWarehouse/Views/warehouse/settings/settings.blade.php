@extends('AdminWarehouse::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('AdminWarehouse::layout.partials.breadcrumb',
    [
    'page_title'=>$title,
    'sub_title'=> "Manage {$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route),
    ])

    <!-- Main content -->
        <section class="content">
            @include('AdminWarehouse::layout.partials.flash_message')
            <div class="row">

                <div class="col-md-3 col-sm-6 col-xs-12">
                    @can('View Invoice Setting Lists')
                        <div class="info-box">
                            <span class="info-box-icon bg-aqua"><i class="fa fa-gear"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text"><a href="{{route('warehouse.warehouse-settings-invoice.index')}}">Invoice Settings</a></span>
                                <span class="info-box-number"><small>Set Invoice For warehouses</small></span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    @endcan
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-aqua"><i class="fa fa-gear"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text"><a href="{{route('warehouse.min-order-settings.index')}}">Min Order Settings</a></span>
                                <span class="info-box-number"><small>Set Min Order Setting For warehouses</small></span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
@endsection
