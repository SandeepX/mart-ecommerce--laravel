<div class="modal-header">
    <p> <strong>{{ ucwords(str_replace('_',' ',$offlinePayment->payment_type)) }}</strong> ({{$offlinePayment->offline_payment_code}})</p>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -60px;">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div id="showFlashMessageModal"></div>


<div class="modal-body col-md-12">
    <table class="row table" >
        <thead>
        <tr>
            <th scope="col">SN</th>
            <th scope="col">Remarks</th>
            <th scope="col">Created At</th>
        </tr>
        </thead>
        <tbody>
        @forelse($remarks as $remark)
            <tr>
                <td>{{++$loop->index}}</td>
                <td>{{ wordwrap($remark->remark, 25, "\n", true) }}</td>
                <td>{{getReadableDate(getNepTimeZoneDateTime($remark->created_at))}}</td>
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
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>

