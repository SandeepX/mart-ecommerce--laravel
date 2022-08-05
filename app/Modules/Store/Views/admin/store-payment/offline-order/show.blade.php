@extends('Admin::layout.common.masterlayout')

@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
   'page_title'=> formatWords($title,false),
   'sub_title'=>'Manage '. formatWords($title,false),
   'icon'=>'home',
   'sub_icon'=>'',
   'manage_url'=>route($base_route.'index'),

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

                                        <span style="font-size: 15px;" class="label label-{{config('kyc_verification_statuses.labels.'.$storePayment['payment_status'])}}">
                                            Status: {{$storePayment['payment_status']}}
                                         </span>

                                        @can('Verify Store Order Offline Payment')
                                            @if($storePayment['payment_status'] == 'pending' )
                                                <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">
                                                    <a href="javascript:void(0)" id="respond_btn" style="border-radius: 0px; " class="btn btn-sm btn-primary">
                                                        <i class="fa fa-reply"></i>
                                                        Respond
                                                    </a>
                                                </div>
                                            @endif
                                        @endcan

                                    </div>
                                    @if($storePayment['payment_status'] != 'pending')
                                        <div class="box-body">
                                            <strong>Last Responded At: {{$storePayment['responded_at']}} </strong><br>
                                            <strong>Responded By: {{$storePayment['responded_by']}} </strong><br>
                                            <strong>Remarks: {!! $storePayment['remarks'] !!} </strong><br>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @can('Verify Store Order Offline Payment')
                        @if($storePayment['payment_status'] == 'pending')
                            <div class="box-body" id="respond_form" {{old('payment_status') ? '' :'hidden'}}>
                                <form class="form-horizontal" role="form"
                                      action="{{route('admin.stores.offline-order-payments.respond',$storePayment['store_offline_payment_code'])}}"
                                      method="post">
                                    {{csrf_field()}}

                                    <div class="box-body">

                                        <div class="col-md-12">

                                            <div class="form-group">
                                                <label for="payment_status" class="control-label">Verification
                                                    Status</label>
                                                <select id="payment_status" name="payment_status"
                                                        class="form-control">
                                                    <option value="verified"
                                                            {{old('payment_status') == 'verified' ? 'selected' : ''}}>
                                                        Verify
                                                    </option>
                                                    <option value="rejected"
                                                            {{old('payment_status') == 'rejected' ? 'selected' : ''}}>
                                                        Reject
                                                    </option>

                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="remarks" class="control-label">Remarks</label>
                                                <textarea id="remarks" class="form-control summernote" name="remarks"
                                                          placeholder="Enter remarks">{{old('remarks')}}</textarea>
                                            </div>

                                        </div>

                                    </div>
                                    <!-- /.box-body -->

                                    <div class="box-footer">
                                        <button type="submit" style="width: 49%;margin-left: 17%;"
                                                class="btn btn-block btn-primary">Respond
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endif
                        @endcan

                        @can('Show Store Order Offline Payment')
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
                                                                <b>Order Code</b> <a
                                                                        class="pull-right">{{$storePayment['store_order_code']}}</a>
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
                                                                        class="pull-right">{{$storePayment['amount']}}</a>
                                                            </li>
                                                            <li class="list-group-item">
                                                                <b>Voucher/Transaction Number</b> <a
                                                                        class="pull-right">{{$storePayment['voucher_number']}}</a>
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
@include('Store::admin.store-payment.offline-order.offline-scripts')
@endpush