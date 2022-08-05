<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true"><i class="fa fa-times"></i></span></button>
    <h4 class="modal-title">Stores Miscellaneous Payment </h4>
</div>

<div class="modal-body">
    <div class="box box-primary">
        @include("Admin::layout.partials.flash_message")
        <div id="showFlashMessage"></div>
        <div class="box-body">
            <div class="col-md-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <span style="font-size: 15px;" class="label label-successlabel label-{{config('kyc_verification_statuses.labels.'.$storePayment['verification_status'])}}">
                            Status: {{$storePayment['verification_status']}}
                        </span>

                        @can('Verify Store Miscellaneous Payment')

                            @if($storePayment['verification_status'] == 'pending' )
                                <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">
                                    <a href="javascript:void(0)" id="respond_btn" style="border-radius: 0px; " class="btn btn-sm btn-primary">
                                        <i class="fa fa-reply"></i>
                                        Respond
                                    </a>
                                </div>
                            @endif
                        @endcan


                        <div class="pull-right" style="margin-top: -5px;margin-right: 370px;">
                            <div id="#" style="border-radius: 0px; " class="btn btn-sm btn-success">
                                <i class="fa fa-money"></i>
                                Current Balance: <strong>{{ ($currentBalance) }}</strong>
                            </div>
                        </div>
                    </div>


                    @if($storePayment['verification_status'] !== 'pending' )
                        <div class="box-body">
                            <strong>Last Responded At: {{$storePayment['responded_at']}} </strong><br>
                            <strong>Responded By: {{$storePayment['responded_by']}} </strong><br>
                            <strong>Remarks: {!! $storePayment['remarks'] !!} </strong><br>
                            @if($storePayment['verification_status'] == 'verified')
                                Verification Source : @if($balanceReconciliationUsage)
                                    <a href="{{route('admin.balance.reconciliation.show',$balanceReconciliationUsage->balance_reconciliation_code)}}" target="_blank">{{ $balanceReconciliationUsage->balance_reconciliation_code }} </a>
                                @else
                                    N/A
                                @endif
                            @endif
                        </div>
                    @endif

                </div>
            </div>
        </div>


{{--        respond form--}}

        @can('Verify Store Miscellaneous Payment')
            @if($storePayment['verification_status'] == 'pending')

                <div class="box-body" id="respond_form" {{old('verification_status') ? '' :'hidden'}}>
                    <form class="form-horizontal" role="form" id="formVerification"
                          action="{{route('admin.stores.misc-payments.respond',$storePayment['store_misc_payment_code'])}}"
                          method="post">

                        {{csrf_field()}}

                        <div class="box-body">

                            <div class="col-md-12">

                                <div class="form-group">
                                    <label for="verification_status" class="control-label">Verification
                                        Status</label>
                                    <select id="verification_status" name="verification_status"
                                            class="form-control" required >
                                        <option value="">select status</option>
                                        <option value="verified"
                                            {{old('verification_status') == 'verified' ? 'selected' : ''}}>
                                            Verify
                                        </option>
                                        <option value="rejected"
                                            {{old('verification_status') == 'rejected' ? 'selected' : ''}}>
                                            Reject
                                        </option>

                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="remarks" class="control-label">Remarks</label>
                                    <textarea id="remarks" class="form-control summernote" name="remarks"
                                              placeholder="Enter remarks">{{old('remarks')}}</textarea>
                                </div>

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

                            </div>

                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button type="button" style="width: 49%;margin-left: 17%;" id="saveMiscPayment"
                                    class="btn btn-block btn-primary">Save
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        @endcan

{{--        end of respond form--}}


        @can('Show Store Miscellaneous Payment')
            <div class="box-body">
                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="box box-success">
                            <div class="box-header with-border">

                                            <span style="font-size: 15px;" class="label label-primary">
                                                 Payment Detail
                                             </span>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-sm-9">
                                        <ul class="list-group list-group-unbordered">
                                            <li class="list-group-item">
                                                <b>Store Misc Payment Code</b> <a
                                                    class="pull-right">{{$storePayment['store_misc_payment_code']}}</a>
                                            </li>

                                            <li class="list-group-item">
                                                <b>Payment For</b> <a
                                                    class="pull-right">{{$storePayment['payment_for']}}</a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Store</b> <a class="pull-right">
                                                    {{$storePayment['store_name']}}
                                                    - {{$storePayment['store_code']}}
                                                </a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Submitted By</b> <a
                                                    class="pull-right">{{$storePayment['submitted_by']}}</a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Payment Type</b> <a
                                                    class="pull-right">{{$storePayment['payment_type']}}</a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Deposited By</b> <a
                                                    class="pull-right">{{$storePayment['deposited_by']}}</a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Purpose</b> <a
                                                    class="pull-right">{{$storePayment['purpose']}}</a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Amount</b> <a
                                                    class="pull-right">{{getNumberFormattedAmount($storePayment['amount'])}}</a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Voucher/Transaction Number</b> <a
                                                    class="pull-right">{{$storePayment['voucher_number']}}</a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Transaction Date</b> <a
                                                    class="pull-right">{{$storePayment['transaction_date']}}</a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Contact phone Number</b> <a
                                                    class="pull-right">{{!empty($storePayment['contact_phone_no'])?$storePayment['contact_phone_no']:'N/A'}}</a>
                                            </li>

                                        </ul>
                                    </div>
                                </div>

                            </div>
                            <!-- /.box-body -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="box box-success">
                                <div class="box-header with-border">

                                        <span style="font-size: 15px;" class="label label-danger">
                                           Meta Detail
                                         </span>

                                </div>
                                <div class="box-body">
                                    <ul class="list-group list-group-unbordered">
                                        @foreach($storePayment['payment_meta'] as $metaDetail)
                                            <li class="list-group-item">
                                                <b>{{$metaDetail['key']}}</b> <a class="pull-right">
                                                    {{$metaDetail['value']}}
                                                </a>
                                            </li>
                                        @endforeach

                                    </ul>
                                </div>
                                <!-- /.box-body -->
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="box box-success">
                                <div class="box-header with-border">

                                            <span style="font-size: 15px;" class="label label-danger">
                                               Documents
                                             </span>

                                </div>
                                <div class="box-body">
                                    <ul class="list-group list-group-unbordered">

                                        @foreach($storePayment['payment_documents'] as $paymentDocument)
                                            <li class="list-group-item">
                                                <b>{{convertToWords($paymentDocument['document_type'],'_')}}</b>

                                                <a href="{{$paymentDocument['file_name']}}"
                                                   class="pull-right" download>
                                                    Download
                                                </a>


                                                @if(hasImageExtension($paymentDocument['file_name']))
                                                    <a href="{{$paymentDocument['file_name']}}"
                                                       class="pull-right" target="_blank">
                                                        View
                                                    </a>
                                                @endif
                                            </li>
                                        @endforeach

                                    </ul>
                                </div>
                                <!-- /.box-body -->
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        @endcan

    </div>
</div>
<div class="row" style="padding: 20px 0 20px 0; margin: 0;">
    <div class="col-md-12 text-center">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
    </div>
</div>

@push('scripts')
    @include('Store::admin.store-payment.misc.misc-scripts')
    <script>
        $('#verification_status').change(function (e){
            e.preventDefault();

            var brRadioSelects = document.getElementsByClassName("radio_select_br_code");

            var status = $(this).val();
            if(status === 'verified'){
                for(var i=0; i<brRadioSelects.length; i++) {
                    brRadioSelects[i].required = true
                }
            }

            if(status === 'rejected'){
                for(var i=0; i<brRadioSelects.length; i++) {
                    brRadioSelects[i].required = false
                }
            }
        })

        $('#saveMiscPayment').click(function (e){
            Swal.fire({
                title: 'Do you want to save the changes ?',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: `Save`,
                denyButtonText: `Don't save`,
                padding:'10em',
                width:'500px'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#formVerification').submit();
                    Swal.fire('Saved!', '', 'success')
                } else if (result.isDenied) {
                    Swal.fire('Changes are not saved', '', 'info')
                }
            })

        })



    </script>



@endpush
