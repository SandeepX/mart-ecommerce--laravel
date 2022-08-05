<hr>
@can('Update Bill Merge Master Status')
<div class="row">
    <div class="col-sm-12">
        <form id="status-form" method="post" action="{{route('warehouse.merge-bill.update-status.master',$masterBillMerge->bill_merge_master_code)}}">
            {{csrf_field()}}

{{--            <div id="dispatch-vehicle-details" style="display: none">--}}
{{--                <div class="panel panel-info">--}}
{{--                    <div class="panel-heading">--}}
{{--                       Add Dispatch Details--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="row">--}}
{{--                    <div class="col-lg-3 col-md-3">--}}
{{--                        <div class="form-group">--}}
{{--                            <label for="vehicle_name" class="control-label">Driver Name</label>--}}
{{--                            <input type="text" id="driver_name"  class="form-control" name="driver_name" value="{{old('driver_name')}}">--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-lg-3 col-md-3">--}}
{{--                        <div class="form-group">--}}
{{--                            <label for="vehicle_type" class="control-label">Vehicle Type</label>--}}
{{--                            <input type="text" id="vehicle_type"  class="form-control" name="vehicle_type" value="{{old('vehicle_type')}}">--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-lg-3 col-md-3">--}}
{{--                        <div class="form-group">--}}
{{--                            <label for="vehicle_number" class="control-label">Vehicle Number</label>--}}
{{--                            <input type="text" id="vehicle_number"  class="form-control" name="vehicle_number" value="{{old('vehicle_number')}}">--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-lg-3 col-md-3">--}}
{{--                        <div class="form-group">--}}
{{--                            <label for="expected_delivery_time" class="control-label">Expected Delivery Time</label>--}}
{{--                            <input type="text" id="expected_delivery_time"  class="form-control datetimepicker" name="expected_delivery_time" value="{{old('expected_delivery_time')}}">--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="row">--}}
{{--                    <div class="col-lg-3 col-md-3">--}}
{{--                        <div class="form-group">--}}
{{--                            <label for="contact_number" class="control-label">Contact Number</label>--}}
{{--                            <input type="text" id="contact_number"  class="form-control" name="contact_number" value="{{old('contact_number')}}">--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

            <div class="row">
                <div class="col-lg-3 col-md-3">
                    <label for="status" class="control-label">Change Status</label>
                    <div class="form-group">
                        <select name="status" class="form-control" id="status" required>
                            <option value="" selected disabled>-Select a Status-</option>
                            <option value="ready_to_dispatch" {{old('status') == 'ready_to_dispatch' ?' selected' : ''}}>Ready To Dispatch</option>
                            <option value="cancelled" {{old('status') == 'cancelled' ?' selected' : ''}}>Cancel</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="form-group">
                        <label for="remarks" class="control-label">Please Add Status Change Remarks (required)</label>
                        <textarea id="remarks" style="height: 107.3px !important" required class="form-control" name="remarks" cols="1">{{old('remarks')}}</textarea>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3">
                    <label class="control-label"></label>
                    <div class="form-group">
                        <button id="order_status_submit" type="submit" class="btn btn-block btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endcan

