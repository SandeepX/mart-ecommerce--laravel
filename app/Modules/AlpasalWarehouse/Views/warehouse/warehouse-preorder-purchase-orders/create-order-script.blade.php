<script>
    $(document).ready(function () {

        $('#purchase-order-submit').on('click',function (e){
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "Once order placed,cannot be cancelled!",
                showCancelButton: true,
                customClass: 'swal-wide',
                cancelButtonColor: '#d33',
                confirmButtonText: `Place Order`,
                width: '800px',
                position:'center',
                heightAuto: false,
                padding:'10em'
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    // $(this).submit();

                    $('#purchase-order-form').submit();
                }
            });
        });
    });

</script>
