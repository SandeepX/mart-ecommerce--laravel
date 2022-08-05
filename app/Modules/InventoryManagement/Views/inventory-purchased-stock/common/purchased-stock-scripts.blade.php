<script>

    function inventoryPurchaseStockFilterParams(){
        let params = {
            store_code: $('#store').val(),
            product_code: $('#product').val(),
            expiry_date_from: $('#expiry_date_from').val(),
            expiry_date_to: $('#expiry_date_to').val(),
            per_page: 25,
            page : 1
        }
        return params;
    }

    $(document).ready(function (){
        var params = inventoryPurchaseStockFilterParams();
        ajaxForGettingResults(params);
        $('#filter_form').on('submit',function (e){
            e.preventDefault();
            var params = inventoryPurchaseStockFilterParams();
            console.log(params);
            ajaxForGettingResults(params);
        });
    });

    function ajaxForGettingResults(params){
        $('#tableForPurchasedInventoryProductStock').html('Loading Results ....')
        $.ajax({
            type: 'GET',
            url: "{{ route('admin.inventory.purchased-stock.index') }}",
            data: params,
        }).done(function(response) {
            $('#tableForPurchasedInventoryProductStock').html('');
            $('#tableForPurchasedInventoryProductStock').html(response);
        }).fail(function (data) {
            displayErrorMessage(data, 'showFlashMessage');
            $("#showFlashMessage").fadeOut(10000);
            scroll(0,0);
        });
    }

    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        var page_value = $(this).attr('href').split('page=')[2];
        var filtered_params = inventoryPurchaseStockFilterParams();
        filtered_params.page = page_value;
        ajaxForGettingResults(filtered_params);
    });

    $(document).on('click', '#refresh', function (e) {
        e.preventDefault();
        var filtered_params = inventoryPurchaseStockFilterParams();
        ajaxForGettingResults(filtered_params);
    });

    var closeButton =
        '<button type="button" style="color:white !important;opacity: 1 !important;" class="close" aria-hidden="true"></button>';


    function displayErrorMessage(data,flashElementId='showFlashMessage') {

        flashElementId='#'+flashElementId;
        var flashMessage = $(flashElementId);
        flashMessage. removeClass().addClass('alert alert-danger').show().empty();

        if (data.status === 422) {
            var errorString = "<ol type='1'>";
            for (error in data.responseJSON.data) {
                errorString += "<li>" + data.responseJSON.data[error] + "</li>";
            }
            errorString += "</ol>";
            flashMessage.html(closeButton + errorString);
        }
        else{
            flashMessage.html(closeButton + data.responseJSON.message);
        }
    }

    $('#reset-store').on('click',function (e) {
        e.preventDefault();
        $('#store').val(null).trigger('change');
    });

    $('#reset-product').on('click',function (e) {
        e.preventDefault();
        $('#product').val(null).trigger('change');
    });

    $('#reset-expiry-from').on('click',function (e) {
        e.preventDefault();
        $('#expiry_date_from').val(null).trigger('change');
    });

    $('#reset-expiry-to').on('click',function (e) {
        e.preventDefault();
        $('#expiry_date_to').val(null).trigger('change');
    });

</script>



