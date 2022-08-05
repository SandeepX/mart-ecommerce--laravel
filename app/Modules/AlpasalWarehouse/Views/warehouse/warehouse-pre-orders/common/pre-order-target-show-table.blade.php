
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">

    </h4>
</div>
<div class="modal-body">
    @forelse($preOrderTargets as $key=>$preOrderTargett)
        @foreach($storeTypeTargets as $storeTypeTarget)
            @if($storeTypeTarget->store_type_name==$key)
                      <div><strong>Store Tye</strong> : {{$storeTypeTarget->store_type_name}}</div><br>
                      <div><strong>Total Group Order</strong> : {{$storeTypeTarget->store_type_total_price}}</div><br>
                      <div><strong>Total Group Target</strong> : {{$storeTypeTarget->target_value}}</div><br>
                      <div><strong>Is Target Achieved</strong> :
                          <i class="{{\App\Modules\AlpasalWarehouse\Helpers\PreOrder\WarehousePreOrderTargetHelper::isTargetAchieved($storeTypeTarget->store_type_total_price,$storeTypeTarget->target_value) ? "fa fa-check" :"fa fa-times"}}"></i></div><br>
            @endif
        @endforeach
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>S.N</th>
                <th>Store</th>
                <th>Store Order</th>
                <th>Store Order Individual Target</th>
                <th>Is Target Achieved</th>
        </tr>
        </thead>
        <tbody>
                 @foreach($preOrderTargett as $preOrderTarget)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>
                        {{$preOrderTarget->store_name}}
                    </td>
                    <td>
                        {{$preOrderTarget->store_total_price}}
                    </td>
                    <td>
                        {{$preOrderTarget->target_value}}
                    </td>
                    <td>
                        <i class="{{\App\Modules\AlpasalWarehouse\Helpers\PreOrder\WarehousePreOrderTargetHelper::isTargetAchieved($preOrderTarget->store_total_price,$preOrderTarget->target_value) ? "fa fa-check" :"fa fa-times"}}"></i>
                    </td>
                </tr>
                 @endforeach
        </tbody>
    </table>
    @empty
        <div style="color: red">No PreOrder Has been ordered</div>
    @endforelse
{{--    {{$preOrderTargets->appends($_GET)->links()}}--}}
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    {{-- <button type="button" class="btn btn-primary">Save changes</button>--}}
</div>

