<div class="modal-header">
    <h4 class="modal-title" id="exampleModalLabel"><strong>Add Remarks</strong></h4>
    <p> <strong>{{ucwords(str_replace('_',' ',$offlinePayment->payment_type))}}</strong> (<small>{{$offlinePayment->offline_payment_code}}</small>)</p>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -60px;">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div id="showFlashMessageModal"></div>
<form method="post" id="formOfflinePaymentRemarks" action="{{route('admin.offline-payment.remarks.save',$offlinePayment->offline_payment_code)}}">
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

@include('OfflinePayment::admin.offline-payments.remarks.scripts.add-remarks-scripts')

