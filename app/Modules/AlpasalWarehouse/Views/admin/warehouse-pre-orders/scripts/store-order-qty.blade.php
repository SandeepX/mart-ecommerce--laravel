<script>
    $(document).ready(function (){

        //close btn of error message
        var closeButton =
            '<button type="button" style="color:white !important;opacity: 1 !important;" class="close" aria-hidden="true"></button>';


        function displayErrorMessage(data) {
            var flashMessage = $('#showFlashMessage');
            flashMessage. removeClass().addClass('alert alert-danger').show().empty();

            if (data.status == 500) {
                flashMessage.html(closeButton + data.responseJSON.errors);

            }
            if (data.status == 400 || data.status == 419) {
                flashMessage.html(closeButton + data.responseJSON.message);

            }
            if (data.status == 422) {
                var errorString = "<ol type='1'>";
                for (error in data.responseJSON.data) {
                    errorString += "<li>" + data.responseJSON.data[error] + "</li>";
                }
                errorString += "</ol>";
                flashMessage.html(closeButton + errorString);
            }
        }

        //    store order qty
        $('.store-order-qty-btn').on('click', function (e) {
            e.preventDefault();

            let vendorCode = $(this).attr('data-vendor-code');
            let warehousePreOrderListingCode = $(this).attr('data-wplc-code');
            let productCode = $(this).attr('data-product-code');
            let productVariantCode = $(this).attr('data-product-variant-code');

            let tragetUrl ="{{route('admin.store-order-qty',["vendorCode"=>":code","warehousePreOrderListingCode"=>":wplc","productCode"=>":product"])}}"

            tragetUrl = tragetUrl.replace(':code', vendorCode);
            tragetUrl = tragetUrl.replace(':wplc', warehousePreOrderListingCode);
            tragetUrl = tragetUrl.replace(':product', productCode);
            tragetUrl = tragetUrl.replace(':productVariant',productVariantCode);
            tragetUrl= tragetUrl+'?productVariantCode='+productVariantCode;
            $.ajax({
                type: 'GET',
                url: tragetUrl,
                // data: formData,
                // dataType: 'html',
            }).done(function(response) {
                $('#showFlashMessage').removeClass().empty();
                $("#store-order-qty-modal").empty().html(response);
                $('#storeOrderQtyModal').modal({
                    focus: false,
                    backdrop:false,
                });

            }).fail(function(data) {
                displayErrorMessage(data);
            });


        });

        //    store order qty for finalized
        $('.store-order-qty-finalized-btn').on('click', function (e) {
            e.preventDefault();

            let vendorCode = $(this).attr('data-vendor-code');
            let warehousePreOrderListingCode = $(this).attr('data-wplc-code');
            let productCode = $(this).attr('data-product-code');
            let tragetUrl ="{{route('admin.store-order-qty-finalized',["vendorCode"=>":code","warehousePreOrderListingCode"=>":wplc","productCode"=>":product"])}}"

            tragetUrl = tragetUrl.replace(':code', vendorCode);
            tragetUrl = tragetUrl.replace(':wplc', warehousePreOrderListingCode);
            tragetUrl = tragetUrl.replace(':product', productCode);

            $.ajax({
                type: 'GET',
                url: tragetUrl,
                // data: formData,
                // dataType: 'html',
            }).done(function(response) {
                $('#showFlashMessage').removeClass().empty();
                $("#store-order-qty-modal").empty().html(response);
                $('#storeOrderQtyModal').modal({
                    focus: false,
                    backdrop:false,
                });

            }).fail(function(data) {
                displayErrorMessage(data);
            });


        });
    });

</script>
