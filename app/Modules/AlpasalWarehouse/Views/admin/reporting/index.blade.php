@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>$title,
    'sub_title'=> "Manage {$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>'',
    ])


    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <form action="{{route('admin.reporting.getReportingData')}}" method="get">

                                <div class="col-xs-3">
                                    <label for="store_name">Store Name</label>
                                    <input type="text" class="form-control" name="store_name" id="store_name" value="{{$filterParameters['store_name']}}">
                                </div>
                                <div class="col-xs-3">
                                    <label for="store_owner">Store Owner</label>
                                    <input type="text" class="form-control"  name="store_owner" id="store_owner" value="{{$filterParameters['store_owner']}}">
                                </div>
                                <div class="col-xs-3">
                                    <label for="pre_order_name">Pre Order Name</label>
                                    <input type="text" class="form-control"  name="pre_order_name" id="pre_order_name" value="{{$filterParameters['pre_order_name']}}">
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="warehouse_code">Select Warehouse</label>
                                        <select name="warehouse_code" class="form-control select2" id="warehouse_code">
                                            <option value="" {{$filterParameters['warehouse_code'] == ''}}>All</option>
                                            @foreach($warehouses as $warehouse)
                                                <option value="{{$warehouse->warehouse_code}}"
                                                    {{$warehouse->warehouse_code == $filterParameters['warehouse_code'] ?'selected' :''}}>
                                                    {{ucwords($warehouse->warehouse_name)}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="province" class="control-label">Province  *</label>
                                        <select class="form-control" id="province" name="province" >
                                            <option selected value="" >--Select An Option--</option>
                                            @if(isset($provinces) && count($provinces)>0)
                                                @foreach ($provinces as $province)
                                                    <option value={{ $province->location_code }} {{ $filterParameters['province'] == $province->location_code ? 'selected': '' }}>{{ $province->location_name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="district" class="control-label">District  *</label>
                                        <select name="district" class="form-control" id="district" onchange="districtChange()">
                                            <option selected value="" >--Select An Option--</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="municipality" class="control-label">Municipality  *</label>
                                        <select name="municipality" class="form-control" id="municipality" onchange="municipalityChange()">
                                            <option selected value="" >--Select An Option--</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="ward" class="control-label">Ward  *</label>
                                        <select class="form-control" id="ward"  name="ward">
                                            <option selected value="" >--Select An Option--</option>
                                        </select>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary btn-sm pull-right">Filter</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of Warehouse Preorder Reporting
                            </h3>
                            <h3 class="panel-title pull-right">
                                <a class="btn btn-sm btn-primary" href="{{route('admin.reporting.excelExportReport') }}">Download Excell Bill</a>
                            </h3>
                        </div>
                        <div class="box-body">
                            <table id="data-table" class="table table-bordered table-striped" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Pre Order Name</th>
                                    <th>Store Pre Order Code</th>
                                    <th>Store Name</th>
                                    <th>Warehouse Name</th>
                                    <th width="15%">Amount</th>
                                    <th>Current Balance</th>
{{--                                    <th>Action</th>--}}
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($warehousePreOrders as $i => $warehousePreOrder)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>{{$warehousePreOrder->pre_order_name}}</td>
                                        <td>{{$warehousePreOrder->store_preorder_code}}</td>
{{--                                        <td><a href="#" data-toggle="tooltip" data-placement="top" title="--}}
{{--                                        <div>Owner:{{$warehousePreOrder->store_owner}}<div>--}}
{{--                                        <div>Phone:{{$warehousePreOrder->phone}}<div>--}}
{{--                                        <div>Mobile:{{$warehousePreOrder->mobile}}<div>--}}
{{--                                        <div>Location:{{$warehousePreOrder->store_full_location}}<div>--}}
{{--                                        <div>Current Balance: Rs. {{$warehousePreOrder->current_balance}}<div>--}}
{{--                                             ">{{$warehousePreOrder->store_name}}</a>--}}
{{--                                        </td>--}}
                                        <td>{{$warehousePreOrder->store_name}}
                                            <div>Contact: {{isset($warehousePreOrder->phone) ? $warehousePreOrder->phone .',' : ''}}{{$warehousePreOrder->mobile}}</div>
                                            <div>Location: {{$warehousePreOrder->store_full_location}}</div>
                                        </td>
                                        <td>{{$warehousePreOrder->warehouse_name}}</td>
                                        <td>Rs. {{$warehousePreOrder->amount}}</td>
                                        <td>Rs. {{$warehousePreOrder->current_balance}}</td>
{{--                                        <td>--}}
{{--                                            @can('Show Warehouse')--}}
{{--                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Show ',route('admin.warehouses.show', $warehouse->warehouse_code),'Show warehouse', 'eye','info')!!}--}}

{{--                                            @endcan--}}
{{--                                        </td>--}}
                                    </tr>
                                @endforeach
                                </tbody>

                            </table>
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
 @includeIf('AlpasalWarehouse::admin.reporting.location-filter-script');
@endpush
