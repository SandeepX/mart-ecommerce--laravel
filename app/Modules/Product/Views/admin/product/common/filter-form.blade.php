
<form id="filter_form" action="{{ route('admin.products.index') }}" method="GET">

    <div class="row">
        <div class="col-xs-6">
            <div class="form-group">
                <label for="vendor_code">Vendor</label>
                <select name="vendor_code" class="form-control select2" id="vendor_code">
                    <option value="">All</option>
                    @if(isset($vendors) && count($vendors)>0)
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->vendor_code }}"
                                    {{$vendor->vendor_code == $filterParameters['vendor_code'] ?'selected' :''}}>
                                {{ $vendor->vendor_name }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>

        <div class="col-xs-6">
            <div class="form-group">
                <label for="product_name">Product</label>
                <input type="text" class="form-control" name="product_name" id="product_name"
                       value="{{$filterParameters['product_name']}}">
            </div>
        </div>
        <div class="col-xs-12">
            <div class="form-group">
                <button type="submit" class="btn btn-block btn-primary form-control">Filter</button>
            </div>
        </div>
    </div>
</form>
