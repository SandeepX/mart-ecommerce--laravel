<div class="modal-header">
    <h4 class="modal-title" id="exampleModalLabel"><strong>Edit Offline Payments</strong></h4>
    <p> <strong>{{ucwords(str_replace('_',' ',$offlinePayment->payment_type))}}</strong> (<small>{{$offlinePayment->offline_payment_code}}</small>)</p>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -60px;">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div id="showFlashMessageModal"></div>
<form method="post" id="formEditOfflinePayment" action="{{route('admin.offline-payment.update',$offlinePayment->offline_payment_code)}}">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="form-group">
            <label class="control-label">Transaction Date</label>
            <input class="form-control input-sm" type="date" name="transaction_date" value="{{$offlinePayment->transaction_date}}" id="transaction_date" required>
        </div>
        @foreach($transactionNumbers as $key => $transactionNumber)
            <div class="form-group">
                <label class="control-label">Transaction No</label>
                <input type="hidden" name="payment_meta_code[{{$key}}]" value="{{$transactionNumber->offline_payment_meta_code}}" required>
                <input class="form-control input-sm" type="text" name="transaction_number[{{$key}}]" value="{{$transactionNumber->value}}" required>
            </div>
        @endforeach

        @if($remarks)
            <div class="form-group">
                <label class="control-label">Remark</label>
                <input type="hidden" name="payment_meta_remark_code" value="{{$remarks->offline_payment_meta_code}}" required>
                <textarea class="form-control" name="remark">{{$remarks->value}}</textarea>
            </div>
        @endif

        <div class="form-group">
            <label class="control-label">Admin Description</label>
            <input type="hidden" name="payment_meta_admin_description_code" value="{{($adminDescription)?$adminDescription->offline_payment_meta_code:''}}" required>
            <textarea class="form-control" name="admin_description">{{ ($adminDescription) ? $adminDescription->value:''}}</textarea>
        </div>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
    </div>
</form>

@include('OfflinePayment::admin.offline-payments.scripts.update-payments-scripts')


