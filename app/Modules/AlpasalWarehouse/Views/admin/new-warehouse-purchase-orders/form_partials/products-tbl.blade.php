<section class="content" style="height: 405px;">
    <div class="search_div" style="overflow-y: scroll;min-height: 100px;height: 350px;">

        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th style="width:5%">Image</th>
                <th style="width:90%">Product</th>
                <th style="width:5%">Action</th>


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
                            <button data-product-name="{{$product->product_name}}" data-product-code="{{$product->product_code}}" style="margin-top:10px"
                                    title="Add to Purchase Order List"
                                    class="btn btn-sm btn-success add-to-cart-btn">
                                <i class="fa fa-shopping-cart"></i>

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
        @if(isset($products))
            {{$products->appends($_GET)->links()}}
        @endif

    </div>
</section>