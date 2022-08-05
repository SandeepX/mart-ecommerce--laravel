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
    @include('AdminWarehouse::layout.partials.breadcrumb',
    [
    'page_title'=>$title,
    'sub_title'=> "Manage {$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route.'index'),
    ])


    <!-- Main content -->
        <section class="content">
            @include('AdminWarehouse::layout.partials.flash_message')
            <div class="row">
                <div class="col-xs-12">
                    @can('View Added Products Of WH Pre Order')
                        <div class="panel panel-default">

                            <div class="panel-body">
                                <div class="row" style="margin: 5px;">
                                    <div class="col-md-6 col-sm-12" style="float: left">
                                        <strong> Warehouse :</strong> {{$warehousePreOrder->warehouse->warehouse_name}} <br/>
                                        <strong> Pre-Order Name :</strong> {{$warehousePreOrder->pre_order_name}} <br/>
                                        <strong> Pre-Order Setting :</strong> {{$warehousePreOrder->warehouse_preorder_listing_code}} <br/>
                                    </div>
                                    <div class="col-md-6 col-sm-12" style="float: right">
                                        <strong> Start Date :</strong> {{getReadableDate($warehousePreOrder->start_time)}} <br/>
                                        <strong> End Date:</strong> {{getReadableDate($warehousePreOrder->end_time)}}  <br/>
                                        <strong> Finalization Date :</strong> {{getReadableDate($warehousePreOrder->finalization_time)}} <br/>
                                        <strong> Vendor Name :</strong> {{$vendor->vendor_name}} <br/>
                                    </div>
                                    <hr>
                                </div>

                                <form action="{{route('warehouse.warehouse-pre-orders.products.index',
                                                  ['warehousePreOrderCode'=>$warehousePreOrder->warehouse_preorder_listing_code,
                                                  'vendorCode'=>$vendor->vendor_code]
                                     )}}" method="get">
                                    <div class="col-xs-8">

                                        <label for="product_name">Product</label>
                                        <input type="text" class="form-control" name="product_name" id="product_name"
                                               value="{{$filterParameters['product_name']}}">
                                    </div>
                                    <br>
                                    <div class="col-xs-4">
                                        <button type="submit" class="btn btn-block btn-primary form-control">Filter</button>
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
                                List of Pre-Order Products
                            </h3>


                            @if(!$warehousePreOrder->isPastFinalizationTime())
                                @can('Add Products To WH Pre Order')
                                    <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">

                                        <a href="{{route('warehouse.warehouse-pre-orders.add-products',$warehousePreOrder->warehouse_preorder_listing_code)}}" style="border-radius: 0px; " class="btn btn-sm btn-info">
                                            <i class="fa fa-plus-circle"></i>
                                            Add Products
                                        </a>
                                    </div>
                                @endcan
                            @endif

                        </div>

                        @can('View Added Products Of WH Pre Order')
                            <div class="box-body">

                                <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product</th>
                                        <th>Status</th>
                                        <th>View Price</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @forelse($warehousePreOrderProducts as $preOrderProduct)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$preOrderProduct->product->product_name}}</td>
                                            <td>
                                                @can('Change Status Of Variants Of Product')
                                                    @if($preOrderProduct->total_active_product > 0)
                                                        <a href="{{route('warehouse.warehouse-pre-orders.products.variants.toggle-status',[
                                                                 'warehousePreOrderListingCode'=>$preOrderProduct['warehouse_preorder_listing_code'],
                                                                 'productCode'=>$preOrderProduct['product_code'],
                                                                 'status'=>'inactive'
                                                                 ])
                                                                 }}  ">
                                                            <label class="switch">
                                                                <input type="checkbox" value="on" class="change-status" checked>
                                                                <span class="slider round"></span>
                                                            </label>
                                                        </a>
                                                    @else
                                                        <a href="{{route('warehouse.warehouse-pre-orders.products.variants.toggle-status',[
                                                             'warehousePreOrderListingCode'=>$preOrderProduct['warehouse_preorder_listing_code'],
                                                             'productCode'=>$preOrderProduct['product_code'],
                                                             'status'=>'active'
                                                             ])
                                                             }}">
                                                            <label class="switch">
                                                                <input type="checkbox" value="off" class="change-status">
                                                                <span class="slider round"></span>
                                                            </label>
                                                        </a>
                                                    @endif
                                                @endcan
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm view-variant-btn"
                                                        data-wpop-code="{{$preOrderProduct['warehouse_preorder_product_code']}}"
                                                        data-wpol-code="{{$preOrderProduct['warehouse_preorder_listing_code']}}"
                                                        data-product-code="{{$preOrderProduct['product_code']}}"
                                                >
                                                    Price
                                                </button>
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
                                @if(isset($warehousePreOrderProducts))
                                    {{$warehousePreOrderProducts->appends($_GET)->links()}}
                                @endif

                            </div>
                        @endcan
                    </div>

                    @include('AlpasalWarehouse::warehouse.warehouse-pre-orders.show-products-partials.price-view-modal')

                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
@endsection
@push('scripts')
    @include('AlpasalWarehouse::warehouse.warehouse-pre-orders.show-products-partials.show-products-script')
    <script>
        $(document).ready(function (){
            $('.change-status').on('change',function (event){
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
