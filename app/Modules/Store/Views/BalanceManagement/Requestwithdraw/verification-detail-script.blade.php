<script type="text/javascript">
        var i = 0;
        $("#add").click(function(){
            ++i;


            $("#dynamicTable").append(
                '<tr><td><input type="text" name="addmore['+i+'][payment_verification_source]" placeholder="eg.CHQ1234(cheque no)" class="form-control" /></td>' +
                '<td><input type="text" name="addmore['+i+'][amount]" class="form-control" /></td>' +
                '<td><input type="file" name="addmore['+i+'][proof]" class="form-control" /></td>' +
                '<td><input type="text" name="addmore['+i+'][remarks]" class="form-control" /></td>' +
                '<td><button type="button" class="btn btn-danger remove-tr">Remove</button></td></tr>');


            $('.select2').select2();
        });

        $(document).on('click', '.remove-tr', function(){
            $(this).parents('tr').remove();
        });


</script>
