<script>
    $('#pre-order-clone-button').on('click',function(event){
        event.preventDefault();
        Swal.fire({
            title: 'Do you want to Add all product of warehouse to this Preorder?',
            showCancelButton: true,
            confirmButtonText: `Clone`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                window.location.href =($(this).attr('href'));
            } else if(result.isConfirmed){

            }
        });
    });

    $('#clone-button-by-vendor-code').on('click',function (event){
        event.preventDefault();

        Swal.fire({
            title: 'Do you want to Add all product of this Vendor to this Preorder?',
            showCancelButton: true,
            cancelButtonColor: '#d33',
            confirmButtonText: `Clone`,
        }).then((result) => {
            if (result.isConfirmed) {
                cloneVendorProducts();
            }
        });

    });

    function cloneVendorProducts(){
        var formAction = $('#clone-products-by-vendor-code').attr('action');
        var formMethod = $('#clone-products-by-vendor-code').attr('method');

        console.log(formAction);
        console.log(formMethod);

        let cloneVendorFormData = new FormData(document.getElementById("clone-products-by-vendor-code"));
        $.ajaxSetup({
            headers:
                {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
        });

        $.ajax({
            url: formAction,
            method: formMethod,
            data: cloneVendorFormData,
            datatype: "JSON",
            contentType: false,
            cache: false,
            processData: false
        }).done(function (response) {
            $('#cloneByVendor').modal('hide');
            location.reload();
        }).fail(function (data) {
            displayErrorMessage(data, 'showFlashMessageModal');
            $("#showFlashMessageModal").fadeOut(10000);
        });
    }

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

    $('#cloneByVendor').on('hide.bs.modal', function (e) {
        $('#vendor_code').prop('selectedIndex',0);
        $('#vendor_code').trigger('change');
    });


</script>
