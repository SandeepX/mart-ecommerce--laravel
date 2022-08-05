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

    $('#formEditOfflinePayment').submit(function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Are you sure you want to Update Offline Payments?',
            showCancelButton: true,
            confirmButtonText: `Yes`,
            padding:'10em',
            width:'500px'
        }).then((result) => {
            if (result.isConfirmed) {
                saveRemarksToPayment();
            }
        })
    });

    function saveRemarksToPayment(){
        var formAction = $('#formEditOfflinePayment').attr('action');
        var formMethod = $('#formEditOfflinePayment').attr('method');

        let offlinePaymentUpdateData = new FormData(document.getElementById("formEditOfflinePayment"));
        $.ajaxSetup({
            headers:
                {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
        });
        $.ajax({
            url: formAction,
            method: formMethod,
            data: offlinePaymentUpdateData,
            datatype: "JSON",
            contentType: false,
            cache: false,
            processData: false
        }).done(function (response) {
            $('#offlinePaymentRemarks').modal('hide');
            location.reload();
            scroll(0,0);
        }).fail(function (data) {
            displayErrorMessage(data, 'showFlashMessageModal');
            $("#showFlashMessageModal").fadeOut(10000);
        });
    }
</script>
