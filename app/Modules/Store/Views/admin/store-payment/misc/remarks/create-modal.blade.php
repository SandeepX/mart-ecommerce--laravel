<div class="modal-header">
    <h4 class="modal-title" id="exampleModalLabel"><strong>Add Remarks</strong></h4>
    <p> <strong>{{ucwords(str_replace('_',' ',$storePayment->payment_type))}}</strong> (<small>{{$storePayment->store_misc_payment_code}}</small>)</p>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -60px;">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div id="showFlashMessageModal"></div>
<form method="post" id="formMiscPaymentRemarks" action="{{route('admin.stores.misc-payments.remarks.save',$storePayment->store_misc_payment_code)}}">
    @csrf
    <div class="modal-body">
        <div class="form-group">
            <textarea class="form-control input-sm" name="remark" id="remark" required></textarea>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button id="saveStoreBalanceControl" type="submit" class="btn btn-primary">Save</button>
    </div>
</form>

@include('Store::admin.store-payment.misc.remarks.scripts.add-remarks-scripts')

