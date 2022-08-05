@extends('AdminWarehouse::layout.common.masterlayout')

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
                    @can('View List Of Store Pre Orders')
                        <div class="panel panel-default">
                            <div class="row" style="margin: 5px;">
                                <div class="col-md-6 col-sm-12" style="float: left">
                                    <strong> Warehouse :</strong> {{$warehousePreOrderListing->warehouse->warehouse_name}} <br/>
                                    <strong> Pre-Order Name :</strong> {{$warehousePreOrderListing->pre_order_name}} <br/>
                                    <strong> Pre-Order Setting :</strong> {{$warehousePreOrderListing->warehouse_preorder_listing_code}} <br/>
                                </div>
                                <div class="col-md-6 col-sm-12" style="float: right">
                                    <strong> Start Date :</strong> {{getReadableDate($warehousePreOrderListing->start_time)}} <br/>
                                    <strong> End Date:</strong> {{getReadableDate($warehousePreOrderListing->end_time)}}  <br/>
                                    <strong> Finalization Date :</strong> {{getReadableDate($warehousePreOrderListing->finalization_time)}} <br/>
                                </div>
                                <hr>
                            </div>

                            <div class="panel-body">
                                @include(''.$module.'.warehouse.warehouse-pre-orders.store-pre-orders.filter-form')
                            </div>
                        </div>
                    @endcan
                </div>
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of {{  formatWords($title,true)}}
                            </h3>
                        </div>
                        @can('View List Of Store Pre Orders')
                            <div class="box-body">

                                <table id="{{ $base_route }}-table" class="table table-bordered table-striped"
                                       cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Pre-Order Code</th>
                                        <th>Store Name</th>
                                        <th>Status</th>
                                        <th>Payment Status</th>
                                        <th>Total Amount</th>
                                        <th>Order Date</th>
                                        <th>Early Finalized</th>
                                        <th>Early Cancelled</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($storePreOrders as $i => $storeOrder)
                                        <tr>
                                            <td>{{++$i}}</td>
                                            <td>{{$storeOrder->store_preorder_code}}</td>
                                            <td>{{$storeOrder->store->store_name}}</td>
                                            <td>{{ ucwords($storeOrder->status) }}</td>
                                            <td>{{ $storeOrder->payment_status == 1 ? 'Paid': 'Unpaid' }}</td>
                                            <td>{{($storeOrder->total_price)}}</td>
                                            <td>{{ date($storeOrder->created_at) }}</td>
                                            <td>
                                                <span class="label label-{{$storeOrder->early_finalized ? 'success' : 'danger'}}">
                                                    {{ $storeOrder->early_finalized ? 'Yes': 'No'}}
                                                </span>
                                                @if($storeOrder->early_finalized)
                                                    &nbsp;<a href="#" data-toggle="tooltip" data-html = "true" data-placement="Sources"
                                                       title="Remarks : {{$storeOrder->storePreOrderEarlyFinalization->early_finalization_remarks}}
                                                           <br>Date : {{getReadableDate(getNepTimeZoneDateTime($storeOrder->storePreOrderEarlyFinalization->early_finalization_date))}}
                                                           <br> Finalized By : {{ $storeOrder->storePreOrderEarlyFinalization->earlyFinalizedBy->name }} ">
                                                        <i class="fa fa-info-circle"></i>
                                                    </a>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="label label-{{$storeOrder->storePreOrderEarlyCancellation ? 'success' : 'danger'}}">
                                                    {{ $storeOrder->storePreOrderEarlyCancellation ? 'Yes': 'No'}}
                                                </span>

                                                @if($storeOrder->storePreOrderEarlyCancellation)
                                                    &nbsp;<a href="#" data-toggle="tooltip" data-html = "true" data-placement="Sources"
                                                             title="Remarks : {{$storeOrder->storePreOrderEarlyCancellation->early_cancelled_remarks}}
                                                                 <br>Date : {{getReadableDate(getNepTimeZoneDateTime($storeOrder->storePreOrderEarlyCancellation->early_cancelled_date))}}
                                                                 <br> Cancelled
                                                                  By : {{ $storeOrder->storePreOrderEarlyCancellation->earlyCancelledBy->name }} ">
                                                        <i class="fa fa-info-circle"></i>
                                                    </a>
                                                @endif
                                            </td>
                                            <td>
                                                @can('View Store Pre Order Details')
                                                    {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Details ',route('warehouse.warehouse-pre-orders.store-orders.show', $storeOrder->store_preorder_code),'Details', 'eye','info')!!}
                                                @endcan
                                                @if(!$storeOrder->storePreOrderEarlyCancellation && !$storeOrder->storePreOrderEarlyCancellation && $storeOrder->status =='pending')
                                                     <a data-href="{{route('warehouse.warehouse-pre-orders.store-pre-order.early-finalize.create',$storeOrder->store_preorder_code)}}" class="btn btn-xs btn-primary early-finalize" data-target="#earlyFinalizeModal">Early Finalize</a>
                                                     <a data-href="{{route('warehouse.warehouse-pre-orders.store-pre-order.early-cancel.create',$storeOrder->store_preorder_code)}}" class="btn btn-xs btn-primary early-cancel "  data-target="#earlyCancelModal">Early Cancel</a>
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
                                {{$storePreOrders->appends($_GET)->links()}}
                            </div>
                        @endcan
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>

    <div class="modal fade" id="earlyFinalizeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            </div>
        </div>
    </div>

    <div class="modal fade" id="earlyCancelModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>

        var closeButton =
            '<button type="button" style="color:white !important;opacity: 1 !important;" class="close" aria-hidden="true"></button>';

        let refBillNoDiv = $('#ref_bill_no_div');
        let transactionCodeDiv= $('#transaction_code_div');
        let orderCodeDiv=$('#order_code_div');
        function displayErrorMessage(data,flashElementId='showFlashMessage') {
            flashElementId='#'+flashElementId;
            var flashMessage = $(flashElementId);
            flashMessage. removeClass().addClass('alert alert-danger').show().empty();

            if (data.status == 422) {
                var errorString = "<ol type='1'>";
                for (error in data.responseJSON.data) {
                    errorString += "<li>" + data.responseJSON.data[error] + "</li>";
                }
                errorString += "</ol>";
                flashMessage.html(closeButton + errorString);
            }
            else{
                flashMessage.html(closeButton + data.responseJSON.message);
            }
        }

        $('.early-finalize').click(function(e){
            e.preventDefault();
                $.ajax({
                    type: 'GET',
                    url: $(this).attr('data-href')
                }).done(function(response) {
                  $('#earlyFinalizeModal').modal('show');
                  $('#earlyFinalizeModal .modal-content').empty().html(response);
                }).fail(function (data) {
                    $('#earlyFinalizeModal').modal('hide');
                    displayErrorMessage(data, 'showFlashMessage');
                    scroll(0,0);
                    $("#showFlashMessage").fadeOut(10000);
                });
        });

        $('.early-cancel').click(function(e){
            e.preventDefault();
            $.ajax({
                type: 'GET',
                url: $(this).attr('data-href')
            }).done(function(response) {
                $('#earlyCancelModal').modal('show');
                $('#earlyCancelModal .modal-content').empty().html(response);
            }).fail(function (data) {
                $('#earlyCancelModal').modal('hide');
                displayErrorMessage(data, 'showFlashMessage');
                scroll(0,0);
                $("#showFlashMessage").fadeOut(10000);
            });
        });

    </script>
@endpush

