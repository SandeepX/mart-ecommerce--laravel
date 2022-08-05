@extends('Admin::layout.common.masterlayout')

@section('content')
    <div class="content-wrapper">

        <section class="content">
            @include("Admin::layout.partials.flash_message")
            <div class="panel panel-default">
                <div class="panel-heading">Welcome, {{auth()->user()->name}}</div>
                <div class="panel-body">Allpasal</div>
            </div>
            <div class="row">

{{--                <div class="col-12">--}}
{{--                    <div class="panel panel-default">--}}
{{--                        <div class="panel-heading">--}}
{{--                            <h3 class="panel-title">Amount Report</h3>--}}
{{--                        </div>--}}
{{--                        <div class="panel-body">--}}
{{--                            <div class="col-md-3 col-sm-6 col-xs-12">--}}
{{--                                <div class="info-box">--}}
{{--                                    <span class="info-box-icon bg-aqua"><i class="fa fa-dollar"></i></span>--}}

{{--                                    <div class="info-box-content">--}}
{{--                                        <span class="info-box-text">Total Sales Amount</span>--}}
{{--                                        <span class="info-box-number">Rs.{{$totalSalesAmount}}</span>--}}
{{--                                    </div>--}}
{{--                                    <!-- /.info-box-content -->--}}
{{--                                </div>--}}
{{--                                <!-- /.info-box -->--}}
{{--                            </div>--}}
{{--                            <div class="col-md-3 col-sm-6 col-xs-12">--}}
{{--                                <div class="info-box">--}}
{{--                                    <span class="info-box-icon bg-red"><i class="fa fa-money"></i></span>--}}

{{--                                    <div class="info-box-content">--}}
{{--                                        <span class="info-box-text">Total Purchase</span>--}}
{{--                                        <span class="info-box-text">Amount</span>--}}
{{--                                        <span class="info-box-number">Rs.{{$totalPurchaseAmount}}</span>--}}
{{--                                    </div>--}}
{{--                                    <!-- /.info-box-content -->--}}
{{--                                </div>--}}
{{--                                <!-- /.info-box -->--}}
{{--                            </div>--}}
{{--                            <div class="col-md-3 col-sm-6 col-xs-12">--}}
{{--                                <div class="info-box">--}}
{{--                                    <span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>--}}

{{--                                    <div class="info-box-content">--}}
{{--                                        <span class="info-box-text">Total Store</span>--}}
{{--                                        <span class="info-box-text">Balance</span>--}}
{{--                                        <span class="info-box-number">Rs.{{$totalStoresBalance}}</span>--}}
{{--                                    </div>--}}
{{--                                    <!-- /.info-box-content -->--}}
{{--                                </div>--}}
{{--                                <!-- /.info-box -->--}}
{{--                            </div>--}}

{{--                            <div class="col-md-3 col-sm-6 col-xs-12">--}}
{{--                                <div class="info-box">--}}
{{--                                    <span class="info-box-icon bg-yellow"><i class="fa fa-industry"></i></span>--}}

{{--                                    <div class="info-box-content">--}}
{{--                                        <span class="info-box-text">Total Warehouses</span>--}}
{{--                                        <span class="info-box-text">Product Stock</span>--}}
{{--                                        <span class="info-box-number">{{$warehousesTotalProductStock}}</span>--}}
{{--                                    </div>--}}
{{--                                    <!-- /.info-box-content -->--}}
{{--                                </div>--}}
{{--                                <!-- /.info-box -->--}}
{{--                            </div>--}}

{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
            </div>
{{--            <div class="row">--}}
{{--                <div class="col-12">--}}
{{--                    <div class="panel panel-default">--}}
{{--                        <div class="panel-heading">--}}
{{--                            <h3 class="panel-title">Statistics</h3>--}}
{{--                        </div>--}}
{{--                        <div class="panel-body">--}}
{{--                            <div class="col-md-3 col-sm-6 col-xs-12">--}}
{{--                                <a href="{{route('admin.products.index')}}">--}}
{{--                                    <div class="info-box">--}}
{{--                                        <span class="info-box-icon bg-aqua"><i class="fa fa-product-hunt"></i></span>--}}

{{--                                        <div class="info-box-content">--}}
{{--                                            <span class="info-box-text">Products</span>--}}
{{--                                            <span class="info-box-number">{{$totalProducts}}</span>--}}
{{--                                        </div>--}}
{{--                                        <!-- /.info-box-content -->--}}
{{--                                    </div>--}}
{{--                                    <!-- /.info-box -->--}}
{{--                                </a>--}}

{{--                            </div>--}}
{{--                            <!-- /.col -->--}}
{{--                            <div class="col-md-3 col-sm-6 col-xs-12">--}}
{{--                                <a href="{{route('admin.vendors.index')}}">--}}
{{--                                    <div class="info-box">--}}
{{--                                        <span class="info-box-icon bg-red"><i class="fa fa-users"></i></span>--}}

{{--                                        <div class="info-box-content">--}}
{{--                                            <span class="info-box-text">Vendors</span>--}}
{{--                                            <span class="info-box-number">{{$totalVendors}}</span>--}}
{{--                                        </div>--}}
{{--                                        <!-- /.info-box-content -->--}}
{{--                                    </div>--}}
{{--                                    <!-- /.info-box -->--}}
{{--                                </a>--}}
{{--                            </div>--}}
{{--                            <!-- /.col -->--}}

{{--                            <!-- fix for small devices only -->--}}
{{--                            <div class="clearfix visible-sm-block"></div>--}}

{{--                            <div class="col-md-3 col-sm-6 col-xs-12">--}}
{{--                                <a href="{{route('admin.brands.index')}}">--}}
{{--                                    <div class="info-box">--}}
{{--                                        <span class="info-box-icon bg-green"><i class="fa fa-apple"></i></span>--}}

{{--                                        <div class="info-box-content">--}}
{{--                                            <span class="info-box-text">Brands</span>--}}
{{--                                            <span class="info-box-number">{{$totalBrands}}</span>--}}
{{--                                        </div>--}}
{{--                                        <!-- /.info-box-content -->--}}
{{--                                    </div>--}}
{{--                                    <!-- /.info-box -->--}}
{{--                                </a>--}}
{{--                            </div>--}}
{{--                            <!-- /.col -->--}}
{{--                            <div class="col-md-3 col-sm-6 col-xs-12">--}}
{{--                                <a href="{{route('admin.stores.index')}}">--}}
{{--                                    <div class="info-box">--}}
{{--                                        <span class="info-box-icon bg-yellow"><i class="fa fa-home"></i></span>--}}

{{--                                        <div class="info-box-content">--}}
{{--                                            <span class="info-box-text">Stores</span>--}}
{{--                                            <span class="info-box-number">{{$totalStores}}</span>--}}
{{--                                        </div>--}}
{{--                                        <!-- /.info-box-content -->--}}
{{--                                    </div>--}}
{{--                                    <!-- /.info-box -->--}}
{{--                                </a>--}}
{{--                            </div>--}}
{{--                            <!-- /.col -->--}}

{{--                            <div class="col-md-3 col-sm-6 col-xs-12">--}}
{{--                                <a href="{{route('admin.warehouses.index')}}">--}}
{{--                                    <div class="info-box">--}}
{{--                                        <span class="info-box-icon bg-aqua"><i class="fa fa-industry"></i></span>--}}

{{--                                        <div class="info-box-content">--}}
{{--                                            <span class="info-box-text">Warehouses</span>--}}
{{--                                            <span class="info-box-number">{{$totalWarehouses}}</span>--}}
{{--                                        </div>--}}
{{--                                        <!-- /.info-box-content -->--}}
{{--                                    </div>--}}
{{--                                    <!-- /.info-box -->--}}
{{--                                </a>--}}
{{--                            </div>--}}
{{--                            <!-- /.col -->--}}

{{--                            <div class="col-md-3 col-sm-6 col-xs-12">--}}
{{--                                <a href="{{route('admin.categories.index')}}">--}}
{{--                                    <div class="info-box">--}}
{{--                                        <span class="info-box-icon bg-red"><i class="fa fa-list-alt"></i></span>--}}

{{--                                        <div class="info-box-content">--}}
{{--                                            <span class="info-box-text">Product</span>--}}
{{--                                            <span class="info-box-text">Categories</span>--}}
{{--                                            <span class="info-box-number">{{$totalProductCategories}}</span>--}}
{{--                                        </div>--}}
{{--                                        <!-- /.info-box-content -->--}}
{{--                                    </div>--}}
{{--                                    <!-- /.info-box -->--}}
{{--                                </a>--}}
{{--                            </div>--}}
{{--                            <!-- /.col -->--}}

{{--                            <div class="col-md-3 col-sm-6 col-xs-12">--}}
{{--                                <div class="info-box">--}}
{{--                                    <span class="info-box-icon bg-green"><i class="fa fa-sort-amount-desc"></i></span>--}}

{{--                                    <div class="info-box-content">--}}
{{--                                        <span class="info-box-text">Total No. of Sales</span>--}}
{{--                                        <span class="info-box-text">Quantity</span>--}}
{{--                                        <span class="info-box-number">{{$totalNumberOfSalesQuantity}}</span>--}}
{{--                                    </div>--}}
{{--                                    <!-- /.info-box-content -->--}}
{{--                                </div>--}}
{{--                                <!-- /.info-box -->--}}
{{--                            </div>--}}
{{--                            <!-- /.col -->--}}

{{--                            <div class="col-md-3 col-sm-6 col-xs-12">--}}
{{--                                <div class="info-box">--}}
{{--                                    <span class="info-box-icon bg-yellow"><i class="fa fa-sort-amount-asc"></i></span>--}}

{{--                                    <div class="info-box-content">--}}
{{--                                        <span class="info-box-text">Total No. of</span>--}}
{{--                                        <span class="info-box-text">Purchase Quantity</span>--}}
{{--                                        <span class="info-box-number">{{$totalNumberOfPurchaseQuantity}}</span>--}}
{{--                                    </div>--}}
{{--                                    <!-- /.info-box-content -->--}}
{{--                                </div>--}}
{{--                                <!-- /.info-box -->--}}
{{--                            </div>--}}
{{--                            <!-- /.col -->--}}

{{--                            <div class="col-md-3 col-sm-6 col-xs-12">--}}
{{--                                <div class="info-box">--}}
{{--                                    <span class="info-box-icon bg-aqua"><i class="fa fa-shopping-basket"></i></span>--}}

{{--                                    <div class="info-box-content">--}}
{{--                                        <span class="info-box-text">Total Pending</span>--}}
{{--                                        <span class="info-box-text">Sales Order</span>--}}
{{--                                        <span class="info-box-number">{{$totalPendingSalesOrders}}</span>--}}
{{--                                    </div>--}}
{{--                                    <!-- /.info-box-content -->--}}
{{--                                </div>--}}
{{--                                <!-- /.info-box -->--}}
{{--                            </div>--}}
{{--                            <!-- /.col -->--}}

{{--                            <div class="col-md-3 col-sm-6 col-xs-12">--}}
{{--                                <div class="info-box">--}}
{{--                                    <span class="info-box-icon bg-red"><i class="fa fa-shopping-cart"></i></span>--}}

{{--                                    <div class="info-box-content">--}}
{{--                                        <span class="info-box-text">Total Pending</span>--}}
{{--                                        <span class="info-box-text">Purchase Order</span>--}}
{{--                                        <span class="info-box-number">{{$totalPendingPurchaseOrders}}</span>--}}
{{--                                    </div>--}}
{{--                                    <!-- /.info-box-content -->--}}
{{--                                </div>--}}
{{--                                <!-- /.info-box -->--}}
{{--                            </div>--}}
{{--                            <!-- /.col -->--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--            </div>--}}

        </section>

    </div>

@endsection
