<style>
    .form-group{
        margin-bottom: 6px !important;
    }
    .alert{
        padding: 5px !important;
    }
    .swal-wide{
        width:300px !important;
        height:200px !important;
    }
</style>
<div class="modal-header">
    <h4 class="modal-title" id="exampleModalLabel">
        <strong>Early Finalize ({{$storePreOrder->store_preorder_code}})</strong><br/>
    </h4>
    <p>Store Name: {{$storePreOrder->store->store_name}} ({{$storePreOrder->store_code}})</p>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -60px;">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div id="showFlashMessageModal"></div>
<form method="post" id="formEarlyFinalize" action="{{route('warehouse.warehouse-pre-orders.store-pre-order.early-finalize.save',$storePreOrder->store_preorder_code)}}">
    @csrf
    <div class="modal-body">
        <div class="form-group">
            <label class="control-label">Remarks</label>
            <textarea class="form-control input-sm" name="remarks" id="remarks" required></textarea>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button id="saveEarlyFinalize" type="submit" class="btn btn-primary">Save changes</button>
    </div>
</form>

@include('AlpasalWarehouse::warehouse.warehouse-pre-orders.store-pre-orders.early-finalize.scripts.early-finalize-scripts')




