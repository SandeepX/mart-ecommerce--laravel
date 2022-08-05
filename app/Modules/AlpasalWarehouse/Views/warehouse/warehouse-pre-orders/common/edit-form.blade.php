
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
                   value="{{old('start_time')?old('start_time') :$warehousePreOrder->getStartTime()}}"
                   name="start_time" {{$isPastStartTime ? 'readonly':''}}/>
            <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
        </div>
        @if($isPastStartTime)
            <small> <span class="label label-warning">Start time has already started.</span></small>
        @endif

    </div>

</div>

<div class="form-group">
    <label  class="col-sm-2 control-label">End Time</label>
    <div class="col-sm-6">
        <div class='input-group date datetimepicker'>
            <input type='text' class="form-control"
                   value="{{old('end_time')?old('end_time') :$warehousePreOrder->getEndTime()}}"
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
                   value="{{old('finalization_time')?old('finalization_time') :$warehousePreOrder->getFinalizationTime()}}"
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
        <input type="file" name="banner_image" id="banner_image" value="">
        @if($warehousePreOrder->banner_image)
            <br/>
        <img id="image_preview" src="{{asset($warehousePreOrder->getBannerUploadPath().$warehousePreOrder->banner_image)}}" alt="your image" width="auto" height="100px" style="object-fit: cover;" />
        @endif
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
