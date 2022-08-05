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

        $('.purchase-return-btn').on('click', function (e) {
            e.preventDefault();
           // let warehouseOrderCode = $(this).attr('data-wo-code');
            let warehouseOrderDetailCode = $(this).attr('data-wod-code');
            let formAction ="{{route('warehouse.warehouse-purchase-orders.return-order',":code")}}"

            formAction = formAction.replace(':code', warehouseOrderDetailCode);
            $('#purchase-return-form').attr('action',formAction);

            $('#purchaseReturnModal').modal({
                focus: false,
                backdrop:false,
            });

        });

        /*$('#purchaseReturnModal').on('show.bs.modal', function (e) {
            // do something...
            $('#variant-td').html(productName);
            // $('#product_variant_code').val(productVariantCode);
            $('#warehouse_product_master_code').val(warehouseProductMasterCode);
        });*/

    });

</script>
