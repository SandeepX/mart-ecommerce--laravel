<div class="panel panel-info">
    <div class="panel-heading"> <b>Pre-Order Status Log</b></div>
    <div class="panel-body">

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>S.N</th>
                <th>Status</th>
{{--                <th>Updated By</th>--}}
                <th>Updated At</th>
                <th>Remarks</th>
            </tr>
            </thead>
            <tbody>
            {{--                                       @foreach($storeOrderStatusLogs as $i => $storeOrderStatusLog)--}}
            @forelse($storePreOrderStatusLogs as $i =>$statusLog)
                <tr>
                    <td>{{++$i}}</td>
                    <td>{{strtoupper($statusLog->status)}}</td>
{{--                    <td>{{$statusLog->updatedBy->name}}</td>--}}
                    <td>{{getReadableDate(getNepTimeZoneDateTime($statusLog->updated_at))}}</td>
                    <td>

                        <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#statusLogRemarks{{$i}}">
                            <i class="fa fa-eye"></i>
                        </button>

                    </td>
                </tr>

                <!-- Modal -->
                <div id="statusLogRemarks{{$i}}" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Remarks - Order Code : {{$storePreOrder->store_preorder_code}} / Status : {{ucwords($statusLog->status)}}</h4>
                            </div>
                            <div class="modal-body">

                                {!! $statusLog->remarks !!}

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>

                    </div>
                </div>

            @empty
                <tr>
                    <td colspan="100%">
                        <p class="text-center"><b>No records found!</b></p>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
