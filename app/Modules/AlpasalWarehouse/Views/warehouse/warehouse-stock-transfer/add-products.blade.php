@extends('AdminWarehouse::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('AdminWarehouse::layout.partials.breadcrumb', [
        'page_title' => $title,
        'sub_title' => "Add Products {$title}",
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

                            <h3 class="box-title">Add Products {{$title}}</h3>
                        </div>

                        <!-- /.box-header -->
                        @include("AdminWarehouse::layout.partials.flash_message")
                        <div id="showFlashMessage"></div>
                        @can('Create WH Stock Transfer')
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-xs-12 col-md-5">
                                        <div class="panel panel-default">

                                            <div class="panel-body">
                                                <form id="product-filter-form">
                                                    @include("AlpasalWarehouse::warehouse.warehouse-stock-transfer.partials.add-products-filter-form")
                                                </form>
                                            </div>
                                        </div>
                                        <div id="product-table">

                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-md-7">

                                        <form class="form-horizontal"
                                              role="form"
                                              id="product-save-as-draft-form"
                                              action="{{route($base_route.'.add-products-stock-transfer-details', $warehouseStockTransfer->stock_transfer_master_code)}}"
                                              method="post"
                                        >
                                            @csrf

                                            <div class="col-xs-12">
                                                <div class="panel panel-primary">
                                                    <div class="panel-heading">
                                                        <h3 class="panel-title">
                                                            List of Selected Products
                                                        </h3>

                                                    </div>

                                                    @include('AlpasalWarehouse::warehouse.warehouse-stock-transfer.partials.includes.added-products-table')

                                                    <div class="box-footer">
                                                        <button type="submit" style="width: 100%;" class="btn btn-lg btn-primary">Send</button>
{{--                                                        <button type="submit" style="width: 49%; float: right" class="btn btn-lg btn-success" id="save_products_as_draft">Save as Draft</button>--}}
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        @endcan
                    </div>
                    <!-- /.box -->
                </div>
                <!--ends column-->
            </div>
            <!-- ends row-->
            @include('AlpasalWarehouse::warehouse.warehouse-stock-transfer.partials.stock-transfer-modal')
        </section>
        <!--ends section-->
    </div>
@endsection
@push('scripts')
    @include('AlpasalWarehouse::warehouse.warehouse-stock-transfer.partials.stock-transfer-script-latest')
@endpush
