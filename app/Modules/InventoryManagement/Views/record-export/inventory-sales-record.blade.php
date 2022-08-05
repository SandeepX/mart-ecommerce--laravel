<table>
    <thead>
    <tr>
        <th colspan="5" style="text-align: center"><strong>Store Inventory Sales Records</strong></th>
    </tr>
    <tr>
        <th>#</th>
        <th>Store Name</th>
        <th>Product Name</th>
        <th>Packaging Configuration</th>
        <th>Dispatched Quantity</th>
        <th>M.R.P(Rs.)</th>
        <th>Manufacture Date</th>
        <th>Expiry Date</th>
        <th>Sale Date</th>

    </tr>
    </thead>
    <tbody>
    @forelse($storeInventoryStockDispatchedDetail as $key =>$datum)

        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{ucfirst($datum->store_name)}}({{($datum->store_code)}})</td>
            <td> {{ucfirst($datum->product_name)}}-{{$datum->product_variant_name}}</td>
            <td> {{ucfirst($datum->package_contains)}}({{$datum->pph_code}})</td>
            <td> {{$datum->total_dispatched_stock}} </td>
            <td> {{(number_format($datum->mrp))}}</td>
            <td> {{ date('d-M-Y',strtotime($datum->manufacture_date))}}</td>
            <td> {{ date('d-M-Y',strtotime($datum->expiry_date))}}</td>
            <td> {{ date('d-M-Y',strtotime($datum->created_at))}}</td>
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
