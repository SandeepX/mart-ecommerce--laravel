@extends('Admin::layout.common.masterlayout')

@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
   'page_title'=> formatWords($title,false),
   'sub_title'=>'Manage '. formatWords($title,false),
   'icon'=>'home',
   'sub_icon'=>'',
   'manage_url'=>route($base_route.'index'),
   ])
        <style>
            .pagination {
                width: 100% !important;
            }

            /*table {*/
            /*    display: block;*/
            /*    overflow-x: auto;*/
            /*    white-space: nowrap;*/
            /*}*/

            .unavaiable-stock {
                background-color: darksalmon;
            }

        </style>
    <!-- Main content -->
        <section class="content">
            <div class="row">

                <div class="col-xs-12">
                    <div class="panel-group">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <strong >
                                    FILTER DEMAND PROJECTION RECORDS
                                </strong>

                                <div class="btn-group pull-right" role="group" aria-label="...">
                                    <button style="margin-top: -5px;" data-toggle="collapse" data-target="#filter" type="button" class="btn btn-sm">
                                        <strong>Filter</strong> <i class="fa fa-filter"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="panel-body" >
                                <div class="panel panel-default">
                                    <div class="{{(isset($filterParameters) && !empty($filterParameters))?'':'collapse'}}" id="filter">
                                        <div class="panel-body" >
                                            <form action="{{route('admin.demand-projection.index')}}" method="get">

                                                <div class="col-xs-3">
                                                    <div class="form-group">
                                                        <label for="payment_for">Warehouse</label>
                                                        <select name="warehouse_code" class="form-control" id="warehouse_code">
                                                            @foreach($warehouse as $key => $value)
                                                                <option value="{{$value['warehouse_code']}}"
                                                                    {{$value['warehouse_code'] == $filterParameters['warehouse_code'] ? 'selected' :''}}>
                                                                    {{ucwords($value['warehouse_name'])}}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-xs-3">
                                                    <div class="form-group">
                                                        <label for="vendor_code">Vendors</label>
                                                        <select name="vendor_code[]" class="form-control select2" id="vendor_code" multiple>
                                                            @foreach($vendors as $key => $value)
                                                                <option value="{{$value['vendor_code']}}"
                                                                   @if($filterParameters['vendor_code']) {{in_array($value['vendor_code'],$filterParameters['vendor_code']) ? 'selected' :''}} @endif>
                                                                    {{ucwords($value['vendor_name'])}}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-xs-3">
                                                    <div class="form-group">
                                                        <label for="">Product Name</label>
                                                        <input type="text"  class="form-control" name="product_name" id="product_name"
                                                               value="{{($filterParameters['product_name'])}}">
                                                    </div>
                                                </div>

                                                <div class="col-xs-3">
                                                    <div class="form-group">
                                                        <label for="">Product Variant Name</label>
                                                        <input type="text" class="form-control" name="product_variant_name" id="product_variant_name"
                                                               value="{{($filterParameters['product_variant_name'])}}">
                                                    </div>
                                                </div>

                                                <div class="col-xs-3">
                                                    <div class="form-group">
                                                        <label for="">Records Per Page</label>
                                                        <input type="number" min="50" step="50" class="form-control" name="limit" id="limit"
                                                               value="{{($filterParameters['limit'])}}">
                                                    </div>
                                                </div>

                                                <div class="col-xs-3">
                                                    <div class="form-group" style="padding-top: 25px;">
                                                        <button  type="submit" id="submit" class="btn btn-block btn-info form-control">Filter</button>
                                                    </div>
                                                </div>

                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                Warehouse Stock Demand Projection of Warehouse:({{($filterParameters['warehouse_code'])}})
                            </h3>
                            <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                <a class="btn btn-warning btn-sm" id="download-excel">
                                    <i class="fa fa-file-archive-o"></i>
                                    Excel Download
                                </a>
                            </div>
                        </div>

                        <div class=" box-body" >
                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product Name</th>
                                    <th>Variant Name</th>
{{--                                    <th>Vendor Name</th>--}}
                                    <th>Purchase Qty</th>
                                    <th>Received Qty</th>
                                    <th>Dispatched Qty(normal)</th>
                                    <th>Dispacthed Qty(preorder)</th>
                                    <th>Stock Transfer Qty</th>
                                    <th>Normal Order Demand Qty</th>
                                    <th>Preorder Demand Qty </th>
                                    <th>Actual Stock</th>
                                    <th>Demand Stock</th>
                                    <th>Demand Projection</th>
                                </tr>
                                </thead>
                                <tbody>

                                @forelse($demandProjection as $i => $datum)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>{{$datum->product_name}}({{$datum->product_code}})</td>
                                        <td>{{($datum->product_variant_name)? $datum->product_variant_name:"N/A"}} </td>
{{--                                        <td>{{$datum->vendor_name}}({{$datum->vendor_code}}) </td>--}}
                                        <td>{{($datum->total_purchase_qty)? $datum->total_purchase_qty:0}}</td>
                                        <td>{{($datum->total_received_qty)? $datum->total_received_qty:0}}</td>
                                        <td>{{($datum->normal_order_dispacthed_qty)? $datum->normal_order_dispacthed_qty:0}}</td>
                                        <td>{{($datum->pre_order_dispatched_qty)? $datum->pre_order_dispatched_qty:0}}</td>
                                        <td>{{($datum->total_stock_transfer_qty)? $datum->total_stock_transfer_qty:0}}</td>
                                        <td>{{($datum->normal_order_demand_qty)? $datum->normal_order_demand_qty:0}}</td>
                                        <td>{{($datum->demand_preorder_qty)? $datum->demand_preorder_qty:0}}</td>
                                        <td>{{($datum->actual_stock)? $datum->actual_stock:0}}</td>
                                        <td>{{($datum->demand_stock)? $datum->demand_stock:0}}</td>
                                        <td class="{{(($datum->demand_projection) && $datum->demand_projection < 0) ? 'unavaiable-stock':''}}" >{{($datum->demand_projection)? $datum->demand_projection:0}}</td>
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

                            <ul class="pagination">
                                <?php
                                    $start_page = 1;
                                    $next_page_no = $page + 1;
                                    $previous_page_no = $page - 1;
                                    $total_number_of_pages = $filterParameters['total_no_of_pages'];
                                ?>
                                    @if($page < $total_number_of_pages)
                                        <li>
                                            <a href="{{route('admin.demand-projection.index')}}?page={{$next_page_no}}&warehouse_code={{$filterParameters['warehouse_code']}}&product_name={{$filterParameters['product_name']}}&product_variant_name={{$filterParameters['product_variant_name']}}&limit={{$filterParameters['limit']}}
                                                ">
                                                <button class="btn btn-success btn-sm">Next</button></a>
                                        </li>
                                    @endif

                                    @for ($i = $start_page; $i <= $total_number_of_pages; $i++)
                                        <li>
                                            <a href="{{route('admin.demand-projection.index')}}?page={{$i}}&warehouse_code={{$filterParameters['warehouse_code']}}&product_name={{$filterParameters['product_name']}}&product_variant_name={{$filterParameters['product_variant_name']}}&limit={{$filterParameters['limit']}}
                                                ">
                                                <button class="btn btn-{{($page==$i)?'danger':'info'}} btn-sm">{{$i}}</button></a>
                                        </li>
                                    @endfor

                                    @if($page > 1)
                                        <li style="float: right">
                                            <a href="{{route('admin.demand-projection.index')}}?page={{ $previous_page_no }}&warehouse_code={{$filterParameters['warehouse_code']}}&product_name={{$filterParameters['product_name']}}&product_variant_name={{$filterParameters['product_variant_name']}}&limit={{$filterParameters['limit']}}
                                                ">
                                                <button class="btn btn-success btn-sm">Previous</button></a>
                                        </li>
                                    @endif
                                </ul>
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
    <script>
        function productsFilterParams(){
            let params = {
                warehouse_code: $('#warehouse_code').val(),
                vendor_code: $('#vendor_code').val(),
                product_name: $('#product_name').val(),
                product_variant_name: $('#product_variant_name').val(),
                limit: $('#limit').val(),
            }
            return params;
        }

        $('#download-excel').on('click',function (e){
            e.preventDefault();
            var filterd_params = productsFilterParams();
            filterd_params.download_excel = true;
            var queryString = $.param(filterd_params);
            var url = "{{ route('admin.demand-projection.index') }}"+'?'+queryString;
            window.open(url,'_blank');
        });
    </script>

@endpush
