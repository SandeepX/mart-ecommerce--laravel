<script>
    $(document).ready(function (){

        //close btn of error message
        var closeButton =
            '<button type="button" style="color:white !important;opacity: 1 !important;" class="close" aria-hidden="true"></button>';

        function displayErrorMessage(data,flashElementId='showFlashMessage') {
            flashElementId='#'+flashElementId;
            var flashMessage = $(flashElementId);
            flashMessage. removeClass().addClass('alert alert-danger').show().empty();

            if (data.status == 422) {
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
        //    pre order target
        $('.stpm-edit').on('click', function (e) {
            e.preventDefault();
            storeTypePackageMasterCode = $(this).attr('data-stpm-code');

            let tragetUrl ="{{route('admin.store-type-packages.edit',":code")}}"

            tragetUrl = tragetUrl.replace(':code', storeTypePackageMasterCode);

            $.ajax({
                type: 'GET',
                url: tragetUrl,
                // data: formData,
                // dataType: 'html',
            }).done(function(response) {
                $('#showFlashMessage').removeClass().empty();
                $("#stpm-modal").empty().html(response);
                $('#exampleModalEdit').modal({
                    focus: false,
                    backdrop:false,
                });
            }).fail(function(data) {
                displayErrorMessage(data);
            });


        });
    });

</script>
