<div class="box-body">

    <table class="table table-bordered table-striped" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>#</th>
            <th>Store Name</th>
            <th>Product Name</th>
            <th>Packaging Configuration</th>
            <th>Cost Price(Rs.)</th>
            <th>M.R.P(Rs.)</th>
            <th>Quantity</th>
            <th>Manufacture Date</th>
            <th>Expiry Date</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>

        @forelse($storeCurrentStockDetail as $key =>$datum)

            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{ucfirst($datum->store_name)}}({{($datum->store_code)}})</td>
                <td> {{ucfirst($datum->product_name)}}-{{$datum->product_variant_name}}</td>
                <td> {{ucfirst($datum->package_contains)}}({{$datum->pph_code}})</td>
                <td> {{(number_format($datum->cost_price))}}</td>
                <td> {{(number_format($datum->mrp))}}</td>
                <td> {{$datum->total_stock}} </td>
                <td> {{ date('d-M-Y',strtotime($datum->manufacture_date))}}</td>
                <td> {{ date('d-M-Y',strtotime($datum->expiry_date))}}</td>
                <td>
                    @can('Show Inventory Quantity Received Detail')
                        {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('show', route('admin.inventory.purchased-stock.show-recieved-qty-detail',[$datum->siid_code,$datum->pph_code] ),'Show recieved quantity detail', 'eye','primary')!!}
                    @endcan
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
    {{$storeCurrentStockDetail->appends($_GET)->links()}}

</div>
