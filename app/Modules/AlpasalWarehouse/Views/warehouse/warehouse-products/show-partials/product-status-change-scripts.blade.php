<script>
    $(document).ready(function(){
        let productName='';
        $('.changeStatusBtn').on('click',function (){
            productName=$(this).attr('data-product-name');
            $("#productCode").val($(this).attr('data-product-code'));
            $(".productName").text($(this).attr('data-product-name'));

        });

        $('#statusChangeForm').submit(function (e){
            e.preventDefault();
            var status = $('#is_active').val();
            var productCode = $('#productCode').val();

            if(status == 1){
                message = 'change '+ productName +' product status to Active '
            }else{
                message = 'change '+ productName +' product status to Inactive '
            }
            Swal.fire({
                title: 'Do you want ' + message + '?',
                width: 500,
                padding: '5em',
                confirmButtonText: `Okay`,
                showDenyButton: true,
                denyButtonText: `Don't change`,
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#warehousePreorderProductStatusModal').modal('hide');
                    $('#statusChangeForm')[0].reset();
                    $.ajax({
                        url:"{{route('warehouse.warehouse-products-status.changeStatus')}}",
                        type:"POST",
                        data: {
                            product_code:productCode,
                            is_active:status,
                            _token: '{{csrf_token()}}'
                        },success:function (response){
                            $('#exampleModal').modal('hide');
                            Swal.fire('Changed Status !', '', 'success')
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        }
                    });

                }else if (result.isDenied) {
                    $('#exampleModal').modal('hide');
                    Swal.fire('Changes are not saved', '', 'info')
                }
            });
        });

        $('#warehouseProduct').submit(function (e){
            e.preventDefault();
            var status = $('#warehouseStatus').val();

            if(status == 1){
                message = 'change all warehouse product status to Active '
            }else{
                message = 'change all warehouse product status to Inactive '
            }
            Swal.fire({
                title: 'Do you want ' + message + '?',
                width: 500,
                padding: '5em',
                confirmButtonText: `Okay`,
                showDenyButton: true,
                denyButtonText: `Don't change`,
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#warehouseProductStatusModal').modal('hide');
                    //alert(status);
                    $('#warehouseProduct')[0].reset();
                    $.ajax({
                        url:"{{route('warehouse.warehouse-all-products-status.changeWarehouseProductStatus')}}",
                        type:"POST",
                        data: {
                            is_active:status,
                            _token: '{{csrf_token()}}'
                        },success:function (response){
                            $('#warehouseProductStatusModal').modal('hide');
                            Swal.fire('Changed Status !', '', 'success')
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        },
                    });

                }else if (result.isDenied) {
                    $('#warehouseProductStatusModal').modal('hide');
                    Swal.fire('Changes are not saved', '', 'info')
                }
            });
        });

    });




</script>

