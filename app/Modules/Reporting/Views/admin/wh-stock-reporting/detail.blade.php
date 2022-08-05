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

                        <div class="panel-body">
                            <form action="{{route('admin.wh-stock-report.warehouse-product-master.detail',[
                                                    'warehouseCode' => $warehouseProductMaster->warehouse_code,
                                                    'warehouseProductMasterCode' => $warehouseProductMaster->warehouse_product_master_code
                                                    ])}}" method="get">

                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="stock_action">Stock Action</label>
                                        <select name="stock_action" class="form-control select2" id="stock_action">
                                            <option value="">All</option>
                                            @foreach($stockActions as $stockAction)
                                                <option value="{{$stockAction}}" {{$stockAction == $filterParameters['stock_action'] ? 'selected' :''}}>
                                                    {{ucwords($stockAction)}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="start_date">Start Date</label>
                                        <input type="date" class="form form-control" name="start_date" id="start_date" value="{{$filterParameters['start_date']}}">
                                    </div>
                                </div>
                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="end_date">End Date</label>
                                        <input type="date" class="form form-control" name="end_date" id="end_date" value="{{$filterParameters['end_date']}}">
                                    </div>
                                </div>
                                <div class="col-xs-3" style="margin-top: 25px">
                                    <button type="submit" class="btn btn-sm btn-primary form-control">Filter</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div style="font-size:15px"  class="panel-title">
                             List of Product Stock Report /
                                Product: <strong>{{$warehouseProductMaster->product->product_name}}</strong>
                                @if($warehouseProductMaster->product_variant_code)
                                   <strong> ({{$warehouseProductMaster->productVariant->product_variant_name}})</strong>
                                @endif
                                 ({{ $warehouseProductMaster->warehouse_product_master_code }})

                            / Current Stock: <strong>{{$warehouseProductMaster->current_stock}}</strong>
                            / Vendor Name: <strong>{{$warehouseProductMaster->vendor->vendor_name}}</strong>
                            </div>
                            <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                <a class="btn btn-warning btn-xs" id="download-excel">
                                    <i class="fa fa-file-archive-o"></i>
                                    Excel Download
                                </a>
                            </div>
                            <a href="{{route('admin.wh-stock-report.index')}}" style="border-radius: 3px; " class="btn btn-xs btn-info pull-right" >
                                <i class="fa fa-plus-circle"></i>
                                Back to Stock List
                            </a>

                        </div>

                        <div class="box-body">
                            <table class="table table-bordered table-striped" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Stock Action</th>
                                    <th>Reference</th>
                                    <th style="text-align: center">IN</th>
                                    <th style="text-align: center">OUT</th>
                                    <th>Current Stock</th>
                                    <th>Created At</th>
                                </tr>

                                </thead>
                                <tbody>
                                @foreach($warehouseProductStatements as $i => $warehouseProductStatement)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td><strong>{{ ucwords(str_replace('-',' ',$warehouseProductStatement->action))}}</strong></td>
                                        <td>
                                            @if($warehouseProductStatement->reference_code)
                                                @if($warehouseProductStatement->link_data['link'])
                                                    <a target="_blank" href="{{$warehouseProductStatement->link_data['link']}}">
                                                        {{$warehouseProductStatement->reference_code}}
                                                    </a>
                                                    @if($warehouseProductStatement->link_data['value'])
                                                        <small>({{$warehouseProductStatement->link_data['value']}}) </small>
                                                    @endif
                                                @else
                                                    {{$warehouseProductStatement->reference_code}}
                                                    @if($warehouseProductStatement->link_data['value'])
                                                        <small>({{$warehouseProductStatement->link_data['value']}}) </small>
                                                    @endif
                                                @endif
                                            @else
                                                <span class="label label-danger">Ref: N/A</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($warehouseProductStatement->stock_changing_type == 'in')
                                                @if($warehouseProductStatement->package)
                                                    {{$warehouseProductStatement->package}}
                                                @else
                                                    {{ $warehouseProductStatement->quantity}}
                                                    <br/>
                                                    (Packaging: N/A)
                                                @endif
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($warehouseProductStatement->stock_changing_type == 'out')
                                                @if($warehouseProductStatement->package)
                                                    {{$warehouseProductStatement->package}}
                                                @else
                                                    {{ $warehouseProductStatement->quantity}}
                                                    <br/>
                                                    (Packaging: N/A)
                                                @endif
                                            @endif
                                        </td>
                                        <td>{{$warehouseProductStatement->current_stock}}</td>
                                        <td>
                                            {{getReadableDate(getNepTimeZoneDateTime($warehouseProductStatement->created_at))}}
                                        </td>

                                    </tr>
                                @endforeach
                                </tbody>

                            </table>
                            {{$warehouseProductStatements->appends($_GET)->links()}}
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
                start_date: $('#start_date').val(),
                end_date: $('#end_date').val(),
                stock_action : $('#stock_action').val(),
                page : 1
            }
            return params;
        }

        $('#download-excel').on('click',function (e){
            e.preventDefault();
            var filterd_params = productsFilterParams();
            filterd_params.download_excel = true;
            var queryString = $.param(filterd_params)
            var url = "{{route('admin.wh-stock-report.warehouse-product-master.detail',[
                                                    'warehouseCode' => $warehouseProductMaster->warehouse_code,
                                                    'warehouseProductMasterCode' => $warehouseProductMaster->warehouse_product_master_code
                                                    ])}}"+'?'+queryString;

            console.log(url);
            window.open(url,'_blank');
        });


    </script>
@endpush
