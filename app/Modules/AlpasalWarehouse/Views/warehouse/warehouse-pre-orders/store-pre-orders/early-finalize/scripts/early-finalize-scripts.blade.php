<script>

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

    $('#formEarlyFinalize').on('submit',function (e){
        e.preventDefault();
        Swal.fire({
            title: 'Do you want to Early Finalize this PreOrder?',
            showCancelButton: true,
            cancelButtonColor: '#d33',
            confirmButtonText: `Save`,
            padding:'10em',
            width:'500px'
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                saveEarlyFinalizeData();
            }
        });
    });

    function saveEarlyFinalizeData() {
        var formAction = $('#formEarlyFinalize').attr('action');
        var formMethod = $('#formEarlyFinalize').attr('method');
        let earlyFinalizeFormData = new FormData(document.getElementById("formEarlyFinalize"));
        $.ajaxSetup({
            headers:
                {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
        });
        $.ajax({
            url: formAction,
            method: formMethod,
            data: earlyFinalizeFormData,
            datatype: "JSON",
            contentType: false,
            cache: false,
            processData: false
        }).done(function (response) {
            $('#exampleModal').modal('hide');
            location.reload();
            scroll(0,0);
        }).fail(function (data) {
            displayErrorMessage(data, 'showFlashMessageModal');
            $("#showFlashMessageModal").fadeOut(10000);
        });
    }
</script>
