
<div class="box-body">
    <table class="table table-bordered table-striped" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th rowspan="2">#</th>
            <th rowspan="2">Product Name</th>
            <th rowspan="2">Packaging Configuration</th>
            <th colspan="4" class="text-center">Batch Detail</th>
            <th colspan="3" class="text-center">Total Stock Quantity</th>
        </tr>
        <tr>
            <th>Cost Price(Rs.)</th>
            <th>M.R.P(Rs.)</th>
            <th>Manufacture Date</th>
            <th>Expiry Date</th>
            <th>Purchased</th>
            <th>Sell Out</th>
            <th>Remaining</th>
        </tr>
        </thead>
        <tbody>

        @forelse($storeCurrentStockDetail as $key =>$datum)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td> {{ucfirst($datum->product_name)}}-{{$datum->product_variant_name}}</td>
                <td> {{ucfirst($datum->package_contains)}}({{$datum->pph_code}})</td>
                <td> {{(number_format($datum->cost_price))}}</td>
                <td> {{(number_format($datum->mrp))}}</td>
                <td> {{ date('d-M-Y',strtotime($datum->manufacture_date))}}</td>
                <td> {{ date('d-M-Y',strtotime($datum->expiry_date))}}</td>
                <td><span class="badge badge-secondary">{{$datum->total_stock_received_qty}}</span>  </td>
                <td><span class="badge badge-secondary">{{$datum->total_stock_dispatched_qty}}</span>  </td>
                <td><span class="badge badge-secondary">{{$datum->total_remaining_stock_qty}}</span>  </td>
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
    {{$storeCurrentStockDetail->appends($_GET)->links()}}
</div>
