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
        </style>
        <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div id="showFlashMessage"></div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <form action="{{route('admin.rejected-item-report.product.stores-lists',
                                                ['warehouseCode'=>$filterParameters['warehouse_code'],
                                                 'productCode'=>$filterParameters['product_code']
                                                ])
                                                }}" method="GET">
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
                                <input type="hidden" name="product_variant_code"
                                       value="{{$filterParameters['product_variant_code']}}">
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
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 id="report_title" class="panel-title">
                                Last Sync Date
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div class="col-md-6">
                                Last Sync Normal Order Rejected Item Date : {{$lastDispatchSyncData['normalOrder']['date']}}<br/>
                                Status :  {{$lastDispatchSyncData['normalOrder']['status']}}
                            </div>
                            <div class="col-md-6">
                                Last Sync PreOrder Rejected Item Date : {{$lastDispatchSyncData['preOrder']['date']}} <br/>
                                Status :  {{$lastDispatchSyncData['preOrder']['status']}}
                            </div>
                        </div>
                    </div>
                </div>



                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                          Warehouse: {{$warehouseName}} / Rejected Product Name: {{$productName}}
                                {{$productVariantName }}  Report /(store wise)
                            </h3>
                            <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                <a href="{{route('admin.wh-rejected-item-store-wise-excel-export',[
                                                'warehouseCode'=>$filterParameters['warehouse_code'],
                                                'productCode'=>$filterParameters['product_code'],
                                                'product_variant_code'=> $filterParameters['product_variant_code']
                                             ])
                                         }}" style="border-radius: 0px; " class="btn btn-sm btn-success excel-export-storewise">
                                    <i class="fa fa-file"></i>
                                    Excel Export
                                </a>
                            </div>
                        </div>

                        <div class=" box-body" >
                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th rowspan="2">#</th>
                                    <th rowspan="2">Store Name</th>
                                    <th colspan="2" class="text-center">Normal Order</th>
                                    <th colspan="2" class="text-center">Pre Order</th>
                                    <th colspan="2" class="text-center">Total Rejected</th>
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
                                @forelse($rejectedItemReportStoreWise as $key => $value)
                                    <tr>
                                        <td>{{++$key}}</td>
                                        <td>
                                            {{$value->store_name}}
                                            {{($value->store_code) ? '('.$value->store_code.')':''}}
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">
                                               {{($value->total_normal_rejected_packaging_qty)? $value->total_normal_rejected_packaging_qty:0}}
                                            </span>
                                        </td>
                                        <td>Rs.{{($value->total_normal_rejected_price)? $value->total_normal_rejected_price:0}}</td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                {{($value->total_preorder_rejected_packaging_qty)? $value->total_preorder_rejected_packaging_qty:0}}
                                            </span>
                                        </td>
                                        <td>Rs.{{($value->total_preorder_rejected_price)? $value->total_preorder_rejected_price:0}}</td>

                                        <td>
                                            <span class="badge bg-secondary">
                                               {{($value->total_packaging_qty)? $value->total_packaging_qty:0}}
                                            </span>
                                        </td>
                                        <td>Rs.{{($value->total_rejected_price)? $value->total_rejected_price:0}}</td>

                                        <td>
                                            <a href="{{route('admin.wh-rejected-item-report.detail-report',
                                                            [
                                                             'warehouseCode' => $filterParameters['warehouse_code'],
                                                             'productCode' => $filterParameters['product_code'],
                                                             'storeCode' => $value->store_code,
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
                            {{$rejectedItemReportStoreWise->appends($_GET)->links()}}
                        </div>
                    </div>
                </div>


            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>

@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $('document').ready(function(){
        $('.excel-export-storewise').click(function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            let query = {
                store_code: $('#store').val(),
                from_date: $('#from_date').val(),
                to_date: $('#to_date').val(),
                page : 1
            }
             var excelDownloadUrl = url +'&' + $.param(query)
             window.location = excelDownloadUrl;
        });
    });
</script>
