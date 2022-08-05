
<form id="filter_form" action="{{ route('admin.warehouse-pre-orders.index') }}" method="GET">
    <div class="row">
        <div class="col-xs-12">
            <div class="form-group">
                <label for="warehouse_name">Warehouse</label>
                <input type="text" class="form-control" name="warehouse_name" id="warehouse_name"
                       value="{{$filterParameters['warehouse_name']}}">
            </div>
        </div>
        <div class="col-xs-12">
            <div class="form-group">
                <button type="submit" class="btn btn-block btn-primary form-control">Filter</button>
            </div>
        </div>
    </div>
</form>
