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
   'manage_url'=>route($base_route.'index'),
   ])
    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div id="showFlashMessage"></div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <form  action="{{route('admin.wh-rejected-item-report.detail-report',
                                                            [
                                                             'warehouseCode' => $filterParameters['warehouse_code'],
                                                             'productCode' => $filterParameters['product_code'],
                                                             'storeCode' => $filterParameters['store_code'],
                                                            ]
                                                            )}}" method="GET">
                                <div class="row">
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
                                    <div class="col-lg-3 col-md-3">
                                        <div class="form-group">
                                            <label for="order_type">Order Types</label>
                                            <select class="form-control select2" name="order_type" id="order_type">
                                                <option value="">All</option>
                                                @foreach($orderTypes as $orderType)
                                                    <option value="{{$orderType}}" {{($orderType == $filterParameters['order_type'] ? 'selected' : '')}}>{{ucwords(str_replace('_',' ',$orderType))}}</option>
                                                @endforeach
                                            </select>
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
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 id="report_title" class="panel-title">
                                Last Sync Date
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div class="col-md-6">
                                Last Sync Normal Order Date : {{$lastRejectedSyncData['normalOrder']['date']}}<br/>
                                Status :  {{$lastRejectedSyncData['normalOrder']['status']}} <br/>
                                Order Count :  {{$lastRejectedSyncData['normalOrder']['count']}}
                            </div>
                            <div class="col-md-6">
                                Last Sync PreOrder Date : {{$lastRejectedSyncData['preOrder']['date']}} <br/>
                                Status :  {{$lastRejectedSyncData['preOrder']['status']}} <br/>
                                Pre Order Count:  {{$lastRejectedSyncData['preOrder']['count']}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 id="report_title" class="panel-title">
                                Warehouse Rejected Item Report
                                / Warehouse : {{$warehouseName}} / Store : {{$storeName}}
                                / Product : {{$productName}}
                                @if($productVariantName) ({{$productVariantName}}) @endif
                            </h3>

                            <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                <a href="{{route('admin.wh-rejected-item-product-wise-excel-export',[
                                                'warehouseCode'=>$filterParameters['warehouse_code'],
                                                'productCode'=>$filterParameters['product_code'],
                                                'storeCode'=>$filterParameters['store_code'],
                                                'product_variant_code'=> $filterParameters['product_variant_code']
                                             ])
                                         }}" style="border-radius: 0px; " class="btn btn-sm btn-success excel-export-productwise">
                                    <i class="fa fa-file"></i>
                                    Excel Export
                                </a>
                            </div>
                        </div>



                        <div class="box-body">
                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Order Date</th>
                                    <th>Order Type</th>
                                    <th>Order Code</th>
                                    <th>Quantity</th>
                                    <th>Unit Rate</th>
                                    <th>Order Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($rejectedItemStatementOfProduct as $key =>$statement)
                                    <tr>
                                        <td>{{++$loop->index}}</td>
                                        <td>{{getReadableDate($statement->order_date)}}</td>
                                        <td>{{ucwords(str_replace('_',' ',$statement->order_type))}}</td>
                                        <td>
                                            @if($statement->link)
                                                <a href="{{$statement->link}}" target="_blank"> {{$statement->order_code}} </a>
                                            @else
                                                {{$statement->order_code}}
                                            @endif
                                        </td>

                                            <td><span class="badge bg-secondary">
                                                {{$statement->rejected_qty}}
                                                </span>
                                            </td>

                                        <td>{{getNumberFormattedAmount($statement->unit_rate)}}</td>
                                        <td>{{getNumberFormattedAmount($statement->total_amount)}}</td>
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
                            {{$rejectedItemStatementOfProduct->appends($_GET)->links()}}
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
        $('.excel-export-productwise').click(function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            let query = {
                from_date: $('#from_date').val(),
                to_date: $('#to_date').val(),
                page : 1
            }
            var excelDownloadUrl = url +'&' + $.param(query)
            window.location = excelDownloadUrl;
        });
    });
</script>




