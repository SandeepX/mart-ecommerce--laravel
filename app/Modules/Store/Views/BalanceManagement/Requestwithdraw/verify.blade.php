@extends('Admin::layout.common.masterlayout')

@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
   'page_title'=> formatWords($title,false),
   'sub_title'=>'Manage '. formatWords($title,false),
   'icon'=>'home',
   'sub_icon'=>'',
   'manage_url'=>route($base_route.'withdraw'),

   ])
    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-primary">

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
                                        <strong>Remarks: {!! $withdrawrequestdetail['remarks'] !!} </strong><br>
                                        <strong>Status: {{$withdrawrequestdetail['status']}} </strong><br>
                                        <strong>Bank Name: {{$withdrawrequestdetail->getPaymentBodyName()}} </strong><br>
                                        <strong>Account No: {{$withdrawrequestdetail->account_no}} </strong><br>

                                        <strong>Withdraw Reason:</strong>
                                        <br> {{ucfirst($withdrawrequestdetail->reason)}} <br><br>
                                        <strong>Requested withdraw Amount:Rs. {!! getNumberFormattedAmount($withdrawrequestdetail['requested_amount']) !!} </strong><br>
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
                                                        @if(isset($status) && count($status) > 0)
                                                            <option value="">Select Status</option>
                                                         @foreach($status as $value)
                                                        <option value="{{$value}}"
                                                            {{old('status') == $value ? 'selected' : ''}}>
                                                            {{$value}}
                                                        </option>
                                                        @endforeach
                                                        @endif
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="remarks" class="control-label">Remarks</label>
                                                    <textarea id="remarks" class="form-control summernote" name="remarks"
                                                              placeholder="Enter remarks">{{old('remarks')}}</textarea>
                                                </div>



                                            </div>
                                            @if(($withdrawrequestdetail['status'] == 'processing' || $withdrawrequestdetail['status'] == "pending") && $pendingAmount >0)
                                                {{--                                                    <div class="form-group">--}}
                                                {{--                                                        <label for="documents" class="control-label">Documents</label>--}}
                                                {{--                                                        <input id="document" type='file' class="form-control" name="document" />--}}

                                                {{--                                                    </div>--}}
                                            <div id="addmoreTable">
                                                    <table class="table table-bordered" id="dynamicTable">
                                                        <tr>
                                                            <th>Payment Verification Source</th>
                                                            <th>Amount</th>
                                                            <th>Proof</th>
                                                            <th>Remarks</th>
                                                        </tr>
                                                        <tr>
                                                            <td><input type="text" name="addmore[0][payment_verification_source]" placeholder="eg.CHQ1234(cheque no)"  class="form-control" /></td>
                                                            <td><input type="text" name="addmore[0][amount]" class="form-control" /></td>
                                                            <td><input type="file" name="addmore[0][proof]" class="form-control" /></td>
                                                            <td><input type="text" name="addmore[0][remarks]" class="form-control" /></td>
                                                        </tr>

                                                    </table>
                                                    <div class="row">
                                                        <div class="col-md-12 text-center">
                                                            <button type="button" name="add" id="add" class="btn btn-success">Add More</button>
                                                        </div>
                                                    </div>
                                            </div>
                                            @endif
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


                        @if(isset($withdrawRequestVerificationDetail) && $withdrawRequestVerificationDetail->count())
                            <h3>List of Withdraw Verification Detail</h3>
                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Bank Name</th>
                                    <th>Payment Method</th>
                                    <th>Amount</th>
                                    <th>payment_verification_source</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    @if($withdrawrequestdetail['status'] == "processing")
                                    <th>Action</th>
                                     @endif
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($withdrawRequestVerificationDetail as $i => $withdrawRequestVerification)
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>{{ucfirst($withdrawRequestVerification->getPaymentBodyName())}}</td>
                                        <td> {{ucfirst($withdrawRequestVerification->payment_method)}}</td>
                                        <td>{{roundPrice($withdrawRequestVerification->amount)}}</td>
                                        <td> {{ucfirst($withdrawRequestVerification->payment_verification_source)}}</td>
                                        <td> {{$withdrawRequestVerification->status}}</td>
                                        <td> {{getReadableDate(getNepTimeZoneDateTime($withdrawRequestVerification->created_at),'Y-M-d')}}</td>
                                        @php
                                            if($withdrawRequestVerification->status == 'passed'){
                                                 $color = 'success';
                                                 $status = 'Approved';
                                             }else{
                                                 $color = "danger";
                                                 $status = 'Rejected';
                                             }
                                        @endphp
                                        @if($withdrawrequestdetail['status'] == "processing")
                                        <td>
                                            {{--                                            @canany('Verify Store Balance Withdraw Request')--}}

                                            {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction($status,route('admin.balance.withdraw-verification-detail.change-status',$withdrawRequestVerification->withdraw_request_verification_details_code),'Change Status', '',$color)!!}
                                            {{--                                            @endcanany--}}
                                        </td>
                                        @endif
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
                        @endif



                    </div>
                    <!-- /.box -->
                </div>
                <!--/.col (left) -->

            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
@endsection
@push('scripts')
    @include('Store::admin.store-payment.misc.misc-scripts')
    @includeIf('Store::BalanceManagement.Requestwithdraw.verification-detail-script');
        <script>
            $('#verifyWithdrawRequest').submit(function (e, params) {
                var localParams = params || {};

                if (!localParams.send) {
                    e.preventDefault();
                }


                Swal.fire({
                    title: 'Are you sure you want to save the changes ?',
                    showCancelButton: true,
                    confirmButtonText: `Yes`,
                    padding:'10em',
                    width:'500px'
                }).then((result) => {
                    if (result.isConfirmed) {

                        $(e.currentTarget).trigger(e.type, { 'send': true });
                        Swal.fire({
                            title: 'Please wait...',
                            hideClass: {
                                popup: ''
                            }
                        })
                    }
                })
            });
            $(document).ready(function()
            {
                $('#status').change(function(){
                    var status = $('#status option:selected').val();
                    console.log(status)
                    if(status === "rejected")
                    {
                        $('#addmoreTable').hide();
                    }else {
                        $('#addmoreTable').show();
                    }
                })
            });
        </script>


@endpush

