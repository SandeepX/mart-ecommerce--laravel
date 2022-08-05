<div class="col-xs-12">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">
                Online Payment Details
            </h3>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-3 col-lg-4">
                    <div class="form-group">
                        <label class="control-label">Online Payment Code</label>
                        <p>{{$subscribedIP->onlinePayment->online_payment_master_code}}</p>
                    </div>
                </div>
                <div class="col-md-3 col-lg-4">
                    <div class="form-group">
                        <label class="control-label">Payment Holder</label>
                        <p>{{$subscribedIP->onlinePayment->payment_initiator}}</p>
                    </div>
                </div>
                <div class="col-md-3 col-lg-4">
                    <div class="form-group">
                        <label class="control-label">Payment Holder Code</label>
                        <p>{{$subscribedIP->onlinePayment->payment_holder_name}} ({{$subscribedIP->onlinePayment->initiator_code}})</p>
                    </div>
                </div>
                <div class="col-md-3 col-lg-4">
                    <div class="form-group">
                        <label class="control-label">Transaction Id</label>
                        <p>{{$subscribedIP->onlinePayment->transaction_id}}</p>
                    </div>
                </div>
                <div class="col-md-3 col-lg-4">
                    <div class="form-group">
                        <label class="control-label">Request At</label>
                        <p>{{getReadableDate($subscribedIP->onlinePayment->request_at)}}</p>
                    </div>
                </div>

                <div class="col-md-3 col-lg-4">
                    <div class="form-group">
                        <label class="control-label">Response At</label>
                        <p>{{isset($subscribedIP->onlinePayment->response_at) ? getReadableDate($subscribedIP->onlinePayment->response_at): 'N/A'}}</p>
                    </div>
                </div>
                <div class="col-md-3 col-lg-4">
                    <div class="form-group">
                        <label class="control-label">Status</label>
                        <p>
                              <span class="label label-{{$status[$subscribedIP->onlinePayment->status]}}">
                                     {{ucfirst($subscribedIP->onlinePayment->status)}}
                              </span>
                        </p>
                    </div>
                </div>
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
                                    @foreach($subscribedIP->onlinePayment->paymentMetaData as $paymentMeta)
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
        </div>
    </div>
</div>
