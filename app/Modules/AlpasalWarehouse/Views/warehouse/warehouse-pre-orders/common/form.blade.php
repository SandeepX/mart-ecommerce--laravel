<div class="form-group">
    <label  class="col-sm-2 control-label">Pre Order Name</label>
    <div class="col-sm-6">
        <div class='input-group'>
            <input required type='text' class="form-control"
                   value="{{isset($warehousePreOrder->pre_order_name)? $warehousePreOrder->pre_order_name:old('pre_order_name')}}"
                   name="pre_order_name"/>
            <span class="input-group-addon">
                            <span class="glyphicon glyphicon-file"></span>
                        </span>
        </div>
    </div>

</div>
<div class="form-group">
    <label  class="col-sm-2 control-label">Start Time</label>
    <div class="col-sm-6">
        <div class='input-group date datetimepicker'>
            <input type='text' class="form-control"
                   value="{{isset($warehousePreOrder->start_time)? $warehousePreOrder->getStartTime():old('start_time')}}"
                   name="start_time"/>
                <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
        </div>
    </div>

</div>

<div class="form-group">
    <label  class="col-sm-2 control-label">End Time</label>
    <div class="col-sm-6">
        <div class='input-group date datetimepicker'>
            <input type='text' class="form-control"
                   value="{{isset($warehousePreOrder->end_time)? $warehousePreOrder->getEndTime():old('end_time')}}"
                   name="end_time"/>
            <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
        </div>
    </div>

</div>

<div class="form-group">
    <label  class="col-sm-2 control-label">Finalization Time</label>
    <div class="col-sm-6">
        <div class='input-group date datetimepicker'>
            <input type='text' class="form-control"
                   value="{{isset($warehousePreOrder->finalization_time)? $warehousePreOrder->getFinalizationTime():old('finalization_time')}}"
                   name="finalization_time"/>
            <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
        </div>
    </div>

</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Banner Image</label>
    <div class="col-sm-6">
      <input type="file" name="banner_image" id="banner_image" value="{{old('banner_image')}}" required>
        <img id="image_preview" src="#" alt="your image" width="auto" height="100px" style="object-fit: cover;display: none" />
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label">Active</label>
    <div class="col-sm-6">
        <input id="status" type="checkbox"
               @if((isset($warehousePreOrder) && $warehousePreOrder->is_active) ||old('is_active')) checked @endif
               name="is_active">
    </div>
</div>
