@extends('Admin::layout.common.masterlayout')

@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
   'page_title'=> formatWords($title,false),
   'sub_title'=>'Manage '. formatWords($title,false),
   'icon'=>'home',
   'sub_icon'=>'',
   'manage_url'=>route($base_route.'.index'),
   ])
    <!-- Main content -->
        <section class="content">
            @include('Admin::layout.partials.flash_message')
            <div id="showFlashMessage"></div>
            <br>
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default">

                        <div class="panel-body">
                            <form action="{{route('admin.wallet.transactions.store.details',$wallet->wallet_code)}}" method="get">

                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label for="wallet_transaction_code">Transaction Code</label>
                                        <input type="text" class="form-control" name="wallet_transaction_code" id="wallet_transaction_code"
                                               value="{{$filterParameters['wallet_transaction_code']}}">
                                    </div>
                                </div>

                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label for="payment_date_from">Transation  Date From</label>
                                        <input type="date" class="form-control" name="transaction_date_from" id="transaction_date_from"
                                               value="{{$filterParameters['transaction_date_from']}}">
                                    </div>
                                </div>

                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label for="payment_date_to">Transaction Date To</label>
                                        <input type="date" class="form-control" name="transaction_date_to" id="transaction_date_to"
                                               value="{{$filterParameters['transaction_date_to']}}">
                                    </div>
                                </div>

                                <div class="col-xs-6">
                                    <div class="form-group">

                                        <label for="transaction_type">Transaction Type</label>
                                        <select name="transaction_type" class="form-control select2" id="transaction_type">
                                            <option value="" {{ $filterParameters['transaction_type']=='' ? 'selected':'' }}>All</option>
                                            @foreach($transactionPurposes as $transactionPurpose)
                                                <option value="{{$transactionPurpose->slug}}" {{$transactionPurpose->slug == $filterParameters['transaction_type'] ?'selected' :''}}>
                                                    {{$transactionPurpose->purpose}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-block btn-primary form-control">Filter</button>
                            </form>
                        </div>
                    </div>

                </div>


                <div class="col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div style="font-size:15px" class="panel-title">
                                <span>Transactions of  Store :{{$wallet->holder_name}} / {{$wallet->wallet_holder_code}}</span>
                                <span > | Total  Balance : {{getNumberFormattedAmount($wallet->current_balance)}}</span>
                                <span > | Active Balance : {{getNumberFormattedAmount($activeBalance)}}</span>
                                <span > | Frozen Balance <a href="#" data-toggle="tooltip" data-html = "true" style="color: white" data-placement="Sources"
                                                            title="Withdraw Amount : {{ getNumberFormattedAmount($frozenBalanceDetails['total_withdraw_freeze']) }}
                                                                <br>Pre Order Amount : {{ getNumberFormattedAmount($frozenBalanceDetails['total_preorder_freeze']) }} ">
                                     <i class="fa fa-info-circle"></i>
                                    </a> :{{ getNumberFormattedAmount($frozenBalanceDetails['total_freeze_amount']) }}
                                </span>
                                <a data-href="{{route('admin.wallet.transactions.control.store.create',$wallet->wallet_code)}}" style="border-radius: 3px; " class="btn btn-xs btn-info pull-right" data-toggle="modal" data-target="#exampleModal">
                                        <i class="fa fa-plus-circle"></i>
                                        Add New Transaction Control
                                </a>

                            </div>
                            <a href="{{route('admin.wallet.transactions.store.dispatch.details',$wallet->wallet_code)}}" class="btn btn-info btn-xs">Dispatch TXN</a>
                            <a href="{{route('admin.wallet.transactions.excel-export',$wallet->wallet_code)}}" class="btn btn-info btn-xs" >
                                Excel Export</a>
                        </div>

                        <div class="box-body">
                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="text-center">Transaction Code <br> <small>(Reference Code)</small></th>
                                    <th>Referenced Transaction Code</th>
                                    <th>Date</th>
                                    <th>Purpose</th>
                                    <th>DR.</th>
                                    <th>CR.</th>
                                    <th>Total Current Balance</th>
                                    <th>Remarks</th>
                                    <th>Document</th>
                                    <th>Ex.Remarks</th>
                                </tr>
                                </thead>
                                <tbody>

                                @forelse($allTransactionByWalletCode as $i => $allTransaction)
                                    <?php
                                        $correctionTransaction = $allTransaction->getAllTransactionCorrectionReferenceCode($allTransaction->wallet_transaction_code)
                                     ?>
                                    <tr>
                                        <td>{{$loop->index+1}}</td>
                                        <td class="text-center">
                                            {{$allTransaction->wallet_transaction_code}}
                                            <br>
                                            <small>({{$allTransaction->reference_code}}) </small>
                                        </td>
                                        <td>
                                            <small>
                                                @forelse($correctionTransaction as $key => $value)
                                                   <a>
                                                      {{$value->wallet_transaction_code}}
                                                   </a>
                                                @empty
                                                   {{ 'N/A' }}
                                                @endforelse
                                            </small>

                                        </td>
                                        <td>{{date('d-M-Y',strtotime($allTransaction->created_at))}}</td>
                                        <td>{{$allTransaction->purpose}} @if($allTransaction->transaction_purpose_reference_code)
                                                ( Ref Code:
                                                    @if($allTransaction->link)
                                                       <a href="{{$allTransaction->link}}" target="_blank"> {{$allTransaction->transaction_purpose_reference_code}}</a> )
                                                    @else
                                                        {{$allTransaction->transaction_purpose_reference_code}} )
                                                    @endif

                                            @endif
                                        </td>
                                        <td>
                                            {{ ($allTransaction->accounting_entry_type=='dr')? getNumberFormattedAmount($allTransaction->amount) : '-'  }}
                                        </td>
                                        <td>
                                            {{ ($allTransaction->accounting_entry_type=='cr')? getNumberFormattedAmount($allTransaction->amount): '-'  }}
                                        </td>
                                        <td>{{getNumberFormattedAmount($allTransaction->balance)}}</td>

                                        <td>{!! $allTransaction->remarks !!} </td>
                                        <td>
                                            @if($allTransaction->proof_of_document)
                                                <a class="btn btn-primary btn-xs"
                                                   href="{{$allTransaction->getProofOfDocumentImagePath()}}" target="_blank">
                                                    View
                                                </a>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            <a data-href="{{route('admin.wallets.transaction.extra-remarks.view',$allTransaction->wallet_transaction_code)}}" class="btn btn-xs btn-info view-remarks" data-toggle="modal" data-target="#exampleModal">view</a>
                                            <a data-href="{{route('admin.wallets.transaction.extra-remarks.create',$allTransaction->wallet_transaction_code)}}" class="btn btn-xs btn-primary add-remarks"  data-toggle="modal" data-target="#exampleModal">Add</a>
                                        </td>

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

                            {{ $allTransactionByWalletCode->appends($_GET)->links() }}

                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->

        </section>
        <!-- /.content -->
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function(){
            $('a[data-toggle="modal"]').click(function() {
                var target = $(this).attr('data-target');
                $(`${target} .modal-content`).html('');
                let url = $(this).attr('data-href');
                $(`${target} .modal-content`).load(url, function(result) {
                    $(target).show();
                });
            });
        });

    </script>
@endpush
