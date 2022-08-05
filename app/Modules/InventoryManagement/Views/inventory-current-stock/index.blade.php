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
            <div id="showFlashMessage"></div>

            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            @include(''.$module.'inventory-current-stock.common.filter-form')
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of Inventory Current Stock Record
                            </h3>
                        </div>

                        <div class="pull-right" style="margin-top: -25px; margin-left: 10px;">
                            <a class="btn btn-info" id="refresh">
                                <i class="fa fa-refresh"></i>
                                Refresh
                            </a>
                        </div>

                        <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                            <a class="btn btn-success" id="download-excel">
                                <i class="fa fa-file-archive-o"></i>
                                Excel Download
                            </a>
                        </div>

                        <div id="tableForStoreCurrentStockQuantity">

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
    @includeIf('InventoryManagement::inventory-current-stock.common.inventory-current-stock-scripts');
@endpush
