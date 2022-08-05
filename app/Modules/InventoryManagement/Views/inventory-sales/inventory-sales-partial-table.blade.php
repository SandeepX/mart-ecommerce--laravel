<div class="box-body">

    <table class="table table-bordered table-striped" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>#</th>
            <th>Store Name</th>
            <th>Product Name</th>
            <th>Packaging Configuration</th>
            <th>Dispatched Quantity</th>
{{--            <th>Cost Price(Rs.)</th>--}}
            <th>M.R.P(Rs.)</th>
            <th>Manufacture Date</th>
            <th>Expiry Date</th>
            <th>Sale Date</th>
            <th>Action</th>
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
                {{--                <td> {{(number_format($datum->cost_price))}}</td>--}}
                <td> {{(number_format($datum->mrp))}}</td>
                <td> {{ date('d-M-Y',strtotime($datum->manufacture_date))}}</td>
                <td> {{ date('d-M-Y',strtotime($datum->expiry_date))}}</td>
                <td> {{ date('d-M-Y',strtotime($datum->created_at))}}</td>
                <td>
                    @can('Show Inventory Quantity Dispatched Detail')
                        {!! \App\Modules\Application\Presenters\DataTable::createHtmlAction('show', route('admin.inventory.sales-record.show-detail',[$datum->siid_code,$datum->pph_code] ),'Show dispatched quantity detail', 'eye','primary')!!}
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
    {{$storeInventoryStockDispatchedDetail->appends($_GET)->links()}}

</div>
