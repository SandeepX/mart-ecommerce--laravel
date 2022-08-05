
<div class="row">
<hr>
<strong><h3>Warehouse Store Order Dispatch Detail</h3></strong>
<hr>

    <div class="col-sm-6">

        <div class="form-group">
            <label for="vehicle_name">Driver Name</label>
            <input type="text" class="form-control" id="driver_name" value="" name="driver_name" placeholder="Enter Driver Name">
        </div>

        <div class="form-group">
            <label for="vehicle_type">vehicle Type</label>
            <input type="text" class="form-control" id="vehicle_type" value="" name="vehicle_type" placeholder="Enter vehicle Type">
        </div>

        <div class="form-group">
            <label for="contact_number">Vehicle Contact Number</label>
            <input type="text" class="form-control" id="vehicle_contact_number" value="" name="contact_number" placeholder="Enter vehicle Contact Number">
        </div>
    </div>

    <div class="col-sm-6">
        <div class="form-group">
            <label for="vehicle_number">Vehicle Number</label>
            <input type="text" class="form-control" id="vehicle_number" value="" name="vehicle_number" placeholder="Enter vehicle Number">

        </div>

        <div class="form-group">
            <label for="expected_deilvery_time">Expected Delivery Time</label>
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

{{--        <div class="form-group">--}}
{{--            <label for="expected_deilvery_time">Expected Delivery Time</label>--}}
{{--            <input type="datetime-local" class="form-control datepicker" id="expected_delivery_time" name="expected_delivery_time" value="">--}}
{{--        </div>--}}

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



