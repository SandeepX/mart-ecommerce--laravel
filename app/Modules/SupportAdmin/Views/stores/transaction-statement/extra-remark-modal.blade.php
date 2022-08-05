<div class="modal-header">
    <p>Wallet Transaction: {{$walletTransaction->wallet_transaction_code}}(<small>{{$walletTransaction->reference_code}}</small>)</p>
    <p>Purpose: {{$walletTransaction->walletTransactionPurpose->purpose}}</p>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -60px;">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">SN</th>
                <th scope="col">Remarks</th>
                <th scope="col">Created At</th>
            </tr>
            </thead>
            <tbody>
            @forelse($remarks as $remarkData)
                <tr>
                    <th scope="row">{{$loop->index + 1}}</th>
                    <td>{{$remarkData->remark}}</td>
                    <td>{{getReadableDate(getNepTimeZoneDateTime($remarkData->created_at))}}</td>
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

</div>
<div class="row" style="padding:  20px 0 20px 0 ; margin: 0;">
    <div class="col-md-12 text-center">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</div>


