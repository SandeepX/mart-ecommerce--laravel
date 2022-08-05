<div class="form-group">
    <label  class="col-sm-3 control-label"> Destination Warehouse</label>
    <div class="col-sm-6">
        <select class="select2 form-control" name="warehouse_name">

            @foreach($warehouses as $warehouse)
                <option value="{{$warehouse->warehouse_code}}">{{$warehouse->warehouse_name}}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-group">
    <label  class="col-sm-3 control-label">Remarks</label>
    <div class="col-sm-6">
        <textarea class="form-control" rows="5" name="remarks"></textarea>
    </div>
</div>

