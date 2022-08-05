<div class="modal-header">
    <h4 class="modal-title" id="exampleModalLabel"><strong>Respond To</strong></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -60px;">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div id="showFlashMessageModal"></div>
<form method="post" id="formRespondSubscription" action="{{route('admin.investment-subscription.respondIS',$subscribedIP->ip_subscription_code)}}">
    @csrf
    <div class="modal-body">
        <div class="form-group">
            <label for="verification_status" class="control-label">Verification Status</label>
            <select id="verification_status" name="admin_status" class="form-control" required>
                <option value="">Select Status</option>
                <option value="accepted" {{old('status') == 'accepted' ? 'selected' : ''}}>Accept</option>
                <option value="rejected" {{old('status') == 'rejected' ? 'selected' : ''}}>Reject</option>
            </select>
        </div>
        <div class="form-group">
            <label class="control-label">Remarks</label>
            <textarea class="form-control input-sm" name="admin_remark" id="remark" required></textarea>
        </div>
        @if($subscribedIP->payment_mode == 'offline')
        <div class="form-group">
            <label style="background-color: lightgreen;">{{count($balanceReconciliation) }} Balance Reconciliation Record found.</label>
            <div id="scroll_top_bottom" style="overflow:scroll; height:200px;">
                <div class="box-header" style="background-color: lightgreen; color: black;">
                    <div class="box-body">
                        <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>BRCode</th>
                                <th>Transaction Date</th>
                                <th>Transaction no</th>
                                <th>Transacted By</th>
                                <th>Description</th>
                            </tr>
                            </thead>
                            @foreach($balanceReconciliation as $key => $balanceReconciliationDetail)
                                <tbody>
                                <tr>
                                    <td><input type="radio" class="radio_select_br_code" name="balance_reconciliation_code" value="{{$balanceReconciliationDetail->balance_reconciliation_code}}" required></td>
                                    <td><strong> {{$balanceReconciliationDetail->balance_reconciliation_code}}</strong></td>
                                    <td><strong> {{$balanceReconciliationDetail->transaction_date}}</strong></td>
                                    <td><strong> {{(isset($balanceReconciliationDetail->transaction_no))?$balanceReconciliationDetail->transaction_no:'N/A'}}</strong></td>
                                    <td><strong>{{isset($balanceReconciliationDetail->transacted_by)?ucfirst($balanceReconciliationDetail->transacted_by):'N/A'}}</strong></td>
                                    <td><strong> {{(isset($balanceReconciliationDetail->description))? strip_tags($balanceReconciliationDetail->description):'N/A'}} </strong> </td>
                                </tr>
                                </tbody>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button id="saveSubscriptionResponseData" type="submit" class="btn btn-primary">Save</button>
    </div>
</form>
@include('InvestmentPlan::Investment-plan-subscription.admin.partials.scripts')
