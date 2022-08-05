<script type="text/javascript">
    var i = 0;
    $("#add").click(function(){
        ++i;


        $("#dynamicTable").append(
            '<tr><td><textarea type="text" name="terms[]" placeholder="Enter Terms and Conditions" class="form-control"></textarea></td>' +
            '<td><button type="button" class="btn btn-danger remove-tr">Remove</button></td></tr>');

    });

    $(document).on('click', '.remove-tr', function(){
        $(this).parents('tr').remove();
    });


</script>
