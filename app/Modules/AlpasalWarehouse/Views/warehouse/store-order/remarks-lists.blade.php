<div class="modal fade" id="viewRemarksModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <p><strong>Remarks List ({{$storeOrder->store_order_code}})</strong></p>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -30px;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">SN</th>
                        <th scope="col">Remarks</th>
                        <th scope="col">Created At</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($storeOrder->latestRemarks as $remarkData)
                        <tr>
                            <th scope="row">{{$loop->index + 1}}</th>
                            <td>{{$remarkData->remark}}</td>
                            <td>{{getReadableDate(getNepTimeZoneDateTime($remarkData->created_at))}}</td>
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
        </div>
    </div>
</div>
