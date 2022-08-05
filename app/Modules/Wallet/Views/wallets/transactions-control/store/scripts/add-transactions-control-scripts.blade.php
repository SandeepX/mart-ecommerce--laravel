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

    function readURL(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const  fileType = file['type'];
            const validImageTypes = ['image/gif', 'image/jpeg', 'image/png'];
            if (validImageTypes.includes(fileType)) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#image_preview').attr('src', e.target.result).show();
                }
                reader.readAsDataURL(input.files[0]); // convert to base64 string
            }else{
                $('#image_preview').hide();
            }
        }
    }

    $('#proof_of_document').change(function(){
        readURL(this);
    })


    $('#action_type').on('change',function(e){
        e.preventDefault();
        $('#reasons').empty();
        $( "#extra_required_fields" ).empty();
        var action_type = $(this).val();
        var user_type_code = '{!! $userTypeCode !!}';
        resetSetValues();
        if(action_type){
            $.ajax({
                type: 'GET',
                url: "{{ url('/api/wallet/transaction-purpose') }}" + '/' + action_type +
                    '/type/'+ user_type_code,
            }).done(function(response) {
                transactionPurposes.length= 0;
                transactionPurposes = response.data;
                $('#reasons').append('<option value="" disabled selected>Select Reason</option>');
                response.data.forEach(function(reasons) {
                    $('#reasons').append('<option  value="' + reasons.wallet_transaction_purpose_code + '">' +
                        reasons.purpose + '</option>');
                });
            });
        }
    });

    function resetSetValues(){
        let amount = $('#amount').val();
        let transaction_code = $('#transaction_code').val();
        let order_code = $('#order_code').val();
        let ref_bill_no = $('#ref_bill_no').val();
        let proof_of_document = $('#proof_of_document').val();
        let remarks = $('#remarks').val();
        if(amount || transaction_code || order_code || ref_bill_no || proof_of_document || remarks){
            $('#amount').val('');
            $('#transaction_code').val('');
            $('#order_code').val('');
            $('#ref_bill_no').val('');
            $('#proof_of_document').val('');
            $('#image_preview').attr('src','#').hide();
            $('#remarks').val('');
        }
    }

    $('#reasons').on('change',function(){
        var reasons = $(this).val();
        resetSetValues();
        $( "#extra_required_fields" ).empty();
        var purposeObject = transactionPurposes.find(purpose => purpose.wallet_transaction_purpose_code === reasons);
        var requiredFields = purposeObject.required_fields;

        if(requiredFields.length > 0){
            requiredFields.forEach(function(field) {
               var fieldName = field.replaceAll("_"," ").toLowerCase().replace(/\b[a-z]/g, function(letter) {
                    return letter.toUpperCase();
                });
                $('#extra_required_fields').append('<label class="control-label">'+fieldName+'</label>' +
                    '<input name="'+field+'" class="form-control input-sm" type="text" id="'+field+'">');
            });
        }
    });


    $('#fromStoreBalanceControl').on('submit',function (e){
        let reasons = $('#reasons option:selected').text();
        e.preventDefault();
        Swal.fire({
            title: 'Do you want to save the changes in : '+reasons+'?',
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

        var formAction = $('#fromStoreBalanceControl').attr('action');
        var formMethod = $('#fromStoreBalanceControl').attr('method');

        let storeBalanceControlFormData = new FormData(document.getElementById("fromStoreBalanceControl"));
        $.ajaxSetup({
            headers:
                {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
        });

        $.ajax({
            url: formAction,
            method: formMethod,
            data: storeBalanceControlFormData,
            datatype: "JSON",
            contentType: false,
            cache: false,
            processData: false
        }).done(function (response) {
            $('#exampleModal').modal('hide');
            location.reload();
        }).fail(function (data) {
            displayErrorMessage(data, 'showFlashMessageModal');
            $("#showFlashMessageModal").fadeOut(10000);
        });
    }

</script>
