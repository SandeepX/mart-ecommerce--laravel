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
                        <div class="panel-body">
                            @include(''.$module.'admin.wh-current-stock.common.filter-form-product')
                        </div>
                        <div class="row" style="margin: 5px;">
                            <div class="col-md-6 col-sm-12" style="float: left">
                                <strong> Warehouse :</strong> {{$warehouse->warehouse_name}} <br/>
                                <strong>Vendor Name:</strong> {{$vendor->vendor_name}} <br/>
                            </div>
                            <hr>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List Of Vendor Wise Product Stock
                            </h3>
                        </div>
                        <div class="pull-right" style="margin-top: -35px;margin-left: 10px;">
                            <a href="{{route('admin.warehouse-wise.current-stock.exportExcellVendorWiseProductStockReport',[
                                        'warehouseCode'=>$warehouse->warehouse_code,
                                        'vendorCode'=>$vendor->vendor_code,
                                    ])}}"
                               style="border-radius: 0px;"
                               class="btn btn-sm btn-success"
                            >
                                <i class="fa fa-file-excel-o"></i>
                                Download Excel File
                            </a>
                            <a href="{{ route('admin.warehouse-wise.current-stock.warehouse.detail',$warehouse->warehouse_code) }}" style="border-radius: 0px; " class="btn btn-sm btn-info">
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
                                    <th>Current Stock</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($vendorWiseProducts as $i => $vendorWiseProduct)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>{{$vendorWiseProduct->product_name}}
                                            @if(isset($vendorWiseProduct->product_variant_name))
                                                <span>({{$vendorWiseProduct->product_variant_name}})</span>
                                            @else
                                                <span></span>
                                            @endif
                                        </td>
                                        @if(isset($vendorWiseProduct->product_packaging_detail))
                                            <td>
                                                <button type="button" class="btn btn-xs btn-primary">
                                                    {{$vendorWiseProduct->product_packaging_detail}}
                                                </button>
                                            </td>
                                        @else
                                            <td>
                                                -
                                            </td>
                                        @endif

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
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
    @push('scripts')
    @endpush
@endsection
