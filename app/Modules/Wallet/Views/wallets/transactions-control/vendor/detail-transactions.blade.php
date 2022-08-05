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
                            <form action="{{route('admin.wallet.transactions.vendor.details',$wallet->wallet_code)}}" method="get">

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
                                <span>Transactions of  Vendor :{{$wallet->holder_name}} / {{$wallet->wallet_holder_code}}</span>
                                <span > | Total  Balance : {{getNumberFormattedAmount($wallet->current_balance)}}</span>
                            </div>
                        </div>

                        <div class="box-body">
                            <table class="table table-bordered table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="text-center">Transaction Code <br> <small>(Reference Code)</small></th>
                                    <th>Date</th>
                                    <th>Purpose</th>
                                    <th>DR.</th>
                                    <th>CR.</th>
                                    <th>Total Current Balance</th>
                                    <th>Remarks</th>
                                    <th>Document</th>
                                </tr>
                                </thead>
                                <tbody>

                                @forelse($allTransactionByWalletCode as $i => $allTransaction)
                                    <tr>
                                        <td>{{$loop->index+1}}</td>
                                        <td class="text-center"> {{$allTransaction->wallet_transaction_code}}<br> <small>({{$allTransaction->reference_code}}) </small></td>
                                        <td>{{date('d-M-Y',strtotime($allTransaction->created_at))}}</td>
                                        <td>{{$allTransaction->purpose}} @if($allTransaction->transaction_purpose_reference_code) (Ref Code: {{$allTransaction->transaction_purpose_reference_code}}) @endif</td>
                                        <td>
                                            {{ ($allTransaction->accounting_entry_type=='dr')? getNumberFormattedAmount($allTransaction->amount) : '-'  }}
                                        </td>
                                        <td>
                                            {{ ($allTransaction->accounting_entry_type=='cr')? getNumberFormattedAmount($allTransaction->amount): '-'  }}
                                        </td>
                                        <td>  {{getNumberFormattedAmount($allTransaction->balance)}}</td>

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
@endpush
