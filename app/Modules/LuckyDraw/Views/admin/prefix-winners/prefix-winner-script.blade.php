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


        //    prefix winner
        $('.prefix-winner-btn').on('click', function (e) {
            e.preventDefault();

            let storeLuckydrawCode = $(this).attr('data-SLC');
            let tragetUrl ="{{route('admin.store-lucky-draws.getStoresForPrefixWinner',":code")}}"

            tragetUrl = tragetUrl.replace(':code', storeLuckydrawCode);

            $.ajax({
                type: 'GET',
                url: tragetUrl,
                // data: formData,
                // dataType: 'html',
            }).done(function(response) {
                $('#showFlashMessage').removeClass().empty();
                $("#prefix-winner-modal").empty().html(response);
                // $('.select2').select2();
                $('#eligible').select2();
                $('#notEligible').select2();
                $('#prefixWinnerModal').modal({
                    focus: false,
                    backdrop:false,
                });

            }).fail(function(data) {
                displayErrorMessage(data);
            });


        });

    });

</script>
