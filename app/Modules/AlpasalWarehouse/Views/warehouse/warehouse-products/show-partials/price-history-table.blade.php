
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">
        Price Setting History of {{$productDetail['product']['product_name']}}
        {{$productDetail['productVariant'] ? $productDetail['productVariant']['product_variant_name'] : ''}}
    </h4>
</div>
<div class="modal-body">
    {{-- @include('AlpasalWarehouse::warehouse.warehouse-products.show-partials.price-history-table')--}}
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>S.N</th>
            <th>From</th>
            <th>To</th>
            <th>Setting</th>
        </tr>
        </thead>
        <tbody>
        @forelse($productPriceHistories as $priceHistory)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$priceHistory->from_date?date('Y-m-d',strtotime($priceHistory->from_date)) : '-'}}</td>
                <td>{{$priceHistory->to_date?date('Y-m-d',strtotime($priceHistory->to_date)) : 'Till date'}}</td>
                <td>
                    <div>MRP:{{$priceHistory['mrp']}}</div>
                    <div>Admin Margin({{$priceHistory['admin_margin_type']}}): {{$priceHistory['admin_margin_value']}}</div>
                    <div>Wholesale Margin({{$priceHistory['wholesale_margin_type']}}): {{$priceHistory['wholesale_margin_value']}}</div>
                    <div>Retail Margin({{$priceHistory['retail_margin_type']}}): {{$priceHistory['retail_margin_value']}}</div>
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
    {{-- <button type="button" class="btn btn-primary">Save changes</button>--}}
</div>

