<div class="col-xs-12">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">
                Offline Payment Details
            </h3>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-3 col-lg-4">
                    <div class="form-group">
                        <label class="control-label">Offline Payment Code</label>
                        <p>{{$subscribedIP->offlinePayment->offline_payment_code}}</p>
                    </div>
                </div>

                <div class="col-md-3 col-lg-4">
                    <div class="form-group">
                        <label class="control-label">Payment Holder</label>
                        <p>{{ucfirst($subscribedIP->offlinePayment->payment_holder_type)}}</p>
                    </div>
                </div>

                <div class="col-md-3 col-lg-4">
                    <div class="form-group">
                        <label class="control-label">Payment Holder Code</label>
                        <p>{{$subscribedIP->offlinePayment->payment_holder_name}} ({{$subscribedIP->offlinePayment->offline_payment_holder_code}})</p>
                    </div>
                </div>

                <div class="col-md-3 col-lg-4">
                    <div class="form-group">
                        <label class="control-label">Payment Type</label>
                        <p>{{ucwords(str_replace('_',' ',$subscribedIP->offlinePayment->payment_type))}}</p>
                    </div>
                </div>

                <div class="col-md-3 col-lg-4">
                    <div class="form-group">
                        <label class="control-label">Deposited By</label>
                        <p>{{$subscribedIP->offlinePayment->deposited_by}}</p>
                    </div>
                </div>

                <div class="col-md-3 col-lg-4">
                    <div class="form-group">
                        <label class="control-label">Transaction Date</label>
                        <p>{{$subscribedIP->offlinePayment->transaction_date}}</p>
                    </div>
                </div>

                <div class="col-md-3 col-lg-4">
                    <div class="form-group">
                        <label class="control-label">Contact No</label>
                        <p>{{$subscribedIP->offlinePayment->contact_phone_no}}</p>
                    </div>
                </div>

                <div class="col-md-3 col-lg-4">
                    <div class="form-group">
                        <label class="control-label">Amount</label>
                        <p>{{getNumberFormattedAmount($subscribedIP->offlinePayment->amount)}}</p>
                    </div>
                </div>

                <div class="col-md-3 col-lg-4">
                    <div class="form-group">
                        <label class="control-label">Verification Status</label>
                        <p>  <span class="label label-{{$status[$subscribedIP->offlinePayment->verification_status]}}">
                                           {{ucfirst($subscribedIP->offlinePayment->verification_status)}}
                                        </span></p>
                    </div>
                </div>

                @if($subscribedIP->offlinePayment->responded_by)
                    <div class="col-md-3 col-lg-4">
                        <div class="form-group">
                            <label class="control-label">Responded By</label>
                            <p> {{$subscribedIP->offlinePayment->respondedBy->name}}</p>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-4">
                        <div class="form-group">
                            <label class="control-label">Responded at</label>
                            <p> {{getReadableDate($subscribedIP->offlinePayment->responded_at)}}</p>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-4">
                        <div class="form-group">
                            <label class="control-label">Remarks</label>
                            <p> {!! $subscribedIP->offlinePayment->remarks !!}</p>
                        </div>
                    </div>
                @endif
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-default">
                        <div class="card-header">
                            <h4 class="card-title">
                                <a href="javascript:void(0)">
                                    <b>Payment Meta</b>
                                </a>
                            </h4>
                        </div>
                        <div id="collapse2" class="collapse show">
                            <div class="card-body">
                                <div class="row">
                                    @foreach($subscribedIP->offlinePayment->paymentMetaData as $paymentMeta)
                                    <div class="col-md-3 col-lg-4">
                                        <div class="form-group">
                                            <label class="control-label">{{ucwords(str_replace('_',' ',$paymentMeta->key))}}</label>
                                            <p>{{$paymentMeta->value}}</p>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-default">
                        <div class="card-header">
                            <h4 class="card-title">
                                <a href="javascript:void(0)">
                                    <b>Payment Documents</b>
                                </a>
                            </h4>
                        </div>
                        <div id="collapse2" class="collapse show">
                            <div class="card-body">
                                <div class="row">
                                    @foreach($subscribedIP->offlinePayment->paymentDocuments as $paymentDocument)
                                        <div class="col-md-3 col-lg-4">
                                            <div class="form-group">
                                                <label class="control-label">{{ucwords(str_replace('_',' ',$paymentDocument->document_type))}}</label>
                                                <p><a target="_blank" href="{{photoToUrl($paymentDocument->file_name,url('uploads/offline/payments/'))}}">{{$paymentDocument->file_name}}</a></p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

