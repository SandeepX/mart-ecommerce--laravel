<table>
    <thead>
    <tr>
        <th><b>S.N</b></th>
        <th><b>Transaction Code</b></th>
        <th><b>Referenced Transaction Code</b></th>
        <th><b>Date</b></th>
        <th><b>Purpose</b></th>
        <th><b>DR.</b></th>
        <th><b>CR.</b></th>
        <th><b>Total Current Balance</b></th>
        <th><b>Remarks</b></th>
    </tr>
    </thead>
    <tbody>

    @foreach($allTransactionByWalletCode as $key => $allTransaction )
        <?php
        $correctionTransaction = $allTransaction->getAllTransactionCorrectionReferenceCode($allTransaction->wallet_transaction_code)
        ?>
        <tr>
            <td>{{$loop->index+1}}</td>
            <td>
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
            <td>{{$allTransaction->purpose}}
                (Ref Code: {{($allTransaction->transaction_purpose_reference_code)?($allTransaction->transaction_purpose_reference_code):''}})
            </td>
            <td>
                {{ ($allTransaction->accounting_entry_type=='dr')? getNumberFormattedAmount($allTransaction->amount) : '-'  }}
            </td>
            <td>
                {{ ($allTransaction->accounting_entry_type=='cr')? getNumberFormattedAmount($allTransaction->amount): '-'  }}
            </td>
            <td>{{getNumberFormattedAmount($allTransaction->balance)}}</td>

            <td>{!! $allTransaction->remarks !!} </td>
    @endforeach
    </tbody>
</table>
