@extends('AdminWarehouse::layout.common.masterlayout')

@section('content')
<div class="content-wrapper">
{{--    @can('View Warehouse Dashboard')--}}
        <section id="app" class="content">
{{--            @can('View Warehouse Dashboard')--}}
{{--                @include("AdminWarehouse::layout.partials.flash_message")--}}
{{--                <div class="row">--}}
{{--                    <div class="col-md-3 col-sm-6 col-xs-12">--}}
{{--                        <div class="info-box">--}}
{{--                            <span class="info-box-icon bg-aqua"><i class="ion ion-ios-gear-outline"></i></span>--}}

{{--                            <div class="info-box-content">--}}
{{--                                <span class="info-box-text">Products</span>--}}
{{--                                <span class="info-box-number">{{$totalProducts}}</span>--}}
{{--                            </div>--}}
{{--                            <!-- /.info-box-content -->--}}
{{--                        </div>--}}
{{--                        <!-- /.info-box -->--}}
{{--                    </div>--}}
{{--                    <!-- /.col -->--}}
{{--                    <div class="col-md-3 col-sm-6 col-xs-12">--}}
{{--                        <div class="info-box">--}}
{{--                            <span class="info-box-icon bg-red"><i class="fa fa-users"></i></span>--}}

{{--                            <div class="info-box-content">--}}
{{--                                <span class="info-box-text">Vendors</span>--}}
{{--                                <span class="info-box-number">{{$totalVendors}}</span>--}}
{{--                            </div>--}}
{{--                            <!-- /.info-box-content -->--}}
{{--                        </div>--}}
{{--                        <!-- /.info-box -->--}}
{{--                    </div>--}}
{{--                    <!-- /.col -->--}}

{{--                    <!-- fix for small devices only -->--}}
{{--                    <div class="clearfix visible-sm-block"></div>--}}

{{--                    <div class="col-md-3 col-sm-6 col-xs-12">--}}
{{--                        <div class="info-box">--}}
{{--                            <span class="info-box-icon bg-green"><i class="ion ion-ios-cart-outline"></i></span>--}}

{{--                            <div class="info-box-content">--}}
{{--                                <span class="info-box-text">Brands</span>--}}
{{--                                <span class="info-box-number">{{$totalBrands}}</span>--}}
{{--                            </div>--}}
{{--                            <!-- /.info-box-content -->--}}
{{--                        </div>--}}
{{--                        <!-- /.info-box -->--}}
{{--                    </div>--}}
{{--                    <!-- /.col -->--}}
{{--                    <div class="col-md-3 col-sm-6 col-xs-12">--}}
{{--                        <div class="info-box">--}}
{{--                            <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>--}}

{{--                            <div class="info-box-content">--}}
{{--                                <span class="info-box-text">Stores</span>--}}
{{--                                <span class="info-box-number">{{$totalStores}}</span>--}}
{{--                            </div>--}}
{{--                            <!-- /.info-box-content -->--}}
{{--                        </div>--}}
{{--                        <!-- /.info-box -->--}}
{{--                    </div>--}}
{{--                    <!-- /.col -->--}}

{{--                    <div class="col-md-3 col-sm-6 col-xs-12">--}}
{{--                        <div class="info-box">--}}
{{--                            <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>--}}

{{--                            <div class="info-box-content">--}}
{{--                                <span class="info-box-text">Warehouses</span>--}}
{{--                                <span class="info-box-number">{{$totalWarehouses}}</span>--}}
{{--                            </div>--}}
{{--                            <!-- /.info-box-content -->--}}
{{--                        </div>--}}
{{--                        <!-- /.info-box -->--}}
{{--                    </div>--}}
{{--                    <!-- /.col -->--}}
{{--                </div>--}}
{{--            @endcan--}}
            <warehouse-dashboard :warehouse="{{$authWarehouse}}"></warehouse-dashboard>
        </section>
{{--    @endcan--}}
</div>

@endsection
