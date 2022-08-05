<script>

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
        transactionCodeDiv.hide();
        orderCodeDiv.hide();
        refBillNoDiv.hide();
        var action_type = $(this).val();
        resetSetValues();

        if(action_type == 'increment'){
            var options0 ='<option value="" disabled selected>Select Reason</option>'+
            '<option value="rewards">Rewards</option>' +
            '<option value="interest">Interest</option>'+
            '<option value="refund_release">Refund Release</option>'+
            '<option value="sales_reconciliation_increment">Sales Reconciliation Increment</option>'+
            '<option value="pre_orders_sales_reconciliation_increment">PreOrder Sales Reconciliation Increment</option>'+
            '<option value="janata_bank_increment">Janata Bank (+)</option>'+
            '<option value="cash_received">Cash Received</option>'+
            '<option value="transaction_correction_increment">Transaction Correction Increment</option>';
            $('#reasons').empty().html(options0);
        }else if (action_type == 'deduction'){
            var options2 ='<option value="" disabled selected>Select Reason</option>'+
            '<option value="royalty">Royalty</option>' +
            '<option value="annual_charge">Annual Charges</option>' +
            '<option value="refundable">Refundable</option>'+
            '<option value="sales_reconciliation_deduction">Sales Reconciliation Deduction</option>'+
            '<option value="pre_orders_sales_reconciliation_deduction">PreOrder Sales Reconciliation Deduction</option>'+
            '<option value="initial_registrations">Initial Registrations</option>'+
            '<option value="transaction_correction_deduction">Transaction Correction Deduction</option>';
            $('#reasons').empty().html(options2);
        }else{
            var options3 = '<option value="">Select Reason</option>';
            $('#reasons').empty().html(options3);
        }

        var reasons = $('#reasons').val();
        if(reasons=='sales_reconciliation_increment'
            ||
            reasons=='sales_reconciliation_deduction'
            ||
            reasons=='pre_orders_sales_reconciliation_increment'
            ||
            reasons=='pre_orders_sales_reconciliation_deduction'
        ) {
            refBillNoDiv.show();
            orderCodeDiv.show();

        }
        if(reasons=='transaction_correction_deduction'
           ||
          reasons=='transaction_correction_increment'){
            transactionCodeDiv.show();
        }

        if(reasons=='cash_received'){
            refBillNoDiv.show();
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

       // console.log(reasons);
        $('#transaction_code_div').hide();
        $('#order_code_div').hide();
        refBillNoDiv.hide();

        resetSetValues();


        if(reasons=='sales_reconciliation_increment'
            ||
            reasons=='sales_reconciliation_deduction'
            ||
            reasons=='pre_orders_sales_reconciliation_increment'
            ||
            reasons=='pre_orders_sales_reconciliation_deduction'
        ) {
             //$('#sales_reconciliation_attributes').show();
            //console.log(5425);
            //console.log($('#abcd'));
            refBillNoDiv.show();
          //  $('#abcd').hide();
             $('#order_code_div').show();

        }
        if(reasons=='transaction_correction_deduction'
            ||
            reasons=='transaction_correction_increment'){
            $('#transaction_code_div').show();
        }

        if(reasons=='cash_received'){
            refBillNoDiv.show();
        }
    });


    $('#fromStoreBalanceControl').on('submit',function (e){
        let reasons = $('#reasons').val();
        var reasons_text = reasons.replaceAll('_',' ');
        console.log(reasons_text);
           e.preventDefault();
            Swal.fire({
                title: 'Do you want to save the changes in : '+reasons_text+'?',
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
