<form action="#" method="get" id="filter_form">

    <div class="col-xs-3">
        <div class="form-group">
            <label for="store">Store <button class="btn btn-danger btn-xs" id="reset-store">Reset</button></label>
            <select id="store" name="store_code" class="form-control select2">
                <option value="" {{($filterParameters['store_code']) ? 'selected' : ''}}>All</option>
                @foreach($stores as $key => $value)
                    <option value="{{$value->store_code}}" {{($filterParameters['store_code'] ==$value->store_code ) ? 'selected' : ''}}>{{$value->store_name}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-xs-3">
        <div class="form-group">
            <label for="product">Product  <button class="btn btn-danger btn-xs" id="reset-product">Reset</button></label>
            <select id="product" name="product_code" class="form-control select2 ">
                <option value="" {{($filterParameters['product_code']) ? 'selected' : ''}}>All</option>
                @foreach($products as $key => $value)
                    <option value="{{$value->product_code}}" {{($filterParameters['product_code'] ==$value->product_code ) ? 'selected' : ''}}>{{$value->product_name}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-xs-3">
        <div class="form-group">
            <label for="sales_from">Sales From  <button class="btn btn-danger btn-xs" id="sales-from">Reset</button></label>
            <input type="date" class="form-control" name="sales_from" id="sales_from"
                   value="{{($filterParameters['sales_from'])}}">
        </div>
    </div>

    <div class="col-xs-3">
        <div class="form-group">
            <label for="sales_to">Sales To <button class="btn btn-danger btn-xs" id="sales-to">Reset</button></label>
            <input type="date" class="form-control" name="sales_to" id="sales_to"
                   value="{{($filterParameters['sales_to'])}}">
        </div>
    </div>

    <div class="col-xs-2">
        <div class="form-group">
            <label for="per_page">Record Per Page</label>
            <input type="number" min="25" step="25" class="form-control" name="per_page" id="per_page"
                   value="{{($filterParameters['perPage'])}}">
        </div>
    </div>

    <button type="submit" id="filter" class="btn btn-block btn-primary form-control">Filter</button>
</form>
