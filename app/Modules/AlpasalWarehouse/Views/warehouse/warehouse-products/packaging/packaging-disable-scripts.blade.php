<script>
    $(document).ready(function (){

        //close btn of error message
        var closeButton =
            '<button type="button" style="color:white !important;opacity: 1 !important;" class="close" aria-hidden="true"></button>';


        function displayErrorMessage(data,flashElementId='showFlashMessage') {

            flashElementId='#'+flashElementId;
            var flashMessage = $(flashElementId);
            flashMessage. removeClass().addClass('alert alert-danger').show().empty();

            /* if (data.status == 500) {
                 flashMessage.html(closeButton + data.responseJSON.errors);
             }
             if (data.status == 400 || data.status == 419) {
                 flashMessage.html(closeButton + data.responseJSON.message);
             }*/
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


        $(document).on('submit','.package-update-form',function (event) {
            event.preventDefault();
            $('#showFlashMessageUpdateModal').removeClass().empty();
            let packageSettingFormData = new FormData(this);
            let formAction = $(this).attr('action');
            ///console.log(formAction);

            if (packageSettingFormData && formAction){
                $.ajaxSetup({
                    headers:
                        { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') }
                });

                $.ajax({
                    url: formAction,
                    method: "POST",
                    data: packageSettingFormData,
                    datatype: "JSON",
                    contentType : false,
                    cache : false,
                    processData: false
                }).done(function(response) {
                    $('#showFlashMessagePackageUpdateModal').removeClass().addClass('alert alert-success').show().empty().html(
                        closeButton + 'Product packaging updated successfully');

                }).fail(function(data) {
                    displayErrorMessage(data,'showFlashMessagePackageUpdateModal');
                });
            }
        });

    });

</script>
