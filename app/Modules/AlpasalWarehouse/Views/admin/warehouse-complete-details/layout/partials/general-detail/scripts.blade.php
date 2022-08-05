<script>
    // $('body #changeWarehousePassword').submit(function (e, params) {
    //     $(document).on('submit','#changeWarehousePassword', function(e,params) {
    //     var localParams = params || {};
    //
    //     if (!localParams.send) {
    //         e.preventDefault();
    //     }
    //     Swal.fire({
    //         title: 'Are you sure you want to change warehouse Password ?',
    //         showCancelButton: true,
    //         confirmButtonText: `Yes`,
    //         padding:'10em',
    //         width:'500px'
    //     }).then((result) => {
    //         if (result.isConfirmed) {
    //
    //             $(e.currentTarget).trigger(e.type, { 'send': true });
    //             Swal.fire({
    //                 title: 'Please wait...',
    //                 hideClass: {
    //                     popup: ''
    //                 }
    //             })
    //         }
    //     })
    // });


    $(document).on("keyup", "input", function(e) {
        $(document).find('span.error-text').text('');
    });


    $(document).on('submit','#changeWarehousePassword', function(e,params) {

        var localParams = params || {};

        if (!localParams.send) {
            e.preventDefault();
        }

        Swal.fire({
            title: 'Are you sure you want to change warehouse-password ?',
            showCancelButton: true,
            confirmButtonText: `Yes`,
            padding: '10em',
            width: '500px'

        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax(
                    {
                        url:$(this).attr('action'),
                        method:$(this).attr('method'),
                        data:new FormData(this),
                        processData:false,
                        dataType:'json',
                        contentType:false,
                        beforeSend:function(){
                            // $(document).find('span.error-text').text('');
                            $("#changeWarehousePasswordBtn").prop('disabled', true);
                            Swal.fire({
                                title: 'Please wait...',
                                hideClass: {
                                    popup: ''
                                }
                            })
                        },

                    }).done(function(response){
                    $("#changeWarehousePasswordBtn").prop('disabled', false);
                    $("#message_block").css("display", "block");
                    $('#message_block').append("<strong>"+ response.message +"</strong>");

                    //clearing the form input field
                    $('#changeWarehousePassword').trigger("reset");

                }).fail(function(err){
                    $("#changeWarehousePasswordBtn").prop('disabled', false);
                    for (var error in err.responseJSON.data) {
                        if(err.responseJSON.data[error].length>1)
                        {
                            var validationMessage = err.responseJSON.data[error];

                            for(var i =0;i<validationMessage.length;i++)
                            {
                                if(i==0){
                                    $('span.password_error').text(validationMessage[i]);
                                }else{
                                    $('span.password_confirmation_error').text(validationMessage[i]);
                                }

                            }
                        }else
                        {
                            $('span.password_error').text(err.responseJSON.data[error]);
                        }
                    }
                });
            }
        })
    });
</script>
