@extends('Admin::layout.common.masterlayout')
@push('css')
    <style>
        .box-color {
            float: left;
            height: 20px;
            width: 20px;
            padding-top: 5px;
            border: 1px solid black;
        }
    </style>
@endpush
@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
   'page_title'=> 'Warehouse Dispatch Statement',
   'sub_title'=>'Manage '. formatWords($title,true),
   'icon'=>'home',
   'sub_icon'=>'',
   'manage_url'=>route('admin.wh-dispatch-statement.index'),
   ])
    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div id="showFlashMessage"></div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            @include(''.$module.'admin.wh-reporting.dispatch-statement.filter-form')
                        </div>
                    </div>

                </div>
                <div class="col-xs-12">
                    @include(''.$module.'admin.wh-reporting.dispatch-log-partials')
                </div>
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 id="report_title" class="panel-title">
                                Warehouse Dispatch Statement
                                / Warehouse : <span id="selectedWarehouse"></span>
                            </h3>
                            <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                <a class="btn btn-warning" id="download-excel">
                                    <i class="fa fa-file-archive-o"></i>
                                    Excel Download
                                </a>
                            </div>
                        </div>
                        <div id="tableForDispatchStatement">

                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
@endsection
@push('scripts')
    @includeIf('Reporting::admin.wh-reporting.dispatch-statement.statements-scripts');
@endpush




