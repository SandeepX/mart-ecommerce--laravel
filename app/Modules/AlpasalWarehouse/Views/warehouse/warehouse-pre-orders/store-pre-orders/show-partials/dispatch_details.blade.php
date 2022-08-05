
<div class="row" style="display: none" id="wh_store_pre_order_dispatch_detail">
    <hr>
    <strong><h3>Warehouse Store Pre Order Dispatch Detail</h3></strong>
    <hr>

    <div class="col-sm-6">

        <div class="form-group dispatch_input">
            <label for="vehicle_name">Driver Name<span style="color: red">*</span></label>
            <input type="text" class="form-control" id="driver_name" value="" name="driver_name" placeholder="Enter Driver Name">
        </div>

        <div class="form-group dispatch_input">
            <label for="vehicle_type">vehicle Type<span style="color: red">*</span></label>
            <input type="text" class="form-control" id="vehicle_type" value="" name="vehicle_type" placeholder="Enter vehicle Type">
        </div>

        <div class="form-group dispatch_input">
            <label for="contact_number">Vehicle Contact Number<span style="color: red">*</span></label>
            <input type="text" class="form-control" id="vehicle_contact_number" value="" name="contact_number" placeholder="Enter vehicle Contact Number">
        </div>
    </div>

    <div class="col-sm-6">
        <div class="form-group dispatch_input">
            <label for="vehicle_number">Vehicle Number<span style="color: red">*</span></label>
            <input type="text" class="form-control" id="vehicle_number" value="" name="vehicle_number" placeholder="Enter vehicle Number">

        </div>

{{--        <div class="form-group dispatch_input">--}}
{{--            <label for="expected_deilvery_time">Expected Delivery Time<span style="color: red">*</span></label>--}}
{{--            <input type="datetime-local" class="form-control" id="expected_delivery_time" name="expected_delivery_time" value="">--}}
{{--        </div>--}}

        <div class="form-group dispatch_input">
            <label for="expected_deilvery_time">Expected Delivery Time<span style="color: red">*</span></label>
            <div class='input-group date datetimepicker'>
                <input type='text'
                       class="form-control"
                       id="expected_delivery_time"
                       value=""
                       name="expected_delivery_time"

                />
                <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
            </div>
        </div>

    </div>

    <hr>
</div>

<script>
    $(function() {
        $('.datetimepicker').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss'
        });
    });

</script>


