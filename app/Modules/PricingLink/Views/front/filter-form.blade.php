

<div class="col-xs-12 px-0 mx-0" style="margin: 0 !important; padding: 0">
    <div class="panel-group">
        <div class="panel panel-success">
            <div class="panel-heading mt-1">

                <div class="row">
                    <div class="col-md-6">
                        <strong>
                            FILTER PRODUCT
                        </strong>
                    </div>
                    <div class="col-md-6 text-right">
                        <strong class="ml-2">
                            <a href="https://m.me/alpasal.co" target="_blank">
                                <button class="btn btn-info btn-sm"><i class="fa fa-comments-o" aria-hidden="true"></i>
                                    Chat With Staff</button></a>
                        </strong>
                    </div>
                </div>



            </div>

            <div class="panel-body" >
                <div class="panel panel-default">
                    <form id="pricing_form_filter" action="{{route('product-pricing.index',$link)}}" method="GET">

                        <div class="col-xs-12">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="product">Product</label> <button class="btn-danger btn-xs " id="reset-product-field" aria-hidden="true">Reset</button>
                                        <select id="product" name="product[]" multiple  class="product-name">
                                            @foreach($products as $key => $wareHouseProducts)
                                                <option {{(isset($filterParameters['product']) && in_array($wareHouseProducts->product_code,$filterParameters['product']) ) ? 'selected' : ''}} value="{{$wareHouseProducts->product_code}}" >{{ucfirst($wareHouseProducts->product_name)}}</option>
                                            @endForeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 text-right"> <button type="button" class="btn btn-primary form-control pricing-product-link-filter" style="margin-top: 24px;">Filter</button></div>
                                </div>

                            </div>
                        </div>


{{--                        <div class="col-xs-3">--}}
{{--                            <div class="form-group">--}}
{{--                                --}}
{{--                            </div>--}}
{{--                        </div>--}}

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

