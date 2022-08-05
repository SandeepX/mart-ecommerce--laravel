<div class="modal fade" id="balance-reconciliation-remarks-{{$detail->balance_reconciliation_code}}" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">
                    Balance Reconciliation Code: {{$detail->balance_reconciliation_code}}
                    <br/>  <small>Add remarks for the usage.</small>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="fromBalanceReconciliationUpdateRemarks" method="post" action="{{route('admin.balance.reconciliation.usages.create.remarks',$detail->balanceReconciliationUsage->balance_reconciliation_usages_code)}}">
                <div class="modal-body">
                    @csrf
                    @method('PUT')
                    <label class="control-label">Remarks</label>
                    <textarea class="form-control" name="remark" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success  update-usages-remarks">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>






