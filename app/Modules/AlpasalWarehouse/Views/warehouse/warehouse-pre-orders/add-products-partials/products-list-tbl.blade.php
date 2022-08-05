<table class="table table-bordered table-striped" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th>Image</th>
        <th>Product</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @if(isset($products))
        @forelse($products as $product)
            <tr>
                <td><img src="{{$product->getFeaturedImage()}}"
                         alt="Vendor Logo" width="50" height="50"></td>
                <td>
                    {{--<p style="margin-top:10px">
                        Gyan Suji
                    </p>--}}
                    <label>{{$product->product_name}}</label>
                </td>
                <td>
                    <button data-product-name="{{$product->product_name}}"
                            data-product-code="{{$product->product_code}}"
                            style="margin-top:10px"
                            title="Add to Pre-Order List"
                            class="btn btn-sm btn-success add-to-cart-btn">
                        <i class="fa fa-plus"></i>

                    </button>
                </td>

            </tr>
        @empty
            <tr>
                <td colspan="100%">
                    <p class="text-center"><b>No records found!</b></p>
                </td>
            </tr>
        @endforelse
    @endif
    </tbody>
</table>

<div id="products-tbl-pagination">
    @if(isset($products))
        {{$products->appends($_GET)->links()}}
    @endif
</div>

