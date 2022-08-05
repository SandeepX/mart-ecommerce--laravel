<script>

    var transactionPurposes = [];

    var closeButton =
        '<button type="button" style="color:white !important;opacity: 1 !important;" class="close" aria-hidden="true"></button>';

    let refBillNoDiv = $('#ref_bill_no_div');
    let transactionCodeDiv= $('#transaction_code_div');
    let orderCodeDiv=$('#order_code_div');
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


    $('#store_type_code').on('change',function(e){
        e.preventDefault();
        $('#store_type_package_code').empty();
        var store_package_code = $(this).val();
        console.log(store_package_code);
        resetSetValues();
        if(store_package_code){
            $.ajax({
                type: 'GET',
                url: "{{ url('/api/store-type-packages/get-packages') }}"+ "/" + store_package_code,
            }).done(function(response) {
                $('#store_type_package_code').append('<option value="" disabled selected>Select Store Packages</option>');
                response.data.forEach(function(storeTypePackages) {
                    $('#store_type_package_code').append('<option  value="' + storeTypePackages.store_type_package_history_code + '">' +
                        storeTypePackages.package_name + '</option>');
                });
            });
        }
    });

    function resetSetValues(){
        let remarks = $('#remarks').val();
        if(remarks){
            $('#remarks').val('');
        }
    }

    $('#fromStorePackageUpdate').on('submit',function (e){
        let reasons = $('#reasons option:selected').text();
        e.preventDefault();
        Swal.fire({
            title: 'Do you want to update package?',
            showCancelButton: true,
            customClass: 'swal-wide',
            cancelButtonColor: '#d33',
            confirmButtonText: `Save`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                saveStoreBalanceControl();
            }
        });
    });

    function saveStoreBalanceControl() {

        var formAction = $('#fromStorePackageUpdate').attr('action');
        var formMethod = $('#fromStorePackageUpdate').attr('method');

        let storePackageUpdateFormData = new FormData(document.getElementById("fromStorePackageUpdate"));
        $.ajaxSetup({
            headers:
                {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
        });

        $.ajax({
            url: formAction,
            method: formMethod,
            data: storePackageUpdateFormData,
            datatype: "JSON",
            contentType: false,
            cache: false,
            processData: false
        }).done(function (response) {
            $('#storePackageUpdate').modal('hide');
            location.reload();
        }).fail(function (data) {
            displayErrorMessage(data, 'showFlashMessageModal');
            $("#showFlashMessageModal").fadeOut(10000);
        });
    }

</script>
