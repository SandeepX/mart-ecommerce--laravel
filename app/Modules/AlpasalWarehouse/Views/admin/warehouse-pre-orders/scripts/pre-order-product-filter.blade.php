<script type="text/javascript">
    $(document).ready(function(){
        $('#vendor_code').change(function(e){
            e.preventDefault();
            var vendor_code=$(this).val();
            var product_code = "{{ isset($filterParameters) ? $filterParameters['product_code'] : '' }}";
            $('#product_code').empty();
            $('#product_variant_code').empty();
            if(vendor_code)
            {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.products-of-vendor') }}",
                    data: {
                        vendor_code: vendor_code,
                        _token: '{{csrf_token()}}'
                    },
                    success: function (data) {
                        $('#product_code').append(data);

                    }
                });
                $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.products-of-vendor') }}",
                    data: {
                        vendor_code: vendor_code,
                        _token: '{{csrf_token()}}'
                    },
                }).done(function(response) {
                    $('#product_code').append('<option value="" selected >--Select An Option--</option>');
                    response.forEach(function(productInVendor) {
                        $('#product_code').append('<option ' + ((productInVendor.product_code ===
                            product_code) ?
                            "selected" : '') + ' value="' + productInVendor.product_code + '">' +
                            productInVendor.product_name + '</option>');
                    });
                    productChange();
                });
            }
        }).trigger('change');
    });
    function productChange() {
        var product_code = $('#product_code option:selected').val();
        var product_variant_code = "{{ isset($filterParameters) ? $filterParameters['product_variant_code'] : '' }}";
        if (product_code) {
            $('#product_variant_code').empty();
            //  $('#tole_street').empty();
            if (product_code) {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.variants-of-product') }}",
                    data: {
                        product_code: product_code,
                        _token: '{{csrf_token()}}'
                    },
                }).done(function(response) {
                    $('#product_variant_code').append('<option value="" selected >--Select An Option--</option>');
                    response.forEach(function(productVariant) {
                        $('#product_variant_code').append('<option ' + ((productVariant.product_variant_code ===
                            product_variant_code) ?
                            "selected" : '') + ' value="' + productVariant.product_variant_code + '">' +
                            productVariant.product_variant_name + '</option>');
                    });
                });

            }
        }
    }
</script>
