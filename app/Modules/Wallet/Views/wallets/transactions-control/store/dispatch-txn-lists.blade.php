@extends('Admin::layout.common.masterlayout')

@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
   'page_title'=> 'Wallet Transaction With Dispatch Amount',
   'sub_title'=>'Manage Wallet Transaction With Dispatch Amount',
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
                            <form action="{{route('admin.wallet.transactions.store.dispatch.details',$wallet->wallet_code)}}" method="get">

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
                                <span>Transactions of  Store With Dispatch Amount :{{$wallet->holder_name}} / {{$wallet->wallet_holder_code}}</span>
                                <span > | Total  Balance : {{getNumberFormattedAmount($wallet->current_balance)}}</span>
                                <span > | Active Balance : {{getNumberFormattedAmount($activeBalance)}}</span>
                                <span > | Frozen Balance <a href="#" data-toggle="tooltip" data-html = "true" style="color: white" data-placement="Sources"
                                                            title="Withdraw Amount : {{ getNumberFormattedAmount($frozenBalanceDetails['total_withdraw_freeze']) }}
                                                                <br>Pre Order Amount : {{ getNumberFormattedAmount($frozenBalanceDetails['total_preorder_freeze']) }} ">
                                     <i class="fa fa-info-circle"></i>
                                    </a> :{{ getNumberFormattedAmount($frozenBalanceDetails['total_freeze_amount']) }}
                                </span>

                            </div>
                        </div>

                        <div class="box-body">
                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="text-center">Transaction Code</th>
                                    <th class="text-center">Bill Merge Code</th>
                                    <th>Date</th>
                                    <th>Purpose</th>
                                    <th>DR.</th>
                                    <th>CR.</th>
                                    <th>Total Current Balance</th>
                                    <th>Remarks</th>
                                </tr>
                                </thead>
                                <tbody>

                                @forelse($allTransactionWithDispatchByWalletCode as $i => $allTransaction)
                                    <tr>
                                        <td>{{$loop->index+1}}</td>
                                        <td class="text-center">
                                            @if($allTransaction->wallet_transaction_code)
                                            {{$allTransaction->wallet_transaction_code}}
                                            @else
                                               N/A
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {{($allTransaction->bill_merge_master_code)?$allTransaction->bill_merge_master_code :'N/A'}}
                                        </td>
                                        <td>{{date('d-M-Y',strtotime($allTransaction->date))}}</td>
                                        <td>{{ucwords(str_replace('-',' ',$allTransaction->purpose))}}
                                            @if($allTransaction->reference_code)
                                                ( Ref Code:
                                                @if($allTransaction->link)
                                                    <a href="{{$allTransaction->link}}" target="_blank"> {{$allTransaction->reference_code}}</a> )
                                                @else
                                                    {{$allTransaction->reference_code}} )
                                                @endif

                                            @endif
                                        </td>
                                        <td>
                                            {{ ($allTransaction->purpose_type=='dr')? getNumberFormattedAmount($allTransaction->total_amount) : '-'  }}
                                        </td>
                                        <td>
                                            {{ ($allTransaction->purpose_type=='cr')? getNumberFormattedAmount($allTransaction->total_amount): '-'  }}
                                        </td>

                                        <td>{{getNumberFormattedAmount($allTransaction->actual_balance)}}</td>

                                        <td style="max-width:200px;word-wrap:break-word;">{!! $allTransaction->remarks !!} </td>
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

                            {{ $allTransactionWithDispatchByWalletCode->appends($_GET)->links() }}

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
