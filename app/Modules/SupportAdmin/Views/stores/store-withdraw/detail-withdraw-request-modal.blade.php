<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true"><i class="fa fa-times"></i></span></button>
    <h4 class="modal-title">Stores Balance Withdraw Request Detail</h4>
</div>
<div class="modal-body">

    <div class="box-body">
        <div class="col-md-12">
            <div class="box-body">
                <strong>Store Name: {{ucfirst($withdrawrequestdetail->store->store_name)}} </strong><br>
                <strong>Store Phone Number: {{ucfirst($withdrawrequestdetail->store->store_contact_phone)}}/{{ucfirst($withdrawrequestdetail->store->store_contact_mobile)}} </strong><br>
                <strong>store Email Address: {{$withdrawrequestdetail->store->store_email}}</strong><br><br>
                <strong>Remarks: {!! $withdrawrequestdetail['remarks'] !!} </strong><br>
                <strong>Status: {{ucfirst($withdrawrequestdetail['status'])}} </strong><br>
                <strong>Bank Name: {{$withdrawrequestdetail->getPaymentBodyName()}} </strong><br>
                <strong>Account No: {{$withdrawrequestdetail->account_no}} </strong><br>

                <strong>Withdraw Reason:</strong>
                <br> {{ucfirst($withdrawrequestdetail->reason)}} <br><br>
                <strong>Requested withdraw Amount: Rs. {!! getNumberFormattedAmount($withdrawrequestdetail['requested_amount']) !!} </strong><br>
                <br>
                <strong>Due Amount To Clear: {{$pendingAmount}} </strong><br>
                <strong>Created At: {{getReadableDate(getNepTimeZoneDateTime($withdrawrequestdetail->created_at),'Y-M-d')}} </strong><br>
            </div>
            @if($withdrawrequestdetail['status'] == 'completed' || $withdrawrequestdetail['status'] =='rejected')
                <div class="box-body">
                    <strong>Verified At: {{$withdrawrequestdetail['verified_at']}} </strong><br>
                    <strong>Verified By: {{$withdrawrequestdetail->verifiedBy ? $withdrawrequestdetail->verifiedBy->name:null}} </strong><br>
                    <strong>Verification status: {{ucfirst($withdrawrequestdetail['status'])}} </strong><br>
                    <strong>Remarks: {!! $withdrawrequestdetail['remarks'] !!} </strong><br>

                    <br>
                </div>
            @endif
        </div>
    </div>

</div>
<div class="row" style="padding:  20px 0 20px 0 ; margin: 0;">
    <div class="col-md-12 text-center">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</div>
