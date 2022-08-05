@extends('AdminWarehouse::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('AdminWarehouse::layout.partials.breadcrumb',
    [
    'page_title'=>$title,
    'sub_title'=> "Manage {$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route('warehouse.warehouse-pre-orders.index'),
    ])


    <!-- Main content -->
        <section class="content">
            @include('AdminWarehouse::layout.partials.flash_message')
            <div class="row">
                <div class="col-xs-12">
                    @can('View List Of Vendors For Pre Orders')
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
                                <form action="{{route('warehouse.warehouse-pre-orders.vendors-list',$warehousePreOrderListingCode)}}" method="get">
                                    <div class="row">
                                        <div class="col-xs-12 col-md-3">
                                            <label for="vendor_name">Vendor</label>
                                            <input type="text" class="form-control" name="vendor_name" id="vendor_name"
                                                   value="{{$filterParameters['vendor_name']}}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-md-3">
                                            <div class="form-group" style="margin-top: 1rem">
                                                <button type="submit" class="btn btn-block btn-primary form-control">Filter</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endcan
                </div>

                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of Vendors
                            </h3>


                        </div>

                        @can('View List Of Vendors For Pre Orders')
                            <div class="box-body">

                                <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Vendor</th>
                                        <th>Vendor Code</th>
                                        <th>Estimated Ordered Products</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($vendors as $i => $vendor)
                                        <tr>
                                            <td>{{++$i}}</td>
                                            <td>{{$vendor->vendor_name}}</td>
                                            <td>{{$vendor->vendor_code}}</td>
                                            <td>{{$vendor->total_ordered_products}}</td>
                                            <td>
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Order Placement',route('warehouse.warehouse-pre-orders.place-order.get',['warehousePreOrderCode'=>$warehousePreOrderListingCode,'vendorCode'=>$vendor->vendor_code]),'View Order', 'plus','info')!!}
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

                                {{$vendors->appends($_GET)->links()}}
                            </div>
                        @endcan
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
@endsection
