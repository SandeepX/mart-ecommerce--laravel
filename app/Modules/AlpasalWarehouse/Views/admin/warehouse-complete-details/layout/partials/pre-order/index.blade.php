<div class="row">

    <div class="col-xs-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">
                    List of Pre-Orders
                </h3>
            </div>


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
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($warehousePreOrders as $i => $warehousePreOrder)
                        <tr>
                            <td>{{++$i}}</td>
                            <td>{{$warehousePreOrder->pre_order_name}}</td>
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
                <div class="pagination" id="warehouse-preorder-pagination">
                    @if(isset($warehousePreOrders))
                        {{$warehousePreOrders->appends($_GET)->links()}}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
