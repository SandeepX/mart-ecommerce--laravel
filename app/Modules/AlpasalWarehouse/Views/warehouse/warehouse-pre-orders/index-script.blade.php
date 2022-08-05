<script>
    $(document).ready(function () {
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

        $('.finalize-btn').on('click',function (e){
            e.preventDefault();
            let hrefAction = $(this).attr('href');
            Swal.fire({
                title: 'Are you sure, you want to finalize?',
                text: "Once finalized cannot be cancelled",
                showCancelButton: true,
                customClass: 'swal-wide',
                cancelButtonColor: '#d33',
                confirmButtonText: `Finalize`,
                width: '800px',
                position:'center',
                heightAuto: false,
                padding:'10em',


            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    // $(this).submit();
                    window.location.href= hrefAction;
                }
            });
        });


        $('.cancel-btmmm').on('click',function (e){
            e.preventDefault();
            let hrefAction = $(this).attr('href');
          //  let csrfToken ={{csrf_token()}};
            Swal.fire({
                title: 'Are you sure, you want to cancel?',
                text: "Once cancel cannot be finalized",
                showCancelButton: true,
                customClass: 'swal-wide',
                cancelButtonColor: '#d33',
                confirmButtonText: `Cancel Pre-order`,
                cancelButtonText: 'Cancel Action',
                width: '800px',
                position:'center',
                heightAuto: false,
                padding:'10em',
                html: "<form id='cancel-form' action="+hrefAction + " method='post'> " +
                    "<input type='hidden' value=''> " +
                    "<input id='user' type='email'> " +
                    "<input id='pass' type='password'> " +
                    "<input id='idno' type='number'> " +
                    "</form>",
                focusConfirm: false,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    // $(this).submit();
                    console.log('1')
                    //window.location.href= hrefAction;
                }
            });
        });

        $('.cancel-btn').on('click',function (e){

            e.preventDefault();
            let hrefAction = $(this).attr('data-href');
            (async () => {

                const { value: formValues } = await Swal.fire({
                   // title: 'Multiple inputs',
                    title: 'Are you sure, you want to cancel?' +
                        '<br> Once cancelled cannot be finalized',
                    text: "Once cancelled cannot be finalized",
                    showCancelButton: true,
                    customClass: 'swal-wide',
                    cancelButtonColor: '#dd3333',
                    confirmButtonText: `Cancel Pre-order`,
                    cancelButtonText: 'Cancel Action',
                    width: '800px',
                    position:'center',
                    heightAuto: false,
                    padding:'10em',
                   // input:'textarea',
                    html:
                       // '<input id="remarks" name="remarks" class="swal2-input">'+

                    '<textarea required id="remarks" rows="10" style="height:auto!important" name="remarks" class="swal2-input" placeholder="Type your remarks here..."></textarea>',
                    focusConfirm: false,
                    preConfirm: () => {
                        let remarksValue =document.getElementById('remarks').value;
                        if (remarksValue){
                            return {
                                'remarks':document.getElementById('remarks').value,
                            }
                        }
                        else{
                            Swal.showValidationMessage('Remarks is required.');
                        }


                            //document.getElementById('swal-input2').value
                    }
                })

                if (formValues) {
                   // Swal.fire(JSON.stringify(formValues))
                    let remarks = {
                        remarks:formValues['remarks']
                    };
                    console.log(remarks);
                    $.ajaxSetup({
                        headers:
                            { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') }
                    });

                    $.ajax({
                        url: hrefAction,
                        method: "POST",
                        data: remarks,
                        datatype: "JSON",
                      //  contentType : false,
                      //  cache : false,
                       // processData: false
                    }).done(function(response) {
                         window.location.reload();
                        //console.log(response);


                    }).fail(function(data) {
                        displayErrorMessage(data);
                    });

                }

            })()
        });
    });
</script>
