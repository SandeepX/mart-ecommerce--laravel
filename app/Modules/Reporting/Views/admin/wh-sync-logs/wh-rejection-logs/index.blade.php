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
   'manage_url'=>route($base_route.'.index'),
   ])
    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div id="showFlashMessage"></div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 id="report_title" class="panel-title">
                                Warehouse Rejection Report Sync Logs
                            </h3>
                        </div>
                        <div class="box-body">
                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Sync Log Code</th>
                                    <th>Order Type</th>
                                    <th>Sync Started Date</th>
                                    <th>Ended Date</th>
                                    <th>Orders Count</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($rejectionSyncLogsLists as $rejectionSyncLog)
                                    <tr>
                                        <td>{{++$loop->index}}</td>
                                        <td>{{$rejectionSyncLog->rejected_item_report_sync_log_code}}</td>
                                        <td>{{$rejectionSyncLog->order_type}}</td>
                                        <td>{{$rejectionSyncLog->sync_started_at}}</td>
                                        <td>{{$rejectionSyncLog->sync_ended_at}}</td>
                                        <td>{{$rejectionSyncLog->synced_orders_count}}</td>
                                        <td>{{ucwords($rejectionSyncLog->sync_status)}}</td>
                                        <td>{{$rejectionSyncLog->created_at}}</td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#{{$rejectionSyncLog->rejected_item_report_sync_log_code}}">
                                                View Details
                                            </button>
                                        </td>
                                    </tr>
                                    <!-- Modal -->
                                    <div class="modal fade" id="{{$rejectionSyncLog->rejected_item_report_sync_log_code}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Sync Log Code: {{$rejectionSyncLog->rejected_item_report_sync_log_code}} Order Type: {{ucwords($rejectionSyncLog->order_type)}}</h5>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="col-md-12">
                                                        <label class="control-label">Orders:</label>
                                                        <div style="overflow-wrap: break-word;max-height: 100px; overflow-y: scroll;">
                                                            {{$rejectionSyncLog->synced_orders}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label class="control-label">Remarks:</label>
                                                        <div style="max-height: 100px; overflow-y: scroll;">
                                                            {{$rejectionSyncLog->sync_remarks}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                </tbody>
                            </table>
                            {{$rejectionSyncLogsLists->appends($_GET)->links()}}
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
@endsection



