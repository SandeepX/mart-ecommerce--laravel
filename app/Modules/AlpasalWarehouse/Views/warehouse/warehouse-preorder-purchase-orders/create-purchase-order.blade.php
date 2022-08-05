@extends('AdminWarehouse::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
        @include("AdminWarehouse::layout.partials.breadcrumb",
        [
        'page_title'=>$title,
        'sub_title'=> "Create {$title}",
        'icon'=>'home',
        'sub_icon'=>'',
        'manage_url'=>route('warehouse.warehouse-pre-orders.vendors-list',$warehousePreOrderListingCode),
        ])

        <section class="content">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-primary">
                        <!-- /.box-header -->
                        @include("AdminWarehouse::layout.partials.flash_message")
                        @can('Place Order For Pre Order')
                            @include('AlpasalWarehouse::warehouse.warehouse-preorder-purchase-orders.partials.warehouse-preorderable-products-for-purchase-orders')
                        @endcan
                        @include('AlpasalWarehouse::warehouse.warehouse-preorder-purchase-orders.partials.warehouse-pre-order-purchased-details')
                    </div>
                    <!-- /.box -->
                </div>
                <!--/.col (left) -->
            </div>
            <!-- /.row -->
        </section>

    </div>

@endsection
@push('scripts')
    @include('AlpasalWarehouse::warehouse.warehouse-preorder-purchase-orders.create-order-script')
@endpush
