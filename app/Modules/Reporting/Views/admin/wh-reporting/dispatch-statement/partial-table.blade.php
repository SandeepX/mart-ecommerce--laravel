<div class="box-body">
    <table class="table table-bordered table-striped" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Store Name</th>
            <th>Order Date</th>
            <th>Order Type</th>
            <th>Quantity</th>
            <th>Unit Rate</th>
            <th>Order Amount</th>
        </tr>
        </thead>
        <tbody>
        @forelse($dispatchStatements as $key =>$statement)
            <tr>
                <td>{{++$loop->index}}</td>
                <td>
                    <strong> Product Name:</strong> {{$statement->product_name}}
                    @if($statement->product_variant_code)
                        ({{$statement->product_variant_name}})
                    @endif <br/>
                    <strong> Vendor Name: </strong> {{$statement->vendor_name}}

                </td>
                <td>{{$statement->store_name}}  ({{$statement->store_code}})</td>
                <td>{{getReadableDate(getNepTimeZoneDateTime($statement->order_date))}}</td>
                <td>{{ucwords(str_replace('_',' ',$statement->order_type))}}<br/>

                    @if($statement->link)
                        (<a href="{{$statement->link}}" target="_blank">{{$statement->order_code}}</a>)
                    @else
                       ({{$statement->order_code}})
                    @endif
                </td>
                <td>{{$statement->package_quantity}}</td>
                <td>{{getNumberFormattedAmount($statement->unit_rate)}}</td>
                <td>{{getNumberFormattedAmount($statement->order_amount)}}</td>
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
    {{$dispatchStatements->appends($_GET)->links()}}
</div>
