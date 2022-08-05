<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true"><i class="fa fa-times"></i></span></button>
    <h4 class="modal-title">
        <b>Payment Detail Of store</b> : {{$storePayment['store_name']}}-{{$storePayment['store_code']}}


    </h4>

    <span style="font-size: 15px;" class="label label-{{config('kyc_verification_statuses.labels.'.$storePayment['verification_status'])}}">
                Status: {{$storePayment['verification_status']}}
     </span>

    <div class="pull-right" style="margin-top: -5px;margin-right: 370px;">
        <div id="#" style="border-radius: 0px; " class="btn btn-sm btn-success">
            <i class="fa fa-money"></i>
            Current Balance: <strong>{{ ($currentBalance) }}</strong>
        </div>
    </div>

</div>
<div class="modal-body">
    <div class="box-body">
        @if($storePayment['verification_status'] !== 'pending' )
            <div class="box-body">
                <strong>Last Responded At: {{$storePayment['responded_at']}} </strong><br>
                <strong>Responded By: {{$storePayment['responded_by']}} </strong><br>
                <strong>Remarks: {!! $storePayment['remarks'] !!} </strong><br>
            </div>
        @endif
    </div>
    <div class="box-body">

        <div class="col-md-12">
            <div id="myCarousel" class="carousel slide" data-ride="carousel">
                <!-- Indicators -->
                <ol class="carousel-indicators">
                    @foreach($storePayment['payment_documents'] as $key => $paymentDocument)
                        <li data-target="#myCarousel" data-slide-to="{{$key}}"
                            class="{{($key==0)?'active':''}}"></li>
                    @endforeach
                </ol>


                <!-- Wrapper for slides -->
                <div class="carousel-inner">
                    @foreach($storePayment['payment_documents'] as $key => $paymentDocument)
                        <div class="item {{($key==0)?'active':''}}">
                            <img src="{{$paymentDocument['file_name']}}" alt="{{convertToWords($paymentDocument['document_type'],'_')}}"
                                 style="width:100%;height:300px;">
                            <div class="carousel-caption">
                                <h3>{{convertToWords($paymentDocument['document_type'],'_')}}</h3>
                            </div>
                        </div>
                    @endforeach
                </div>


                <!-- Left and right controls -->
                <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#myCarousel" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>


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
{{--                    <div class="col-md-6">--}}
{{--                        <div class="box box-success">--}}
{{--                            <div class="box-header with-border">--}}

{{--                                            <span style="font-size: 15px;" class="label label-danger">--}}
{{--                                               Documents--}}
{{--                                             </span>--}}

{{--                            </div>--}}
{{--                            <div class="box-body">--}}
{{--                                <ul class="list-group list-group-unbordered">--}}

{{--                                    @foreach($storePayment['payment_documents'] as $paymentDocument)--}}
{{--                                        <li class="list-group-item">--}}
{{--                                            <b>{{convertToWords($paymentDocument['document_type'],'_')}}</b>--}}

{{--                                            <a href="{{$paymentDocument['file_name']}}"--}}
{{--                                               class="pull-right" download>--}}
{{--                                                Download--}}
{{--                                            </a>--}}

{{--                                            @if(hasImageExtension($paymentDocument['file_name']))--}}
{{--                                                <a href="{{$paymentDocument['file_name']}}"--}}
{{--                                                   class="pull-right" target="_blank">--}}
{{--                                                    View--}}
{{--                                                </a>--}}
{{--                                            @endif--}}
{{--                                        </li>--}}
{{--                                    @endforeach--}}

{{--                                </ul>--}}
{{--                            </div>--}}
{{--                            <!-- /.box-body -->--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </div>
            </div>
        </div>
</div>






