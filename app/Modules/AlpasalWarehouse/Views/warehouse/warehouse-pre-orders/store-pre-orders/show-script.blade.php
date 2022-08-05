<script>
    $('#preOrderStatusForm').submit(function (e, params) {
        var localParams = params || {};

        if (!localParams.send) {
            e.preventDefault();
        }


        Swal.fire({
            title: 'Are you sure',
            text: "Once status changed, you will not be able to perform any action on this pre-order!",
            showCancelButton: true,
            customClass: 'swal-wide',
            cancelButtonColor: '#d33',
            confirmButtonText: `Save`,
            width: '800px',
            position:'center',
            heightAuto: false,
            padding:'10em'
        }).then((result) => {
            if (result.isConfirmed) {

                $(e.currentTarget).trigger(e.type, { 'send': true });
                Swal.fire({
                    title: 'Please wait...',
                    hideClass: {
                        popup: ''
                    }
                })
            }
        })
    });

    // $('body select[name="status"]').change(function () {
    //
    //     if($(this).val() === 'dispatched') {
    //         $('#wh_store_pre_order_dispatch_detail').css('display', 'block');
    //         $('#wh_store_pre_order_dispatch_detail .dispatch_input input').prop('required', true);
    //     }else{
    //         $('#wh_store_pre_order_dispatch_detail').css('display', 'none');
    //         $('#wh_store_pre_order_dispatch_detail .dispatch_input input').prop('required', false);
    //     }
    // }).trigger('change');
    // $(document).ready(function () {
    //     $('.summernote').summernote({
    //         height: 150, // set editor height
    //         minHeight: null, // set minimum height of editor
    //         maxHeight: null, // set maximum height of editor
    //         focus: false // set focus to editable area after initializing summernote
    //     });
    // });

</script>
