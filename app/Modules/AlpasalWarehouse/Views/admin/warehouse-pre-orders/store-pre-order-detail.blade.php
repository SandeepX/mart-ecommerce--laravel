@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>$title,
    'sub_title'=> "Manage {$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route.'index'),
    ])

    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="row" style="margin: 5px;">
                            <div class="col-md-6 col-sm-12" style="float: left">
                                <strong> Warehouse :</strong> {{$warehousePreOrderListing->warehouse->warehouse_name}} <br/>
                                <strong>Vendor Name:</strong> {{$vendors->vendor_name}} <br/>
                                <strong> Pre-Order Name :</strong> {{$warehousePreOrderListing->pre_order_name}} <br/>
                                <strong> Pre-Order Setting :</strong> {{$warehousePreOrderListing->warehouse_preorder_listing_code}} <br/>
                            </div>
                            <div class="col-md-6 col-sm-12" style="float: right">
                                <strong> Start Date :</strong> {{getReadableDate($warehousePreOrderListing->start_time)}} <br/>
                                <strong> End Date:</strong> {{getReadableDate($warehousePreOrderListing->end_time)}}  <br/>
                                <strong> Finalization Date :</strong> {{getReadableDate($warehousePreOrderListing->finalization_time)}} <br/>
                            </div>
                            <hr>
                        </div>
                        <div class="panel-body">
                            @include(''.$module.'.admin.warehouse-pre-orders.common.store-order-product-filter-form')
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                              Finalized  Ordered Products  | Total Price
                                : {{ getNumberFormattedAmount($total_amount) }}
                                | Total PreOrder Price : {{ getNumberFormattedAmount($total_pre_order_rate) }}
                            </h3>
                        </div>
                        <div class="pull-right" style="margin-top: -35px;margin-left: 10px;">
                            <a href="{{ route('admin.warehouse-pre-orders.store-orders.in-vendor.export', [
                                $vendors->vendor_code,
                                $warehousePreOrderListing->warehouse_preorder_listing_code
                                ]) }}"
                               style="border-radius: 0px;"
                               class="btn btn-sm btn-success"
                            >
                                <i class="fa fa-file-excel-o"></i>
                                Download Excel File
                            </a>
                            <a href="{{ route('admin.warehouse-pre-orders.vendors-list',$warehousePreOrderListing->warehouse_preorder_listing_code) }}" style="border-radius: 0px; " class="btn btn-sm btn-info">
                                <i class="fa fa-plus-circle"></i>
                                Back To Vendor List
                            </a>
                        </div>



                        <div class="box-body">

                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product Name</th>
                                    <th>Ordered Package Type</th>
                                    <th>Quantity</th>
                                    <th>Unit Rate</th>
                                    <th>Product Price</th>
                                    <th>Unit PreOrder Rate</th>
                                    <th>PreOrder Rate</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($storePreOrderProducts as $i => $preOrder)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>{{$preOrder->product_name}}
                                            @if(isset($preOrder->product_variant_name))
                                                <span>({{$preOrder->product_variant_name}})</span>
                                            @else
                                                <span></span>
                                            @endif
                                        </td>
                                        <td>
                                            {{$preOrder->ordered_package_type ?? '-'}}
                                        </td>
                                        <td>
                                            {{$preOrder->total_ordered_quantity}}
                                            <button type="button" class="btn btn-primary store-order-qty-finalized-btn"
                                                    data-vendor-code="{{$vendors->vendor_code}}"
                                                    data-wplc-code="{{$warehousePreOrderListing->warehouse_preorder_listing_code}}"
                                                    data-product-code="{{$preOrder->product_code}}">
                                                {{$preOrder->total_ordered_quantity}}
                                            </button>

                                        </td>
                                        <td>{{getNumberFormattedAmount($preOrder->vendor_price)}}</td>
                                        <td>{{getNumberFormattedAmount($preOrder->sub_total)}}</td>
                                        <td>{{getNumberFormattedAmount($preOrder->unit_price)}}</td>
                                        <td>{{getNumberFormattedAmount($preOrder->pre_order_rate)}}</td>
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
                @include(''.$module.'.admin.warehouse-pre-orders.common.store-order-qty-modal')
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
    @push('scripts')
        @includeIf('AlpasalWarehouse::admin.warehouse-pre-orders.scripts.pre-order-product-filter');
        @includeIf('AlpasalWarehouse::admin.warehouse-pre-orders.scripts.store-order-qty');
    @endpush
@endsection
