<script>
    $(document).ready(function() {

        $('#store_code').change(function() {
            var storeCode = $('#store_code option:selected').val();
            var store_order_code = "{{ isset($locationPath) ? $locationPath['store_order_code']->store_order_code : '' }}";
            $('#store_order_code').empty();
            $('#store_preorder_code').empty();
            if (storeCode) {

                $.ajax({
                    type: 'GET',
                    url: "{{ url('/warehouse/merge-bill') }}" + '/' + storeCode,
                }).done(function(response) {
                    response.data.storeOrders.forEach(function(storeOrder) {
                        $('#store_order_code').append('<option  value="' + storeOrder.store_order_code + '">' +
                            storeOrder.store_order_code + '</option>');
                    });
                    response.data.storePreOrders.forEach(function(storePreOrder) {
                        $('#store_preorder_code').append('<option value="' + storePreOrder.store_preorder_code + '">' +
                            storePreOrder.store_preorder_code + '</option>');
                    });

                });

            }
        })
    });

</script>
