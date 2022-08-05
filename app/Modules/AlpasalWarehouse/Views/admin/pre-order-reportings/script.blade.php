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

        //    preorder search
        $('.preorder-search').on('click', function (e) {
            e.preventDefault();

            let storeCode = $('input[name = store_code]').val();
            if(storeCode == "")
            {
                $('#error-message-store-code').html('Store Code must be filled');
            }
            else{
                $('#error-message-store-code').html('');
            }
            let preorderCode = $('input[name = preorder_code]').val();
            if(preorderCode == "")
            {
                $('#error-message-pre-order-code').html('Preorder Code must be filled');
            }
            else{
                $('#error-message-pre-order-code').html('');
            }
            let tragetUrl ="{{route('admin.pre-orders-reporting.search',["storeCode"=>":code","preorderCode"=>":poc"])}}"

            tragetUrl = tragetUrl.replace(':code', storeCode);
            tragetUrl = tragetUrl.replace(':poc', preorderCode);
            $.ajax({
                type: 'GET',
                url: tragetUrl,
                // data: formData,
                // dataType: 'html',
            }).done(function(response) {
               $('#preorder-info').empty().html(response);
            }).fail(function(data) {
                displayErrorMessage(data);
            });


        });
    });

</script>
