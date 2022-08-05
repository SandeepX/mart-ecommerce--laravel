@extends('AdminWarehouse::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('AdminWarehouse::layout.partials.breadcrumb', [
        'page_title' => $title,
        'sub_title' => "Delivery Detail of {$title}",
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

                            <h3 class="box-title">Delivery Detail of {{$title}}</h3>
                        </div>

                        <!-- /.box-header -->
                        @include("AdminWarehouse::layout.partials.flash_message")
                        @can('View Received WH Stock Transfer List')
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-xs-12 col-md-6">
                                        <div class="panel panel-primary">
                                            <div class="panel-heading">
                                                <h3 class="panel-title">
                                                    Delivery Detail
                                                </h3>

                                            </div>

                                            <div class="panel-body">
                                                <div class="box-body">
                                                    <table class="table table-bordered table-striped" cellspacing="0" width="100%" id="delivery-detail-tbl">
                                                        <thead>
                                                        <tr>
                                                            <th>Field</th>
                                                            <th>Value</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($warehouseStockTransferDeliveryDetail as $stockTransferMeta)
                                                                <tr>
                                                                    <td>{{ Str::ucfirst(str_replace('_', ' ', $stockTransferMeta->key)) }}</td>
                                                                    <td>{{ Str::ucfirst($stockTransferMeta->value) }}</td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="100%">
                                                                        <p class="text-center"><b>No records found!</b></p>
                                                                    </td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
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
        </section>
        <!--ends section-->
    </div>
@endsection

