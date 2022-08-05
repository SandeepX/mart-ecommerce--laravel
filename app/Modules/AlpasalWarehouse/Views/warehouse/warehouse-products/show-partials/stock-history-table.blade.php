
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">
        Stock History of {{$productDetail['product']['product_name']}}
        {{$productDetail['productVariant'] ? $productDetail['productVariant']['product_variant_name'] : ''}}
    </h4>
</div>
<div class="modal-body">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>S.N</th>
            <th>Date</th>
            <th>Quantity</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @forelse($warehouseProductStockHistories as $stockHistory)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{date('Y-m-d',strtotime($stockHistory->created_at))}}</td>
                <td>{{$stockHistory->quantity}}</td>
                <td>
                    <span class="label label-primary">{{$stockHistory->action}}</span>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="10">
                    <p class="text-center"><b>No history available!</b></p>
                </td>

            </tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    {{--<button type="button" class="btn btn-primary">Save changes</button>--}}
</div>
