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
                               <strong> Warehouse :</strong> {{$preOrder->warehouse->warehouse_name}} <br/>
                               <strong> Vendor Name :</strong> {{$vendors->vendor_name}} <br/>
                                <strong> Pre-Order Name :</strong> {{$preOrder->pre_order_name}} <br/>
                                <strong> Pre-Order Setting :</strong> {{$preOrder->warehouse_preorder_listing_code}} <br/>
                            </div>
                            <div class="col-md-6 col-sm-12" style="float: right">
                                <strong> Start Date :</strong> {{getReadableDate($preOrder->start_time)}} <br/>
                                <strong> End Date:</strong> {{getReadableDate($preOrder->end_time)}}  <br/>
                                <strong> Finalization Date :</strong> {{getReadableDate($preOrder->finalization_time)}} <br/>
                            </div>
                            <hr>
                        </div>
                        <div class="panel-body">
                            @include(''.$module.'.admin.warehouse-pre-orders.common.product-filter-form')
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of Products Added | Total Amount: {{getNumberFormattedAmount($total_amount)}}
                            </h3>
                        </div>
                        <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                            <a href="{{ route('admin.warehouse-pre-orders.vendors-list',$preOrder->warehouse_preorder_listing_code) }}" style="border-radius: 0px; " class="btn btn-sm btn-info">
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
                                    <th>Product Price</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($productsInPreOrder as $i => $preOrder)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>{{$preOrder->product_name}}
                                            @if(isset($preOrder->product_variant_name))
                                                <span>({{$preOrder->product_variant_name}})</span>
                                            @else
                                            <span></span>
                                            @endif
                                        </td>
                                        <td>{{getNumberFormattedAmount($preOrder->product_price)}}</td>
                                        <td>
                                            @if($preOrder->is_active)
                                                @php
                                                    $activeStatus = 'Deactivate';
                                                @endphp
                                                <span class="label label-success">On</span>
                                            @else
                                                @php
                                                    $activeStatus = 'Activate';
                                                @endphp
                                                <span class="label label-danger">Off</span>
                                            @endif
                                        </td>
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

                            {{$productsInPreOrder->appends($_GET)->links()}}
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
    @includeIf('AlpasalWarehouse::admin.warehouse-pre-orders.scripts.pre-order-product-filter');
@endpush
