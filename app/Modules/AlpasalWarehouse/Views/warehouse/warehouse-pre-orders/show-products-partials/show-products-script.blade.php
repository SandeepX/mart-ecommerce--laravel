<script>
    $(document).ready(function () {

        //close btn of error message
        var closeButton =
            '<button type="button" style="color:white !important;opacity: 1 !important;" class="close" aria-hidden="true"></button>';


        function displayErrorMessage(data, flashElementId = 'showFlashMessage') {

            flashElementId = '#' + flashElementId;
            var flashMessage = $(flashElementId);
            flashMessage.removeClass().addClass('alert alert-danger').show().empty();

            if (data.status == 422) {
                var errorString = "<ol type='1'>";
                for (error in data.responseJSON.data) {
                    errorString += "<li>" + data.responseJSON.data[error] + "</li>";
                }
                errorString += "</ol>";
                flashMessage.html(closeButton + errorString);
            } else {
                flashMessage.html(closeButton + data.responseJSON.message);
            }
        }

        //edit variant price modal on click
        $(document).on('click','.view-variant-btn',function (e) {
            e.preventDefault();

            var productCode = $(this).attr('data-product-code');
            var warehousePreOrderCode =  $(this).attr('data-wpol-code');

            if(productCode,warehousePreOrderCode){
                let targetUrl="{{route('warehouse.warehouse-pre-orders.view-price',['warehousePreOrderCode'=>':warehousePreOrderCode','productCode'=>':productCode'])}}";
                targetUrl = targetUrl.replace(':warehousePreOrderCode', warehousePreOrderCode);
                targetUrl = targetUrl.replace(':productCode', productCode);
                $.ajax({
                    type: 'GET',
                    url: targetUrl,
                    // dataType: 'html',
                }).done(function(response) {
                    $('#showFlashMessageModal').removeClass().empty();
                    $("#price-view-table-modal").empty().html(response);

                    $('#priceViewModal').modal({
                        focus: false,
                        backdrop:false,
                    });

                    $('#showFlashMessage').removeClass().empty();

                }).fail(function(data) {
                    displayErrorMessage(data);
                });
            }
        });

    });
</script>
