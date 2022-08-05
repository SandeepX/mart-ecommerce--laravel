@extends('Admin::layout.common.masterlayout')

@section('content')
    <div class="content-wrapper">
    @include('Admin::layout.partials.breadcrumb',
    [
   'page_title'=> formatWords($title,false),
   'sub_title'=>'Manage '. formatWords($title,false),
   'icon'=>'home',
   'sub_icon'=>'',
   'manage_url'=>route($base_route.'list'),
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
                            <form action="{{route('admin.store.balance.detail',$store->store_code)}}" method="get">

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
                                            @foreach($transactionTypes as $transactionType)
                                                <option value="{{$transactionType}}" {{$transactionType == $filterParameters['transaction_type'] ?'selected' :''}}>{{ucwords(str_replace('_'," ",$transactionType))}}</option>
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
                               <span>Transactions of  Store : {{$store->store_name}} / {{$store->store_code}}</span>
                                <span > | Total  Balance : {{ getNumberFormattedAmount($storeTotalBalance) }}</span>
                                <span > | Active Balance : {{ getNumberFormattedAmount($storeActiveBalance) }}</span>
                                <span > | Frozen Balance <a href="#" data-toggle="tooltip" data-html = "true" style="color: white" data-placement="Sources"
                                                                  title="Withdraw Amount : {{ getNumberFormattedAmount($totalFreezeAmountDetails['total_withdraw_freeze']) }}
                                                                      <br>Pre Order Amount : {{ getNumberFormattedAmount($totalFreezeAmountDetails['total_preorder_freeze']) }}">
                                     <i class="fa fa-info-circle"></i>
                                    </a> : {{ getNumberFormattedAmount($totalFreezeAmountDetails['total_freeze_amount']) }}
                                </span>

                                @can('Create Store Balance Control')
                                    <a href="{{route('admin.store-balance-control.create',$store->store_code)}}" style="border-radius: 3px; " class="btn btn-xs btn-info pull-right" data-toggle="modal" data-target="#exampleModal">
                                        <i class="fa fa-plus-circle"></i>
                                        Add New Balance Control
                                    </a>
                                @endcan
                            </div>
                        </div>

                        <div class="box-body">
                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Transaction Code</th>
                                    <th>Date</th>
                                    <th>Purpose</th>
{{--                                    <th>Amount</th>--}}
                                    <th>DR.</th>
                                    <th>CR.</th>
                                    <th>Total Current Balance</th>
                                    <th>Remarks</th>
                                    <th>Document</th>
                                </tr>
                                </thead>
                                <tbody>


                                @forelse($allTransactionByStoreCode as $i => $allTransaction)

                                    <?php

//                                    $transactionType = $allTransaction['transaction_type'];
//                                        $creditType = [
//                                                'sales_return',
//                                                'load_balance',
//                                                'rewards',
//                                                'interest',
//                                                'sales_reconciliation_increment',
//                                                'pre_orders_sales_reconciliation_increment',
//                                                'refund_release',
//                                                'transaction_correction_increment',
//                                                'preorder_refund',
//                                                'janata_bank_increment',
//                                                'cash_received'
//                                            ];
//                                        if(in_array($transactionType,$creditType)){
//                                            $creditStatus = 'cr';
//                                        }else{
//                                            $creditStatus = '';
//                                        }
                                    ?>
                                    <tr>
                                        <td>{{++$i}}</td>
                                        <td>{{$allTransaction['store_balance_master_code']}}</td>
                                        <td>{{date('d-M-Y',strtotime($allTransaction['created_at']))}}</td>
                                        <td>
                                            {{ucwords(str_replace('_',' ',$allTransaction['transaction_type']))}}
                                            <br/>
                                            @if($allTransaction['transaction_type'] == 'transaction_correction_deduction'
                                               ||
                                               $allTransaction['transaction_type']=='transaction_correction_increment'
                                               )
                                                (Transaction Code: {{$allTransaction->storetransactioncorrection->transaction_code}})
                                            @elseif($allTransaction['transaction_type']=='pre_orders_sales_reconciliation_increment'
                                              ||
                                              $allTransaction['transaction_type']=='pre_orders_sales_reconciliation_deduction'
                                              ||
                                              $allTransaction['transaction_type']=='sales_reconciliation_increment'
                                              ||
                                              $allTransaction['transaction_type']=='sales_reconciliation_deduction'
                                              )
                                             @if($allTransaction->salesreconciliation->order_code)
                                                    (Order Code: {{$allTransaction->salesreconciliation->order_code}})
                                             @endif
                                             @if($allTransaction->salesreconciliation->ref_bill_no)
                                                    (Ref Bill No: {{$allTransaction->salesreconciliation->ref_bill_no}})
                                             @endif
                                            @endif
                                        </td>
{{--                                        <td> Rs. {{ getNumberFormattedAmount($allTransaction['transaction_amount']) }}</td>--}}
                                        <td>
                                           {{ ($allTransaction->accounting_entry_type=='dr')? getNumberFormattedAmount($allTransaction['transaction_amount']) : '-'  }}
                                        </td>
                                        <td>
                                            {{ ($allTransaction->accounting_entry_type=='cr')? getNumberFormattedAmount($allTransaction['transaction_amount']): '-'  }}
                                        </td>
                                        <td>{{ getNumberFormattedAmount($allTransaction['balance']) }}</td>

                                        <td>{!!  $allTransaction->remarks !!}</td>
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

                            {{ $allTransactionByStoreCode->appends($_GET)->links() }}

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
                let url = $(this).attr('href');
                $(`${target} .modal-content`).load(url, function(result) {
                    $(target).show();
                });
            });
        });
    </script>
@endpush
