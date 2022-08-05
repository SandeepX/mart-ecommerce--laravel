<script>

    function inventoryProductDispatchedFilterParams(){
        let params = {
            store_code: $('#store').val(),
            product_code: $('#product').val(),
            sales_from: $('#sales_from').val(),
            sales_to: $('#sales_to').val(),
            // payment_type: $('#payment_type').val(),
            per_page: 25,
            page : 1
        }
        return params;
    }

    $(document).ready(function (){
        var params = inventoryProductDispatchedFilterParams();
        ajaxForGettingResults(params);
        $('#filter_form').on('submit',function (e){
            e.preventDefault();
            var params = inventoryProductDispatchedFilterParams();
            ajaxForGettingResults(params);
        });
    });

    function ajaxForGettingResults(params){
        $('#tableForInventoryProductDispatchedStock').html('Loading Results ....')
        $.ajax({
            type: 'GET',
            url: "{{ route('admin.inventory.sales.index') }}",
            data: params,
        }).done(function(response) {
            $('#tableForInventoryProductDispatchedStock').html('');
            $('#tableForInventoryProductDispatchedStock').html(response);
        }).fail(function (data) {
            displayErrorMessage(data, 'showFlashMessage');
            $("#showFlashMessage").fadeOut(10000);
            scroll(0,0);
        });
    }

    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        var page_value = $(this).attr('href').split('page=')[2];
        var filtered_params = inventoryProductDispatchedFilterParams();
        filtered_params.page = page_value;
        ajaxForGettingResults(filtered_params);
    });

    $(document).on('click', '#refresh', function (e) {
        e.preventDefault();
        var filtered_params = inventoryProductDispatchedFilterParams();
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

    $('#sales-from').on('click',function (e) {
        e.preventDefault();
        $('#sales_from').val(null).trigger('change');
    });

    $('#sales-to').on('click',function (e) {
        e.preventDefault();
        $('#sales_to').val(null).trigger('change');
    });

    $('#download-excel').on('click',function (e){
        e.preventDefault();
        var filterd_params = inventoryProductDispatchedFilterParams();
        //filterd_params.download_excel = true;
        var queryString = $.param(filterd_params)
        var url = "{{ route('admin.inventory.sales-record.export') }}"+'?'+queryString;
        window.open(url,'_blank');
    });


</script>



