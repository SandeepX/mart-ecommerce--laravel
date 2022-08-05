


<div class="box-body">
    <table class="table table-bordered table-striped" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>#</th>
            <th class="text-center">Transaction Code <br> <small>(Reference Code)</small></th>
            <th>User Detail</th>
            <th>Date</th>
            <th>Purpose</th>
            <th>DR.</th>
            <th>CR.</th>
            <th>Remarks</th>
            <th>Document</th>
            <th>Ex.Remarks</th>
        </tr>
        </thead>
        <tbody>

        @forelse($allWalletTransactionsForDaybook as $i => $allTransaction)
            <tr>
                <td>{{$loop->index+1}}</td>
                <td class="text-center"> {{$allTransaction->wallet_transaction_code}}<br> <small>({{$allTransaction->reference_code}}) </small></td>
                <td>{{$allTransaction->holder_name}} ({{$allTransaction->wallet->wallet_holder_code}})</td>
                {{--                                        <td>{{date('d-M-Y H:i:s',strtotime($allTransaction->created_at))}}</td>--}}
                <td>{{getNepTimeZoneDateTime($allTransaction->created_at)}}</td>
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
                        <button data-toggle="modal" value="{{$allTransaction->wallet_transaction_code}}" data-url="{{route('admin.daybook.view-extra-remark',$allTransaction->wallet_transaction_code)}}" data-target="#modal-target1" id="extra_remark_view_btn" data-placement="left" data-tooltip="true" title="view remark" class="btn btn-xs btn-info">
                           view
                        </button>
                    </a>
                    <a>
                        <button data-toggle="modal" value="{{$allTransaction->wallet_transaction_code}}" data-url="{{route('admin.daybook.create-extra-remark',$allTransaction->wallet_transaction_code)}}" data-target="#modal-target1" id="extra_remark_view_btn" data-placement="left" data-tooltip="true" title="view remark" class="btn btn-xs btn-primary">
                         Add
                        </button>
                    </a>

                    <div class="modal fade" id="modal-target1" >
                        <div class="modal-dialog" role="document">
                            <div class="extra-remark-modal-content" style="background-color: white" >

                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>

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

    <div class="pagination" id="daybook-pagination">
        @if(isset($allWalletTransactionsForDaybook))
            {{$allWalletTransactionsForDaybook->appends($_GET)->links()}}
        @endif
    </div>

</div>

