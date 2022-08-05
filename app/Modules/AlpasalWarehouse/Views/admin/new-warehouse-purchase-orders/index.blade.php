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
                            <form action="{{route('admin.warehouse-purchase-orders.index')}}" method="get">
                                <div class="col-xs-3">
                                    <label for="vendor">Vendor</label>
                                    <select id="vendor" name="vendor" class="form-control">
                                        <option value="">
                                            All
                                        </option>

                                        @foreach($vendors as $vendor)
                                            <option value="{{$vendor->vendor_code}}"
                                                {{$vendor->vendor_code == $filterParameters['vendor_code'] ?'selected' :''}}>
                                                {{ucwords($vendor->vendor_name)}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-xs-3">
                                    <label for="warehouse">Warehouse</label>
                                    <select id="warehouse" name="warehouse" class="form-control">
                                        <option value="">
                                            All
                                        </option>

                                        @foreach($warehouses as $warehouse)
                                            <option value="{{$warehouse->warehouse_code}}"
                                                {{$warehouse->warehouse_code == $filterParameters['warehouse_code'] ?'selected' :''}}>
                                                {{ucwords($warehouse->warehouse_name)}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-xs-3">
                                    <label for="status">Status</label>
                                    <select id="status" name="status" class="form-control">
                                        <option value="">
                                            All
                                        </option>

                                        @foreach($statuses as $status)
                                            <option value="{{$status}}"
                                                {{$status == $filterParameters['status'] ?'selected' :''}}>
                                                {{ucwords($status)}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-xs-3">
                                    <label for="order_date_from">Order Date From</label>
                                    <input type="date" class="form-control" name="order_date_from" id="order_date_from" value="{{$filterParameters['order_date_from']}}">
                                </div>

                                <div class="col-xs-3">
                                    <label for="order_date_to">Order Date To</label>
                                    <input type="date" class="form-control" name="order_date_to" id="order_date_to" value="{{$filterParameters['order_date_to']}}">
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
                                List of Purchase Orders
                            </h3>


                        </div>


                        <div class="box-body">

                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Order Code</th>
                                    <th>Warehouse</th>
                                    <th>Vendor</th>
                                    <th>Order Date</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($purchaseOrders as $i => $purchaseOrder)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>{{$purchaseOrder->warehouse_order_code}}</td>
                                        <td>{{$purchaseOrder->warehouse->warehouse_name}}</td>
                                        <td>{{$purchaseOrder->vendor->vendor_name}}</td>
                                        <td>{{$purchaseOrder->order_date ? $purchaseOrder->order_date :'-'}}</td>
                                        <td> <span class="label label-primary">{{ucwords($purchaseOrder->status)}}</span></td>
                                        <td>{{date("Y-m-d",strtotime($purchaseOrder->created_at))}}</td>
                                       {{-- <td>
                                            {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Details ',route('warehouse.warehouse-purchase-orders.show', $purchaseOrder->warehouse_order_code),'View Order', 'eye','info')!!}

                                            @if($purchaseOrder->getOrderStatus() == 'draft')

                                                {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete',route('warehouse.warehouse-purchase-orders.destroy',$purchaseOrder->warehouse_order_code),$purchaseOrder,'Order','')!!}
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ',route('warehouse.warehouse-purchase-orders.edit', $purchaseOrder->warehouse_order_code),'Edit Order', 'pencil','primary')!!}

                                            @else
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Download ',route('warehouse.warehouse-purchase-orders.generate-bill', [$purchaseOrder->warehouse_order_code,'action'=>'download']),'Download Bill', 'download','primary')!!}
                                            @endif

                                        </td>--}}
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

                            {{$purchaseOrders->appends($_GET)->links()}}
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
@endsection
