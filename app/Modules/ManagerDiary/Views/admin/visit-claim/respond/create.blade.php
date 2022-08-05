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
        <strong>Visit Claim Form :  #{{$storeVisitClaimRequest->store_visit_claim_request_code}}</strong><br/>
    </h4>
    <p>
        {{isset($storeVisitClaimRequest->managerDiary) ? $storeVisitClaimRequest->managerDiary->store_name : 'N/A'}}
        -
        {{  isset($storeVisitClaimRequest->managerDiary->referred_store_code) ? $storeVisitClaimRequest->managerDiary->referredStore->store_name.' ('.$storeVisitClaimRequest->managerDiary->referred_store_code.')' : 'N/A' }}
    </p>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -30px;">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div id="showFlashMessageModal"></div>
<form method="post" id="formVisitClaimRespond" action="{{route('admin.store-visit-claim-requests.respond',$storeVisitClaimRequest->store_visit_claim_request_code)}}">
    @csrf
    <div class="modal-body">
        <div class="form-group">
            <label class="control-label">Status</label>
            <select class="form-control" name="status" id="status" required>
                <option value="">Select Status</option>
                <option value="verified">Verified</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>
        <div class="form-group">
            <label class="control-label">Remarks</label>
            <textarea class="form-control input-sm" name="remarks" id="remarks" required></textarea>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button id="saveVisitClaimRespond" type="submit" class="btn btn-primary">Save changes</button>
    </div>
</form>

@include('ManagerDiary::admin.visit-claim.respond.scripts.visit-claim-scripts')




