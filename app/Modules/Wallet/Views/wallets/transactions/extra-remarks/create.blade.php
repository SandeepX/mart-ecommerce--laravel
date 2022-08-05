<div class="modal-header">
    <h4 class="modal-title" id="exampleModalLabel"><strong>Add Remarks</strong></h4>
    <p>Wallet Transaction: {{$walletTransaction->wallet_transaction_code}}(<small>{{$walletTransaction->reference_code}}</small>)</p>
    <p>Purpose: {{$walletTransaction->walletTransactionPurpose->purpose}}</p>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -60px;">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div id="showFlashMessageModal"></div>
<form method="post" id="fromWalletTransactionRemarks" action="{{route('admin.wallets.transaction.extra-remarks.save',$walletTransaction->wallet_transaction_code)}}">
    @csrf
    <div class="modal-body">
        <div class="form-group">
            <textarea class="form-control input-sm" name="remark" id="remark" required></textarea>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button id="saveStoreBalanceControl" type="submit" class="btn btn-primary">Save changes</button>
    </div>
</form>

@include('Wallet::wallets.transactions.extra-remarks.scripts.add-extra-remarks-scripts')

