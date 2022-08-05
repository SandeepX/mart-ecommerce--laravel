@extends('AdminWarehouse::layout.common.masterlayout')
@section('content')
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
                @can('View List Of WH Purchase Orders')
                    <div class="col-xs-12">
                        <div class="panel panel-default">

                            <div class="panel-body">
                                <form action="{{route('warehouse.warehouse-purchase-orders.index')}}" method="get">
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
                @endcan

                <div class="col-xs-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    List of Purchase Orders
                                </h3>


                                @can('Add New WH Purchase Order')
                                    <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                        <a href="{{ route('warehouse.warehouse-purchase-orders.create') }}" style="border-radius: 0px; " class="btn btn-sm btn-info">
                                            <i class="fa fa-plus-circle"></i>
                                            Add New Purchase Order
                                        </a>
                                    </div>
                                @endcan

                            </div>

                            @can('View List Of WH Purchase Orders')
                                <div class="box-body">

                                    <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Order Code</th>
                                            <th>Vendor</th>
                                            <th>Order Source</th>
                                            <th>Order Date</th>
                                            <th>Status</th>
                                            <th>Created At</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($purchaseOrders as $i => $purchaseOrder)
                                           <tr>
                                               <td>{{++$i}}</td>
                                               <td>{{$purchaseOrder->warehouse_order_code}}</td>
                                               <td>{{$purchaseOrder->vendor->vendor_name}}</td>
                                               <td>{{ucwords($purchaseOrder->order_source)}}</td>
                                               <td>{{$purchaseOrder->order_date ? $purchaseOrder->order_date :'-'}}</td>
                                               <td> <span class="label label-primary">{{ucwords($purchaseOrder->status)}}</span></td>
                                               <td>{{date("Y-m-d",strtotime($purchaseOrder->created_at))}}</td>
                                               <td>
                                                   @can('Show WH Purchase Order Detail')
                                                       {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Details ',route('warehouse.warehouse-purchase-orders.show', $purchaseOrder->warehouse_order_code),'View Order', 'eye','info')!!}
                                                   @endcan
                                                    @if($purchaseOrder->getOrderStatus() == 'draft')
                                                           @can('Add New WH Purchase Order')
                                                               {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete',route('warehouse.warehouse-purchase-orders.destroy',$purchaseOrder->warehouse_order_code),$purchaseOrder,'Order','')!!}
                                                               {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ',route('warehouse.warehouse-purchase-orders.edit', $purchaseOrder->warehouse_order_code),'Edit Order', 'pencil','primary')!!}
                                                            @endcan
                                                       @else
                                                           @can('Show WH Purchase Order Detail')
                                                               {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Download ',route('warehouse.warehouse-purchase-orders.generate-bill', [$purchaseOrder->warehouse_order_code,'action'=>'download']),'Download Bill', 'download','primary')!!}
                                                           @endcan
                                                    @endif

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

                                    {{$purchaseOrders->appends($_GET)->links()}}
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
