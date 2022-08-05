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
            <div id="showFlashMessage"></div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default">

                        <div class="panel-body">
                            <form action="{{route('warehouse.warehouse-pre-orders.index')}}" method="get">

                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label for="pre_order_name">Pre Order Name</label>
                                        <input type="text" class="form-control" name="pre_order_name" id="pre_order_name"
                                               value="{{$filterParameters['pre_order_name']}}">
                                    </div>
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
                                List of Pre-Orders
                            </h3>

                            @can('Create WH Pre Order')
                                <div class="pull-right" style="margin-top: -25px;margin-left: 10px;">
    {{--                                <a href="{{ route('warehouse.warehouse-pre-orders.finalize') }}"--}}
    {{--                                   style="border-radius: 0px; " class="btn btn-sm btn-info">--}}
    {{--                                    Finalize Pre-Orders--}}
    {{--                                </a>--}}

                                    <a href="{{ route('warehouse.warehouse-pre-orders.create') }}"
                                       style="border-radius: 0px; " class="btn btn-sm btn-info">
                                        <i class="fa fa-plus-circle"></i>
                                        Add New Pre-Order
                                    </a>
                                </div>
                            @endcan

                        </div>

                       @can('View List Of WH Pre Orders')
                            <div class="box-body">

                                <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Pre Order Name</th>
                                        <th>Start Time</th>
                                        <th>End Time</th>
                                        <th>Finalization Time</th>
                                        <th>Active</th>
                                        <th>Status Type</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($warehousePreOrders as $i => $warehousePreOrder)
                                        <tr>
                                            <td>{{++$i}}</td>
                                            <td><strong>{{$warehousePreOrder->pre_order_name}} ({{$warehousePreOrder->warehouse_preorder_listing_code}})</strong></td>
                                            <td>{{getReadableDate($warehousePreOrder->start_time)}}</td>
                                            <td>{{getReadableDate($warehousePreOrder->end_time)}}</td>
                                            <td>{{getReadableDate($warehousePreOrder->finalization_time)}}</td>
                                            <td>
                                                @if($warehousePreOrder->is_active)
                                                    @php
                                                        $activeStatus = 'Deactivate';
                                                    @endphp
                                                    <span class="label label-success">On</span>
                                                @else
                                                    @php
                                                        $activeStatus = 'Activate';
                                                    @endphp
                                                    <span class="label label-danger">Off</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="label label-{{returnLabelColor($warehousePreOrder->status_type)}}">
                                                    {{$warehousePreOrder->status_type}}
                                                </span>

                                            </td>
                                            <td>{{date("Y-m-d",strtotime($warehousePreOrder->created_at))}}</td>
                                            <td>
                                                @can('View Added Products Of WH Pre Order')
                                                    {{--{!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Details ',route('warehouse.warehouse-pre-orders.show', $warehousePreOrder->warehouse_preorder_listing_code),'View', 'eye','info')!!}--}}
                                                    {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Added products ',route('warehouse.warehouse-pre-orders.products.vendors-list', $warehousePreOrder->warehouse_preorder_listing_code),'Added products to pre-order ', 'eye','primary')!!}
                                                @endcan

                                                @if(!$warehousePreOrder->isFinalized())
                                                    @can('Edit WH Pre Order')
                                                        {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Edit ',route('warehouse.warehouse-pre-orders.edit', $warehousePreOrder->warehouse_preorder_listing_code),'Edit ', 'pencil','primary')!!}
                                                    @endcan
                                                @endif

                                                @if(!$warehousePreOrder->isPastFinalizationTime())
                                                    @can('Change the Status Of WH Pre Order')
                                                        <a href="{{route('warehouse.warehouse-pre-orders.toggle-status',['warehousePreOrderCode'=>$warehousePreOrder['warehouse_preorder_listing_code']])}}" class="btn btn-primary changeStatus btn-sm" role="button">change status</a>
                                                    @endcan
                                                    @can('Add Products To WH Pre Order')
                                                        {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Add Products ',route('warehouse.warehouse-pre-orders.add-products', $warehousePreOrder->warehouse_preorder_listing_code),'Add products to pre-order ', 'plus','primary')!!}
                                                    @endcan

                                                    @if(!$warehousePreOrder->isPastStartTime())
                                                        @can('Delete WH Pre Order')
                                                            {!! \App\Modules\Application\Presenters\DataTable::makeDeleteAction('Delete',route('warehouse.warehouse-pre-orders.destroy',$warehousePreOrder->warehouse_preorder_listing_code),$warehousePreOrder,'Pre-Order','')!!}
                                                        @endcan
                                                    @endif
                                                @elseif($warehousePreOrder->hasBeenOrderedByStore())
                                                    @can('View List Of Vendors For Pre Orders')
                                                        {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Place Order',route('warehouse.warehouse-pre-orders.vendors-list', $warehousePreOrder->warehouse_preorder_listing_code),'Place order to vendor ', 'plus','primary')!!}
                                                    @endcan
                                                @endif
                                                    @can('Create PreOrder Target')
                                                        <button type="button" class="btn btn-primary btn-sm wpl-modal" style="padding-top: 1px;padding-bottom: 1px;" data-wpl-code="{{$warehousePreOrder->warehouse_preorder_listing_code}}">
                                                            Set Target
                                                        </button>
                                                    @endcan

                                                    @can('Show PreOrder Target')
                                                        <button type="button" class="btn btn-primary btn-sm pre-order-target-modal" style="padding-top: 1px;padding-bottom: 1px;" data-wplt-code="{{$warehousePreOrder->warehouse_preorder_listing_code}}">
                                                            view Target
                                                        </button>
                                                    @endcan

                                                @if($warehousePreOrder->hasBeenOrderedByStore())
                                                    @can('View List Of Store Pre Orders')
                                                            {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Store Orders ',route('warehouse.warehouse-pre-orders.store-orders', $warehousePreOrder->warehouse_preorder_listing_code),'Store Orders ', '','primary')!!}
                                                    @endcan
                                                @endif

                                                @if(!$warehousePreOrder->isFinalized() && !$warehousePreOrder->isCancelled() )
                                                    @can('Finalize WH Pre Order')
                                                        <a href="{{ route('warehouse.warehouse-pre-orders.single.finalize',$warehousePreOrder->warehouse_preorder_listing_code) }}" class="finalize-btn">
                                                            <button data-placement="left" data-tooltip="true" class="btn btn-xs btn-primary">
                                                                <span class="fa"></span>
                                                                Finalize
                                                            </button>
                                                        </a>
                                                    @endcan
                                                @elseif($warehousePreOrder->isFinalized() )
                                                    <label class="btn btn-xs btn-success">
                                                        Finalized
                                                    </label>

                                                  <!-- Trigger the modal with a button -->
                                               <button type="button"
                                                       class="btn btn-info btn-xs"
                                                       data-toggle="modal"
                                                       data-backdrop="static" data-keyboard="false"
                                                       data-target="#clonePreOrder{{$warehousePreOrder->warehouse_preorder_listing_code}}">
                                                   Clone PreOrder
                                               </button>

                                                        <!-- Modal -->
                                                        <div id="clonePreOrder{{$warehousePreOrder->warehouse_preorder_listing_code}}" class="modal fade" role="dialog">
                                                            <div class="modal-dialog">

                                                                <!-- Modal content-->
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                        <h4 style="color: red" class="modal-title">
                                                                          Clone  WH Pre Order Listing : {{$warehousePreOrder->pre_order_name}} ({{$warehousePreOrder->warehouse_preorder_listing_code}}) ?
                                                                        </h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form method="post"
                                                                              action="{{route('warehouse.warehouse-pre-orders.single.clone',
                                                                                            $warehousePreOrder->warehouse_preorder_listing_code
                                                                                      )}}">
                                                                            @csrf
                                                                            <div class='form-group'>
                                                                                <label >Pre Order Name </label>
                                                                                <input type='text' class="form-control"
                                                                                       value="{{old('pre_order_name')}}"
                                                                                       required
                                                                                       name="pre_order_name"/>

                                                                            </div>
                                                                            <br>

                                                                            <div class='input-group date datetimepicker'>
                                                                                <label >Start Time </label>
                                                                                <input type='text' class="form-control"
                                                                                       value="{{old('start_time')}}"
                                                                                       required
                                                                                       name="start_time"/>
                                                                                <span class="input-group-addon">
                                                                                 <span class="glyphicon glyphicon-calendar"></span>
                                                                                 </span>
                                                                            </div>
                                                                            <br>

                                                                            <div class='input-group date datetimepicker'>
                                                                                <label >End Time </label>
                                                                                <input type='text' class="form-control"
                                                                                       value="{{old('end_time')}}"
                                                                                       required
                                                                                       name="end_time"/>
                                                                                <span class="input-group-addon">
                                                                                 <span class="glyphicon glyphicon-calendar"></span>
                                                                                 </span>
                                                                            </div>
                                                                            <br>

                                                                            <div class='input-group date datetimepicker'>
                                                                                <label >Finalization Time </label>
                                                                                <input type='text' class="form-control"
                                                                                       value="{{old('finalization_time')}}"
                                                                                       required
                                                                                       name="finalization_time"/>
                                                                                <span class="input-group-addon">
                                                                                 <span class="glyphicon glyphicon-calendar"></span>
                                                                                 </span>
                                                                            </div>


                                                                            <button style="margin-top: 15px" type="submit" class="btn btn-success"> Clone </button>
                                                                            <button style="margin-top: 15px" type="button" class="btn btn-danger pull-right" data-dismiss="modal">Close</button>
                                                                        </form>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                @endif



                                                @if(!$warehousePreOrder->isCancelled() && !$warehousePreOrder->isFinalized())
                                                    @can('Cancel WH Pre Order')
                                                        <a href="javascript:void(0)" data-href="{{ route('warehouse.warehouse-pre-orders.single.cancel',$warehousePreOrder->warehouse_preorder_listing_code) }}" class="cancel-btn">
                                                            <button data-placement="left" data-tooltip="true" class="btn btn-xs btn-primary">
                                                                <span class="fa"></span>
                                                                Cancel
                                                            </button>
                                                        </a>
                                                    @endcan
                                                @elseif($warehousePreOrder->isCancelled())
                                                    <label class="btn btn-xs btn-danger">
                                                        Cancelled
                                                    </label>
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

                                {{$warehousePreOrders->appends($_GET)->links()}}
                            </div>
                       @endcan
                    </div>
                </div>
                @include(''.$module.'.warehouse.warehouse-pre-orders.common.pre-order-target-modal')
                @include(''.$module.'.warehouse.warehouse-pre-orders.common.pre-order-target-show-modal')
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
@endsection

@push('scripts')

    <script>

        $(function() {
            $('.datetimepicker').datetimepicker({
                format: 'YYYY-MM-DD HH:mm:ss'
            });
        });

        $('.changeStatus').click(function (e){
            e.preventDefault();
            var href = $(this).attr('href');
            Swal.fire({
                title: 'Are you sure you want to change preorder status ?',
                showDenyButton: true,
                confirmButtonText: `Yes`,
                denyButtonText: `No`,
                padding:'10em',
                width:'500px'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                } else if (result.isDenied) {
                    Swal.fire('changes not saved', '', 'info')
                }
            })
        })

    </script>
    @endpush

@push('scripts')
    @include(''.$module.'.warehouse.warehouse-pre-orders.index-script')
    @include(''.$module.'.warehouse.warehouse-pre-orders.common.pre-order-target-script')
@endpush
