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
                            <form action="{{route('admin.warehouse-pre-orders.vendors-list',$warehousePreOrderListingCode)}}" method="get">
                                <div class="col-xs-3">
                                    <label for="vendor_name">Vendor</label>
                                    <input type="text" class="form-control" name="vendor_name" id="vendor_name"
                                           value="{{$filterParameters['vendor_name']}}">
                                </div>

                                <br><br>
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-block btn-primary form-control">Filter</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                             List Of Vendors
                            </h3>

                            <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                <a href="{{ route($base_route .'show',$warehousePreOrderListing->warehouse_code) }}" style="border-radius: 0px; " class="btn btn-sm btn-info">
                                    <i class="fa fa-plus-circle"></i>
                                    Back To WarehousePre Order List
                                </a>
                            </div>
                        </div>


                        <div class="box-body">

                            <table  class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Vendor</th>
                                    <th>Vendor Code</th>
                                    <th>Total Produts</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($vendors as $i => $vendor)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>{{$vendor->vendor_name}}</td>
                                        <td>{{$vendor->vendor_code}}</td>
                                        <td>{{$vendor->total_products}}</td>
                                        <td>
                                            {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Ordered Products',route('admin.warehouse-pre-orders.store-orders.in-vendor',[
                                             'vendorCode'=>$vendor->vendor_code,
                                             'warehousePreOrderListingCode'=>$warehousePreOrderListingCode
                                             ]),'View', 'eye','primary')!!}

                                            {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Finalized Products',route('admin.warehouse-pre-orders.finalized-store-orders.in-vendor',[
                                             'vendorCode'=>$vendor->vendor_code,
                                             'warehousePreOrderListingCode'=>$warehousePreOrderListingCode
                                             ]),'View', 'eye','primary')!!}

                                            {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('All Products ',route('admin.warehouse-pre-orders.pre-order-detail',[
                                              'vendorCode'=>$vendor->vendor_code,
                                              'warehousePreOrderListingCode'=>$warehousePreOrderListingCode
                                              ]),'View', 'eye','info')!!}

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


                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->





    <!-- /.content -->
    </div>
@endsection
