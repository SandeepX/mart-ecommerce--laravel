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

        //let productVariantCode;
        let productName;
        let warehouseProductMasterCode;

        $('.update-price-btn').on('click', function (e) {
            e.preventDefault();
            ///productVariantCode = $(this).attr('data-variant-code');
            productName = $(this).attr('data-product-name');
            warehouseProductMasterCode = $(this).attr('data-wpm-code');
            let formAction ="{{route('warehouse.warehouse-products.update-price-setting',":code")}}"

            formAction = formAction.replace(':code', warehouseProductMasterCode);
           $('#update-price-form').attr('action',formAction);

            $('#updatePriceSettingModal').modal({
                focus: false,
                backdrop:false,
            });

        });

        $('#updatePriceSettingModal').on('show.bs.modal', function (e) {
            // do something...
           $('#variant-td').html(productName);
          // $('#product_variant_code').val(productVariantCode);
           $('#warehouse_product_master_code').val(warehouseProductMasterCode);
        });


        $('.price-history-btn').on('click', function (e) {
           // e.preventDefault();

            warehouseProductMasterCode = $(this).attr('data-wpm-code');
            let tragetUrl ="{{route('warehouse.warehouse-products.price-histories',":code")}}"

            tragetUrl = tragetUrl.replace(':code', warehouseProductMasterCode);

            $.ajax({
                type: 'GET',
                url: tragetUrl,
               // data: formData,
                // dataType: 'html',
            }).done(function(response) {
               // console.log(response);
                $('#showFlashMessage').removeClass().empty();
                $("#price-history-modal").empty().html(response);
                $('#priceHistoryModal').modal({
                    focus: false,
                    backdrop:false,
                });

            }).fail(function(data) {
                displayErrorMessage(data);
            });


        });


        //stock history modal
        $('.stock-history-btn').on('click', function (e) {
             e.preventDefault();

            warehouseProductMasterCode = $(this).attr('data-wpm-code');
            let tragetUrl ="{{route('warehouse.warehouse-products.stock-histories',":code")}}"

            tragetUrl = tragetUrl.replace(':code', warehouseProductMasterCode);

            $.ajax({
                type: 'GET',
                url: tragetUrl,
                // data: formData,
                // dataType: 'html',
            }).done(function(response) {
                $('#showFlashMessage').removeClass().empty();
                $("#stock-history-modal").empty().html(response);
                $('#stockHistoryModal').modal({
                    focus: false,
                    backdrop:false,
                });

            }).fail(function(data) {
                displayErrorMessage(data);
            });


        });

    //    price info
        $('.price-info-btn').on('click', function (e) {
            e.preventDefault();

            warehouseProductMasterCode = $(this).attr('data-wpm-code');
            let tragetUrl ="{{route('warehouse.warehouse-products.price-info',":code")}}"

            tragetUrl = tragetUrl.replace(':code', warehouseProductMasterCode);

            $.ajax({
                type: 'GET',
                url: tragetUrl,
                // data: formData,
                // dataType: 'html',
            }).done(function(response) {
                $('#showFlashMessage').removeClass().empty();
                $("#price-info-modal").empty().html(response);
                $('#priceInfoModal').modal({
                    focus: false,
                    backdrop:false,
                });

            }).fail(function(data) {
                displayErrorMessage(data);
            });


        });
    });

</script>
