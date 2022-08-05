<script>

    $('#reset-product').click(function(e){
        e.preventDefault();
        $('#product_name').val(null).trigger('change');
    });

    $('#reset-product_variant_name').click(function(e){
        e.preventDefault();
        $('#product_variant_name').val(null).trigger('change');
    });

    $('#reset-vendor_name').click(function(e){
        e.preventDefault();
        $('#vendor_code').val('null').trigger('change');
    });

    $('.excel-export-daybook').on('click',function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        let query = {
            warehouse_code: $('#warehouse').val(),
            vendor: $('#vendor').val(),
            product: $('#product').val(),
            from_date: $('#from_date').val(),
            to_date: $('#to_date').val(),
            order_type: $('#order_type').val(),
            product_name: $('#product_name').val(),
            product_variant_name: $('#product_variant_name').val(),
            page : 1
        }
        var excelDownloadUrl = url +'?' + $.param(query)
        window.location = excelDownloadUrl;
    });
</script>
