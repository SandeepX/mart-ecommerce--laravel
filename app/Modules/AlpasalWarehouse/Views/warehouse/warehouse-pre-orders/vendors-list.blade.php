@extends('AdminWarehouse::layout.common.masterlayout')
@section('content')
    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 25px;
        }
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #F21805;
            -webkit-transition: .4s;
            transition: .4s;
        }
        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }
        input:checked + .slider {
            background-color: #50C443;
        }
        input:focus + .slider {
            box-shadow: 0 0 1px #50C443;
        }
        input:checked + .slider:before {
            -webkit-transform: translateX(35px);
            -ms-transform: translateX(35px);
            transform: translateX(35px);
        }
        /* Rounded sliders */
        .slider.round {
            border-radius: 25px;
        }
        .slider.round:before {
            border-radius: 50%;
        }
    </style>
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
                    @can('View Added Products Of WH Pre Order')
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
                                    <div class="row">
                                        <div class="col-xs-12 col-md-3">
                                            <label for="vendor_name">Vendor</label>
                                            <input type="text" class="form-control" name="vendor_name" id="vendor_name"
                                                   value="{{$filterParameters['vendor_name']}}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-md-3">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-block btn-primary form-control" style="margin-top: 2rem">Filter</button>
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
                                List Of Vendors
                            </h3>
                            @can('View List Of WH Pre Orders')
                                <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                    <a href="{{ route($base_route .'index') }}" style="border-radius: 0px; " class="btn btn-sm btn-info">
                                        <i class="fa fa-plus-circle"></i>
                                        Back To WarehousePre Order List
                                    </a>
                                </div>
                            @endcan
                        </div>

                        @can('View Added Products Of WH Pre Order')
                            <div class="box-body">
                                <table  class="table table-bordered table-striped" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Vendor</th>
                                        <th>Vendor Code</th>
                                        <th>Total Produts</th>
                                        <th>Product Status</th>
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
                                                  @can('Change Status Of Products Of Vendor')
                                                      @if($vendor->total_active_products < 1)
                                                        <a href="{{route(
                                                              'warehouse.warehouse-pre-orders.products.vendors.toggle-status',[
                                                              'warehousePreOrderListingCode'=>$warehousePreOrderListingCode,
                                                              'vendorCode'=>$vendor->vendor_code,
                                                              'status'=>'active'
                                                                ])}}" >
                                                            <label class="switch">
                                                                <input type="checkbox" value="off" class="change-status-product">
                                                                <span class="slider round"></span>
                                                            </label>
                                                        </a>
                                                      @else
                                                          <a href="{{route(
                                                              'warehouse.warehouse-pre-orders.products.vendors.toggle-status',[
                                                              'warehousePreOrderListingCode'=>$warehousePreOrderListingCode,
                                                              'vendorCode'=>$vendor->vendor_code,
                                                              'status'=>'inactive'
                                                                ])}}">
                                                              <label class="switch">
                                                                  <input type="checkbox" value="on" class="change-status-product" checked>
                                                                  <span class="slider round"></span>
                                                              </label>
                                                          </a>
                                                      @endif
                                                  @endcan
                                              </td>
                                            <td>
                                                @can('View Added Products Of WH Pre Order')
                                                     {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('All Products ',route('warehouse.warehouse-pre-orders.products.index',[
                                                      'warehousePreOrderCode'=>$warehousePreOrderListingCode,
                                                      'vendorCode'=>$vendor->vendor_code
                                                      ]),'View', 'eye','info')!!}
                                                @endcan
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
                        @endcan
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
        <!-- /.content -->
    </div>
@endsection
@push('scripts')
<script>
    $(document).ready(function (){
        $('.change-status-product').on('change',function (event){
            event.preventDefault();
            let current = $(this).val();
            Swal.fire({
                title: 'Do you Want To Change Status?',
                showCancelButton: true,
                confirmButtonText: `Change`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    window.location.href = $(this).closest('a').attr('href');

                } else {
                    if (current === 'on') {
                        $(this).prop('checked', true);
                    } else if (current === 'off') {
                        $(this).prop('checked', false);
                    }
                }
            });
        });
    });
</script>
@endpush
