
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">
        @if(isset($storePreOrderqty) && $storePreOrderqty->count())

            @foreach($storePreOrderqty->take(1) as $qty)
                Order Info Of {{$qty->product_name}}
            @endforeach
        @endif
    </h4>
</div>
<div class="modal-body">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>S.N</th>
            <th>Store</th>
            <th>Order Qty</th>
        </tr>
        </thead>
        <tbody>

        @if(isset($storePreOrderqty) && $storePreOrderqty->count())

            @foreach($storePreOrderqty as $qty)

                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>
                        {{$qty->store_name}}
                    </td>
                    <td>
                        {{$qty->quantity}}
                    </td>
                </tr>
            @endforeach
        @endif

        </tbody>
    </table>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    {{-- <button type="button" class="btn btn-primary">Save changes</button>--}}
</div>

