<div class="col-xs-12 col-md-3">
    <div class="form-group">
        <label for="product_name">Product Name/Code</label>
        <input type="text" class="form-control" name="product_name" id="product_name" value="{{$filterParameters['product_name']}}">
    </div>
</div>
<div class="col-xs-12 col-md-3">
    <div class="form-group">
        <label for="variant_name">Variant Name/Code</label>
        <input type="text" class="form-control" name="variant_name" id="variant_name" value="{{$filterParameters['variant_name']}}">
    </div>
</div>
{{--<div class="col-xs-12 col-md-3">--}}
{{--    <div class="form-group">--}}
{{--        <label for="price_condition">Price Condition</label>--}}
{{--        <select name="price_condition" class="form-control select2" id="price_condition">--}}
{{--            <option value="" {{$filterParameters['price_condition'] == ''}}>All</option>--}}
{{--            @foreach($priceConditions as $key=>$priceCondition)--}}
{{--                <option value="{{$priceCondition}}"--}}
{{--                        {{$priceCondition == $filterParameters['price_condition'] ?'selected' :''}}>--}}
{{--                    {{ucwords($key)}}--}}
{{--                </option>--}}
{{--            @endforeach--}}
{{--        </select>--}}
{{--    </div>--}}
{{--</div>--}}
{{--<div class="col-xs-12 col-md-3">--}}
{{--    <div class="form-group">--}}
{{--        <label for="total_price">Price</label>--}}
{{--        <input type="number" class="form-control" name="total_price" id="total_price" value="{{$filterParameters['total_price']}}">--}}
{{--    </div>--}}
{{--</div>--}}
