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
                <div class="col-xs-12">
                    @can('View Store Pre Orders In Pre Order')
                        <div class="panel panel-default">

                            <div class="panel-body">
                                <form action="{{route('warehouse.warehouse-pre-orders.stores.detail',$storeCode)}}" method="get">

                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <label for="pre_order_name">Pre-Order Name</label>
                                            <input type="text" class="form-control" name="pre_order_name" id="pre_order_name"
                                                   value="{{$filterParameters['pre_order_name']}}">
                                        </div>
                                    </div>

                                    <div class="col-xs-4">
                                        <label for="status">Status</label>
                                        <select class="form-control select2" id="status" name="status[]" multiple>

                                            @foreach($preOrderStatuses as $preOrderStatus)
                                                <option value="{{$preOrderStatus}}"
                                                    {{(isset($filterParameters['statuses']) && in_array($preOrderStatus,$filterParameters['statuses']))? 'selected' :''}}>
                                                    {{ucwords($preOrderStatus)}}
                                                </option>
                                            @endforeach
                                        </select>
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
                    @endcan
                </div>

                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                List of Pre-Orders
                            </h3>

                        </div>

                        @can('View Store Pre Orders In Pre Order')
                            <div class="box-body">

                                <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>S.N</th>
                                        <th>PRE ORDER</th>
                                        <th>STATUS</th>
                                        <th>PAYMENT STATUS</th>
                                        <th>AMOUNT</th>
                                        <th>ORDER CREATED</th>
                                        <th>EARLY FINALIZED</th>
                                        <th>EARLY CANCELLED</th>
                                        <th>ACTION</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @forelse($preOrdersListing as $i => $preOrder)
                                        <tr>
                                            <td>{{++$i}}</td>
                                            <td>
                                                <b>Pre Order : {{$preOrder->pre_order_name}}({{$preOrder->warehouse_preorder_listing_code}})</b>
                                                <br>
                                                Start Time : <b>{{getReadableDate($preOrder->start_time)}}</b> <br>
                                                End Time : <b>{{getReadableDate($preOrder->end_time)}}</b>
                                            </td>
                                            <td>
                                                <span class="label label-{{returnLabelColor($preOrder->status)}}">
                                                     {{$preOrder->status}}
                                                </span>

                                            </td>
                                            <td>
                                                  <span class="label label-{{returnLabelColor($preOrder->payment_status)}}">
                                                     {{$preOrder->payment_status == 1 ? 'Paid': 'Unpaid'}}
                                                </span>

                                            </td>
                                            <td>{{$preOrder->total_price}}</td>
                                            <td>{{getReadableDate(getNepTimeZoneDateTime($preOrder->created_at))}}</td>

                                            <td>
                                                <span class="label label-{{$preOrder->early_finalized ? 'success' : 'danger'}}">
                                                    {{ $preOrder->early_finalized ? 'Yes': 'No'}}
                                                </span>
                                                @if($preOrder->early_finalized)
                                                    &nbsp;<a href="#" data-toggle="tooltip" data-html = "true" data-placement="Sources"
                                                             title="Remarks : {{$preOrder->storePreOrderEarlyFinalization->early_finalization_remarks}}
                                                                 <br>Date : {{getReadableDate(getNepTimeZoneDateTime($preOrder->storePreOrderEarlyFinalization->early_finalization_date))}}
                                                                 <br> Finalized By : {{ $preOrder->storePreOrderEarlyFinalization->earlyFinalizedBy->name }} ">
                                                        <i class="fa fa-info-circle"></i>
                                                    </a>
                                                @endif
                                            </td>

                                            <td>
                                                <span class="label label-{{$preOrder->storePreOrderEarlyCancellation ? 'success' : 'danger'}}">
                                                    {{ $preOrder->storePreOrderEarlyCancellation ? 'Yes': 'No'}}
                                                </span>

                                                @if($preOrder->storePreOrderEarlyCancellation)
                                                    &nbsp;<a href="#" data-toggle="tooltip" data-html = "true" data-placement="Sources"
                                                             title="Remarks : {{$preOrder->storePreOrderEarlyCancellation->early_cancelled_remarks}}
                                                                 <br>Date : {{getReadableDate(getNepTimeZoneDateTime($preOrder->storePreOrderEarlyCancellation->early_cancelled_date))}}
                                                                 <br> Cancelled
                                                                  By : {{ $preOrder->storePreOrderEarlyCancellation->earlyCancelledBy->name }} ">
                                                        <i class="fa fa-info-circle"></i>
                                                    </a>
                                                @endif
                                            </td>

                                            <td>
                                                @can('View Store Pre Order Details')
                                                    {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('Details ',route('warehouse.warehouse-pre-orders.store-orders.show', $preOrder->store_preorder_code),'Details', 'eye','info')!!}
                                                @endcan

                                                    @if(!$preOrder->storePreOrderEarlyCancellation && !$preOrder->early_finalized && $preOrder->status =='pending')
                                                        <a data-href="{{route('warehouse.warehouse-pre-orders.store-pre-order.early-finalize.create',$preOrder->store_preorder_code)}}" class="btn btn-xs btn-primary early-finalize" data-target="#earlyFinalizeModal">Early Finalize</a>
                                                        <a data-href="{{route('warehouse.warehouse-pre-orders.store-pre-order.early-cancel.create',$preOrder->store_preorder_code)}}" class="btn btn-xs btn-primary early-cancel "  data-target="#earlyCancelModal">Early Cancel</a>
                                                    @endif
                                            </td>
                                        </tr>

                                    @empty
                                        <tr>
                                            <td colspan="10">
                                                <p class="text-center"><b>No records found!</b></p>
                                            </td>

                                        </tr>
                                    @endforelse
                                    </tbody>


                                </table>
                                {{$preOrdersListing->appends($_GET)->links()}}
                            </div>
                        @endcan
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
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
    </div>

@endsection

@push('scripts')
    <script>
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

