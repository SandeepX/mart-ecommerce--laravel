

<div class="card card-default bg-panel">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="row">
                    <div class="col-md-11">
                        <div style="font-size:15px" class="panel-title ml-5">
                            <h4><strong>
                                    <span style="margin-left: 10px;">Transaction  Of store : {{$wallet->holder_name}}/{{$wallet->wallet_holder_code}}</span>
                                    <span > | Total  Balance : {{getNumberFormattedAmount($wallet->current_balance)}}</span>
                                    <span > | Active Balance : {{getNumberFormattedAmount($activeBalance)}}</span>
                                    <span > | Frozen Balance <a href="#" data-toggle="tooltip" data-html = "true" style="color: white" data-placement="Sources"
                                                                title="Withdraw Amount : {{ getNumberFormattedAmount($frozenBalanceDetails['total_withdraw_freeze']) }}
                                                                    <br>Pre Order Amount : {{ getNumberFormattedAmount($frozenBalanceDetails['total_preorder_freeze']) }} ">
                                     <i style="background-color: #0b0b0b" class="fa fa-plus"></i>
                                    </a> :{{ getNumberFormattedAmount($frozenBalanceDetails['total_freeze_amount']) }}
                                </span>
                                </strong>


                            </h4>
                         </div>
                    </div>
                    <div class="col-md-1">
                        <a  class="btn btn-danger btn-sm mt-3" data-toggle="collapse" href="#collapseFilter" role="button" aria-expanded="false" aria-controls="collapseExample">
                            <i class="fa  fa-filter"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="panel panel-default collapse" id="collapseFilter" style="background-color: #E4E4E4">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form id="transaction_statement_filter" action="{{route('support-admin.store-transaction-statement',$storeCode)}}" method="GET">
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
                                <button type="button" class="btn btn-block btn-primary form-control" id="filter-store-transaction-btn">Filter</button>
                            </form>


                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="panel panel-default">
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
                            <td>
                                {{$allTransaction->purpose}} @if($allTransaction->transaction_purpose_reference_code)
                                    ( Ref Code:
                                            {{$allTransaction->transaction_purpose_reference_code}})
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
                                <a>
                                    <button data-toggle="modal" value="{{$allTransaction->wallet_transaction_code}}"
                                            data-url="{{route('support-admin.transaction.extra-remark-view',['transactionWalletCode'=> $allTransaction->wallet_transaction_code])}}"
                                            data-target="#modal-target1"
                                            id="extra_remark_btn"
                                            data-placement="left" data-tooltip="true" title="Details" class="btn btn-xs btn-info">
                                        <span class="fa fa-eye"></span>
                                        view
                                    </button>
                                </a>

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

                <div class="pagination" id="statement-pagination">
                    @if(isset($allTransactionByWalletCode))
                    {{ $allTransactionByWalletCode->appends($_GET)->links() }}
                    @endif
                </div>




        </div>
    </div>
</div>

    <div class="modal fade" id="modal-target1" >
        <div class="modal-dialog" style="width: 80% !important; height: 90vh; overflow: scroll;">
            <div class="view-extra-remark-modal-content" style="background-color: white" >

            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>




























