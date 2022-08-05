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

        //    price info
        $('.change-password-btn').on('click', function (e) {
            e.preventDefault();

            user_code = $(this).attr('data-user-code');
            let tragetUrl ="{{route('admin.admin-password.edit',":code")}}"

            tragetUrl = tragetUrl.replace(':code', user_code);


            $.ajax({
                type: 'GET',
                url: tragetUrl,
                // data: formData,
                // dataType: 'html',
            }).done(function(response) {
                $('#showFlashMessage').removeClass().empty();
                $("#change-password-modal").empty().html(response);
                $('#changePassword').modal({
                    focus: false,
                    backdrop:false,
                });

            }).fail(function(data) {
                displayErrorMessage(data);
            });


        });
    });

</script>
