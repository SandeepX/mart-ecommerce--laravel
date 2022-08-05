
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">
     Set PreOrder Target
    </h4>
    <div id="showFlashMessageModal"></div>
</div>
<form id="preOrderTarget" action="{{route('warehouse.warehouse-pre-order-target.pre-order-target.update',$preOrderListingCode)}}" method="POST">
    @csrf
<div class="modal-body">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Store Type</th>
            <th>Group</th>
            <th>Individual</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($preOrderListing) && $preOrderListing->count())
            @foreach($storeTypes as $storeType)
                <tr>
                    <td><input type="hidden" name="store_type_code[]" value="{{$storeType->store_type_code}}">
                        {{$storeType->store_type_name}}
                    </td>
                    <td><input type="number" name="target_group_value[]"
                               value="{{\App\Modules\AlpasalWarehouse\Helpers\PreOrder\WarehousePreOrderTargetHelper::getGroupPreOrderTarget($preOrderListingCode,$storeType->store_type_code)}}">

                    </td>
                    <td><input type="number" name="target_individual_value[]"
                               value="{{\App\Modules\AlpasalWarehouse\Helpers\PreOrder\WarehousePreOrderTargetHelper::getIndividualPreOrderTarget($preOrderListingCode,$storeType->store_type_code)}}">
                    </td>
                </tr>
            @endforeach
        @else
            @foreach($storeTypes as $storeType)
                <tr>
                    <td>
                        {{$storeType->store_type_name}}
                    </td>
                    <td>
                        {{\App\Modules\AlpasalWarehouse\Helpers\PreOrder\WarehousePreOrderTargetHelper::getGroupPreOrderTarget($preOrderListingCode,$storeType->store_type_code)}}
                    </td>
                    <td>
                        {{\App\Modules\AlpasalWarehouse\Helpers\PreOrder\WarehousePreOrderTargetHelper::getIndividualPreOrderTarget($preOrderListingCode,$storeType->store_type_code)}}
                    </td>
                </tr>
            @endforeach
            <div style="color: red">This PreOrder is not Targetable</div>
        @endif

        </tbody>
    </table>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    @if(isset($preOrderListing) && $preOrderListing->count())
        @can('Create Target')
             <button type="submit" class="btn btn-primary update-pre-order-target">Save changes</button>
        @endcan
    @endif
</div>
</form>

