@extends('Admin::layout.common.masterlayout')
@push('css')
    <style>
        .box-color {
            float: left;
            height: 20px;
            width: 20px;
            padding-top: 5px;
            border: 1px solid black;
        }
    </style>
@endpush
@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
   'page_title'=> formatWords($title,true),
   'sub_title'=>'Manage '. formatWords($title,true),
   'icon'=>'home',
   'sub_icon'=>'',
   'manage_url'=>route($base_route.'.index'),
   ])
    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div id="showFlashMessage"></div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <form id="filter_form" action="#" method="GET">
                                <div class="row">
                                    <div class="col-lg-4 col-md-4">
                                        <div class="form-group">
                                            <label for="store">Store</label>
                                            <select id="store" name="store_code" class="form-control select2">
                                                <option value="" >All</option>
                                                @foreach($stores as $store)
                                                <option value="{{$store->store_code}}" {{($store->store_code == $filterParameters['store_code'] ? 'selected' : '')}}>{{$store->store_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3">
                                        <div class="form-group">
                                            <label for="from_date" class="control-label">Order Date From</label>
                                            <input type="date" class="form-control" name="from_date" id="from_date" value="{{$filterParameters['from_date']}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3">
                                        <div class="form-group">
                                            <label for="to_date">Order Date To</label>
                                            <input type="date" class="form-control" name="to_date" id="to_date" value="{{$filterParameters['to_date']}}">
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="product_variant_code" value="{{$filterParameters['product_variant_code']}}">
                                <div class="row">
                                    <div class="col-lg-3 col-md-3">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-block btn-primary form-control" style="margin-top: 24px;">Filter</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
                <div class="col-xs-12">
                    @include(''.$module.'admin.wh-reporting.dispatch-log-partials')
                </div>
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 id="report_title" class="panel-title">
                                Warehouse Dispatch Report
                                / Warehouse Name: {{$warehouseName}}
                                / Product Name : {{$productName}}
                                @if($productVariantName) ({{$productVariantName}}) @endif
                            </h3>
                            <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                <a class="btn btn-warning" id="download-excel">
                                    <i class="fa fa-file-archive-o"></i>
                                    Excel Download
                                </a>
                            </div>
                        </div>

                            <div class="box-body">
                                <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th rowspan="2">#</th>
                                        <th rowspan="2">Store Name</th>
                                        <th colspan="2" class="text-center">Normal Order</th>
                                        <th colspan="2" class="text-center">Pre Order</th>
                                        <th colspan="2" class="text-center">Total Order</th>
                                        <th rowspan="2">Action</th>
                                    </tr>
                                    <tr>
                                        <th>Quantity</th>
                                        <th>Amount</th>
                                        <th>Quantity</th>
                                        <th>Amount</th>
                                        <th>Quantity</th>
                                        <th>Amount</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($dispatchOrdersReportStoreWise as $key =>$stores)
                                        <tr>
                                            <td>{{$loop->index + 1}}</td>
                                            <td>
                                                {{$stores->store_name}}
                                            </td>
                                            <td><span class="label label-success">{{isset($stores->normal_order_packaging_qty) ? $stores->normal_order_packaging_qty : 'N/A'}}</span></td>
                                            <td>{{isset($stores->normal_order_amount) ? getNumberFormattedAmount($stores->normal_order_amount) : 'N/A'}}</td>
                                            <td><span class="label label-info">{{isset($stores->pre_order_packaging_qty) ? $stores->pre_order_packaging_qty : 'N/A'}}</span></td>
                                            <td>{{isset($stores->pre_order_amount) ? getNumberFormattedAmount($stores->pre_order_amount) : 'N/A'}}</td>
                                            <td><span class="label label-primary">{{isset($stores->total_packaging_qty) ? $stores->total_packaging_qty : 'N/A'}}</span></td>
                                            <td>{{isset($stores->total_amount) ? getNumberFormattedAmount($stores->total_amount) : 'N/A'}}</td>
                                            <td>
                                                 <a href="{{route('admin.wh-dispatch-report.product.store.statement',
                                                            [
                                                             'warehouseCode' => $filterParameters['warehouse_code'],
                                                             'productCode' => $filterParameters['product_code'],
                                                             'storeCode' => $stores->store_code,
                                                             'product_variant_code' => $filterParameters['product_variant_code']
                                                            ]
                                                            )}}" class="btn btn-info btn-xs"><i class="fa fa-eye"></i>View Details</a>
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
                                {{$dispatchOrdersReportStoreWise->appends($_GET)->links()}}
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
                store_code: $('#store').val(),
                from_date: $('#from_date').val(),
                to_date: $('#to_date').val(),
                page : 1
            }
            return params;
        }
        $('#download-excel').on('click',function (e){
            e.preventDefault();
            var filterd_params = productsFilterParams();
            filterd_params.download_excel = true;
            var queryString = $.param(filterd_params)
            var url = "{{ route('admin.wh-dispatch-report.product.stores-lists',
                                [
                                    'warehouseCode'=>$filterParameters['warehouse_code'],
                                    'productCode'=>$filterParameters['product_code']
                                ]) }}"+'?'+'product_variant_code='+'{{$filterParameters['product_variant_code']}}&'+queryString;
            window.open(url,'_blank');
        });
    </script>
@endpush



