
<form id="filter_form" action="{{ route('admin.warehouse-wise.current-stock.warehouse.detail',$warehouse->warehouse_code) }}" method="GET">
    <div class="row">
        <div class="col-xs-12">
            <div class="form-group">
                <label for="vendor_code">Vendor</label>
                <select name="vendor_code" class="form-control select2" id="vendor_code">
                    <option value="">Select Vendor</option>
                    @foreach($vendors as $vendor)
                        <option value="{{$vendor->vendor_code}}"
                            {{$vendor->vendor_code == $filterParameters['vendor_code'] ?'selected' :''}}>
                            {{ucwords($vendor->vendor_name)}}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="form-group">
                <button type="submit" class="btn btn-block btn-primary form-control">Filter</button>
            </div>
        </div>
    </div>
</form>
