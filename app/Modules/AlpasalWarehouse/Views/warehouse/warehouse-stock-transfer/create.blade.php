@extends('AdminWarehouse::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('AdminWarehouse::layout.partials.breadcrumb', [
        'page_title' => $title,
        'sub_title' => "Add {$title}",
        'icon' => 'home',
        'sub_icon' => '',
        'manage_url' => route($base_route.'.index'),
    ])

    <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <!-- general form elements -->
                    <div class="box box-success">
                        <div class="box-header with-border">

                            <h3 class="box-title">Add the {{$title}}</h3>
                            @can('View WH Stock Transfer List')
                                <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">
                                    <a href="{{ route($base_route.'.index') }}" style="border-radius: 0px; " class="btn btn-sm btn-primary">
                                        <i class="fa fa-list"></i>
                                        List of {{formatWords($title,true)}}
                                    </a>
                                </div>
                            @endcan
                        </div>

                        <!-- /.box-header -->
                        @include("AdminWarehouse::layout.partials.flash_message")
                        @can('Create WH Stock Transfer')
                            <div class="box-body">
                                <form class="form-horizontal" role="form" action="{{route($base_route.'.store')}}" method="post">
                                    @csrf

                                    <div class="box-body">

                                        @include('AlpasalWarehouse::warehouse.warehouse-stock-transfer.partials.wh-stock-transfer-form')

                                    </div>
                                    <!-- /.box-body -->

                                    <div class="box-footer">
                                        <button type="submit" style="width: 49%;margin-left: 26%;" class="btn btn-block btn-primary">Add</button>
                                    </div>
                                </form>
                            </div>
                        @endcan
                    </div>
                    <!-- /.box -->
                </div>
                <!--ends column-->
            </div>
            <!-- ends row-->
        </section>
        <!--ends section-->
    </div>
@endsection
