<script>
    var closeButton =
        '<button type="button" style="color:white !important;opacity: 1 !important;" class="close" aria-hidden="true"></button>';


    function displayErrorMessage(data,flashElementId='showFlashMessageModal') {

        flashElementId='#'+flashElementId;

        var flashMessage = $(flashElementId);

        flashMessage.removeClass().addClass('alert alert-danger').show().empty();

        if (data.status == 422) {
            var errorString = "<ol type='1'>";
            for (error in data.responseJSON.data) {
                errorString += "<li>" + data.responseJSON.data[error] + "</li>";
            }
            errorString += "</ol>";
            flashMessage.html(closeButton + errorString);
            console.log(flashMessage.html(closeButton + errorString));
        }
        else{
            flashMessage.html(closeButton + data.responseJSON.message);
        }
    }


    $('#formMassPriceSettingOfProduct').on('submit',function (e){
        e.preventDefault();
        Swal.fire({
            title: 'Do you want to Update Price Setting',
            showCancelButton: true,
            customClass: 'swal-wide',
            cancelButtonColor: '#d33',
            confirmButtonText: `Save`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                saveMassPriceSettingOfProduct();
            }
        });
    });

    function saveMassPriceSettingOfProduct() {

        var formAction = $('#formMassPriceSettingOfProduct').attr('action');
        var formMethod = $('#formMassPriceSettingOfProduct').attr('method');

        let massStorePriceSetting = new FormData(document.getElementById("formMassPriceSettingOfProduct"));
        $.ajaxSetup({
            headers:
                {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
        });

        $.ajax({
            url: formAction,
            method: formMethod,
            data: massStorePriceSetting,
            datatype: "JSON",
            contentType: false,
            cache: false,
            processData: false
        }).done(function (response) {
            $('#priceSettingModal').modal('hide');
            location.reload();
        }).fail(function (data) {
            displayErrorMessage(data, 'showMassPriceError');
            $("#showMassPriceError").fadeOut(10000);
        });
    }
</script>
