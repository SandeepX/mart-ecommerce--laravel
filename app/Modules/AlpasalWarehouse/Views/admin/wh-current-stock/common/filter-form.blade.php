
<form id="filter_form" action="{{ route('admin.warehouse-wise.current-stock.index') }}" method="GET">
    <div class="row">
        <div class="col-xs-12">
            <div class="form-group">
                <label for="warehouse_code">Warehouse</label>
                <select name="warehouse_code" class="form-control select2" id="warehouse_code">
                    <option value="">Select Warehouse</option>
                    @foreach($warehouses as $warehouse)
                        <option value="{{$warehouse->warehouse_code}}"
                            {{$warehouse->warehouse_code == $filterParameters['warehouse_code'] ?'selected' :''}}>
                            {{ucwords($warehouse->warehouse_name)}}
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
