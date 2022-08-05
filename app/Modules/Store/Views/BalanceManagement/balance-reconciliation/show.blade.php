@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
    'page_title'=>$title,
    'sub_title'=> "Manage {$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route.'.reconciliation'),
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
                            <div>

                                @if($reconciliationDetail['status']=='used')
                                    <strong>status:</strong> <span class="label label-danger">Already used</span>
                                @else
                                    <strong>status:</strong> <span class="label label-success">Still Unused</span>
                                @endif
                            </div>

                            <br><strong>Balance Reconciliation Code: {{ $reconciliationDetail['balance_reconciliation_code'] }}</strong>
                            @if($reconciliationDetail->status =='used')
                            <strong>

                                    @if($reconciliationDetail->balanceReconciliationUsage->used_for =='locked')
                                          ( {{$reconciliationDetail->balanceReconciliationUsage->used_for}} )
                                    @else
                                       || Used For : {{ ucwords(str_replace('_',' ' ,$reconciliationDetail->balanceReconciliationUsage->used_for))}}
                                       (<a href="{{route('admin.wallet.offline-payment.load-balance.show',$reconciliationDetail->balanceReconciliationUsage->used_for_code)}}">
                                       {{$reconciliationDetail->balanceReconciliationUsage->used_for_code}}
                                       </a>)
                                    @endif


                            </strong>
                            @endif
                            <br><br>
                            <strong>Transaction Type: {{ ucfirst($reconciliationDetail['transaction_type']) }}</strong><br><br>
                            <strong>Payment By: {{ ucfirst($reconciliationDetail['payment_method']) }}</strong><br><br>


                            @if($reconciliationDetail['payment_method']=='bank')
                                <strong>Payment Body Name:  {{ $reconciliationDetail->getbankName->bank_name }}</strong><br><br>

                            @elseif($reconciliationDetail['payment_method']=='remit')
                                <strong>Payment Body Name:  {{ $reconciliationDetail->getRemitName->remit_name }}</strong><br><br>

                                @elseif($reconciliationDetail['payment_method'] =='digital_wallet')
                                <strong>Payment Body Name:  {{ $reconciliationDetail->getDigitalWalletName->wallet_name }}</strong><br><br>
                            @endif

                            <strong>Transaction Number:{{ $reconciliationDetail['transaction_no'] }}</strong><br><br>
                            <strong>Transaction Amount: Rs.{{ getNumberFormattedAmount($reconciliationDetail['transaction_amount']) }}</strong><br><br>
                            <strong>Transaction By: {{ $reconciliationDetail['transacted_by'] }}</strong><br><br>

                            <strong>Description:</strong><br>
                            {!! strip_tags($reconciliationDetail->description) !!}<br><br>



                            <strong>Transaction Date: {{date('d-M-Y',strtotime($reconciliationDetail->transaction_date))}}</strong><br><br>
                            <strong>Created Date: {{date('d-M-Y',strtotime($reconciliationDetail->created_at))}}</strong><br><br>
                            <strong>Updated Date: {{date('d-M-Y',strtotime($reconciliationDetail->updated_at))}}</strong><br><br>

                            <strong>Created By: {{  ucfirst($reconciliationDetail->createdBy->name)}} </strong><br><br>
                            <strong>Updated By: {{  isset($reconciliationDetail->updated_by) ? ucfirst($reconciliationDetail->updatedBy->name):'N/A'}} </strong><br><br>


                        </div>
                    </div>
                </div>

            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
@endsection
@push('scripts')
    @include('Store::admin.store-payment.misc.misc-scripts')
@endpush

