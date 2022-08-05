<script>
    $(document).ready(function () {
        $('#warehouse_type_code').change(function() {
            var warehouseType= $(this).find(':selected').attr("data-slug");
            //alert(warehouseType);
            if(warehouseType == 'open'){
                confirm("Warning : Changing type to open will turn off all its connection with stores");
            }
        });
    });
</script>