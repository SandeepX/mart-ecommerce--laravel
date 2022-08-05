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
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    List of Warehouse Purchase Orders
                                </h3>



                                <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
                                    <a href="{{ route('new-warehouse-purchase-orders') }}" style="border-radius: 0px; " class="btn btn-sm btn-info">
                                        <i class="fa fa-plus-circle"></i>
                                        Add New Purchase Order
                                    </a>
                                </div>

                            </div>


                            <div class="box-body">
                                @include(''.$module.'.admin.warehouse-purchase-orders.common.filter-form')

                                <table id="data-table" class="table table-bordered table-striped" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Order Code</th>
                                        <th>Order Date</th>
                                        <th>Warehouse</th>
                                        <th>Vendor</th>
                                        <th>Status</th>
                                        <th>Sent Date</th>
                                        <th>Vendor Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($purchaseOrders as $i => $purchaseOrder)
                                       <tr>
                                           <td>{{++$i}}</td>
                                           <td>{{$purchaseOrder->order_code}}</td>
                                           <td>{{$purchaseOrder->order_date}}</td>
                                           <td>{{$purchaseOrder->warehouse->warehouse_name}}</td>
                                           <td>{{$purchaseOrder->vendor->vendor_name}}</td>
                                           <td>{{$purchaseOrder->sent_status}}</td>
                                           <td>{{$purchaseOrder->sent_date}}</td>
                                           <td>{{isset($purchaseOrder->receivedByVendor) ? $purchaseOrder->receivedByVendor->order_received_status : 'not received'}}</td>
                                          
                                           <td>
                                                {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Details ',route('admin.warehouse-purchase-orders.show', $purchaseOrder->order_code),'View Order', 'eye','info')!!}

                                                @if($purchaseOrder->sent_status == 'draft')

                                                    {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete',route('admin.brands.destroy',$purchaseOrder->order_code),$purchaseOrder,'Order','')!!}
                                                    {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ',route('admin.warehouse-purchase-orders.edit', $purchaseOrder->order_code),'Edit Order', 'pencil','primary')!!}

                                                @endif

                                           </td>
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