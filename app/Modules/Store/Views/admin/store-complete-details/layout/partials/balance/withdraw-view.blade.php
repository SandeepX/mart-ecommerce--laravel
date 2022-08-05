<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true"><i class="fa fa-times"></i></span></button>
    <h4 class="modal-title">Stores Balance Withdraw Request</h4>
</div>
<div class="modal-body">
    @if($withdrawrequestdetail['status'] != 'completed' || $withdrawrequestdetail['status'] != 'rejected')
        <div class="box-body" id="respond_form" {{old('status') ? '' :'hidden'}}>
            <form class="form-horizontal" role="form" id="verifyWithdrawRequest"
                  enctype="multipart/form-data"
                  action="{{route('admin.stores.balance-withdrawRequest.verify',$withdrawrequestdetail['store_balance_withdraw_request_code'])}}"
                  method="post" >

                {{csrf_field()}}

                <div class="box-body">

                    <div class="col-md-12">

                        <div class="form-group">
                            <label for="status" class="control-label">Verification
                                Status</label>
                            <select id="status" name="status"
                                    class="form-control" >
                                <option value="{{$withdrawrequestdetail['status']}}">{{ucfirst($withdrawrequestdetail['status'])}}</option>"

                                @if($withdrawrequestdetail['status'] == 'processing')
                                    <option value="completed"
                                        {{old('status') == 'completed' ? 'selected' : ''}}>
                                        Completed
                                    </option>
                                @endif

                                <option value="rejected"
                                    {{old('status') == 'rejected' ? 'selected' : ''}}>
                                    Rejected
                                </option>

                                <option value="processing"
                                    {{old('status') == 'processing' ? 'selected' : ''}}>
                                    processing
                                </option>



                            </select>
                        </div>

                        <div class="form-group">
                            <label for="remarks" class="control-label">Remarks</label>
                            <textarea id="remarks" class="form-control summernote" name="remarks"
                                      placeholder="Enter remarks">{{old('remarks')}}</textarea>
                        </div>

                        @if($withdrawrequestdetail['status'] == 'processing')
                            <div class="form-group">
                                <label for="documents" class="control-label">Documents</label>
                                <input id="document" type='file' class="form-control" name="document" />

                            </div>
                        @endif


                    </div>

                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                    <button type="submit" style="width: 49%;margin-left: 17%;"
                            class="btn btn-block btn-primary" id="saveWithRequest">Respond
                    </button>
                </div>
            </form>
        </div>
    @endif
    <div class="box-body">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    @if($withdrawrequestdetail['status'] != 'completed' && $withdrawrequestdetail['status'] != 'rejected')
                        <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">
                            <a href="javascript:void(0)" id="respond_btn" style="border-radius: 0px; " class="btn btn-sm btn-primary">
                                <i class="fa fa-reply"></i>
                                Respond
                            </a>
                        </div>
                    @endif

                </div>
                <div class="box-body">
                    <strong>Store Name: {{ucfirst($withdrawrequestdetail->store->store_name)}} </strong><br>
                    <strong>Store Phone Number: {{ucfirst($withdrawrequestdetail->store->store_contact_phone)}}/{{ucfirst($withdrawrequestdetail->store->store_contact_mobile)}} </strong><br>
                    <strong>store Email Address: {{$withdrawrequestdetail->store->store_email}}</strong><br><br>

                    <strong>Withdraw Reason:</strong>
                    <br> {{ucfirst($withdrawrequestdetail->reason)}} <br><br>
                    <strong>Requested withdraw Amount:Rs. {!! getNumberFormattedAmount($withdrawrequestdetail['requested_amount']) !!} </strong><br>

                    <br>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="row" style="padding:  20px 0 20px 0 ; margin: 0;">
    <div class="col-md-12 text-center">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</div>
