

<div class="row">
    <div class="col-md-12">
        @if(isset($finalProducts) && $finalProducts->count() > 0)
            <table class="table table-striped">
                <thead>
                     @foreach($finalProducts as $products)
                        <p>
                            <th style="color: #0f0f0f; text-align: center">Product : {{$products[0]->product_name}}</th>
                        </p>
                            @foreach($products as $product)

                                    <p>
                                        <th style="color: #0f0f0f; text-align: center">Variant : {{($product->product_variant_name)? $product->product_variant_name:'N/A' }}</th>
                                    </p>

                                    <tr>
                                        <th>Product Name</th>
                                        <th>Product Variant Name</th>
                                        <th>Package Name</th>
                                        <th>Product Price (NRS)</th>
                                    </tr>
                            </thead>
                            <tbody>
                                    @foreach($product['package_details'] as $package)
                                        <tr>
                                            <td>{{$product->product_name}}</td>
                                            <td>{{$product->product_variant_name}}</td>
                                            <td>{{$package['package_name']}}</td>
                                            <td>{{$package['mrp']}}</td>
                                        </tr>

                                    @endforeach
                            @endforeach
                                    <tr style="background: #aaa;">
                                        <td colspan="7">-</td>
                                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="pagination" id="pricingLink-pagination">
                @if(isset($finalProducts))
                    {{$finalProducts->appends($_GET)->links()}}
                @endif
            </div>
        @else
            <div class="alert alert-info" role="alert">
                No Product Data Found !
            </div>
        @endif
    </div>
</div>


