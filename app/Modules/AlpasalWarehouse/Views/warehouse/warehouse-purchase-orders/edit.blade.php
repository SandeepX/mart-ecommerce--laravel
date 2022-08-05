@extends('AdminWarehouse::layout.common.masterlayout')

@section('content')
    <div class="content-wrapper">
        @include("AdminWarehouse::layout.partials.breadcrumb",
        [
        'page_title'=>$title,
        'sub_title'=> "Create {$title}",
        'icon'=>'home',
        'sub_icon'=>'',
        'manage_url'=>route($base_route.'index')
        ])

        @can('Add New WH Purchase Order')
            <section class="content">
                <div class="row">
                    @include("AdminWarehouse::layout.partials.flash_message")
                    <div id="showFlashMessage"></div>

                    <!-- left column purchase order form-->
                    <div class="col-md-8">
                        @include('AlpasalWarehouse::warehouse.warehouse-purchase-orders.form_partials.purchase-order-form')
                    </div>
                    <!--/.col (left) purchase order form-->

                    <!-- right column product_filter -->
                    <div class="col-md-4">
                        @include('AlpasalWarehouse::warehouse.warehouse-purchase-orders.form_partials.product-filter')

                    </div>
                    <!--/.col (right) product_filter -->
                </div>
                <!-- /.row -->
            </section>
        @endcan
    </div>

@endsection
@push('scripts')
    @include('AlpasalWarehouse::warehouse.warehouse-purchase-orders.scripts.purchase-order-create-script')
@endpush
