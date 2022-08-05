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
   'page_title'=> formatWords($title,true),
   'sub_title'=>'Manage '. formatWords($title,true),
   'icon'=>'home',
   'sub_icon'=>'',
   'manage_url'=>route($base_route.'.index'),
   ])
    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div id="showFlashMessage"></div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default">

                        <div class="panel-body">
                            @include(''.$module.'admin.wh-reporting.filter-form')
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
                                Warehouse Dispatch Report
                            </h3>
                            <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                <a target="_blank" href="{{ route('admin.wh-dispatch-statement.index') }}" style="border-radius: 0px; " class="btn btn-sm btn-info">
                                    <i class="fa fa-list"></i>
                                    Dispatch Statement
                                </a>
                            </div>
                            <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                <a class="btn btn-warning" id="download-excel">
                                    <i class="fa fa-file-archive-o"></i>
                                    Excel Download
                                </a>
                            </div>
                        </div>

                       <div id="tableForProductsList">

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
    @includeIf('Reporting::admin.wh-reporting.report-scripts');
@endpush


