<div class="modal fade" id="warehousePreorderProductStatusModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <strong class="modal-title warehouseName" id="exampleModalLabel">Change status of All preorder product of this warehouse </strong>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="showFlashMessageModal"></div>
                <form method="post" id="warehousePreorderProduct">


                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="warehousePreorerListingCode" id="warehousePreorerListingCode" value="">

                    <div class="form-group">
                        <label class="control-label">Change Status</label>
                        <select  class="form-control input-sm" name="is_active" id="warehousePreOrderproductStatus" required autocomplete="off">
                            <option value="">select status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit"  class="btn btn-success">Change</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

@push('scripts')

<script>


    $('.changeStatus').click(function (e){
        $("#warehousePreorerListingCode").val($(this).attr('data-whplc'));
    });

    $('#warehousePreorderProduct').submit(function (e){
        e.preventDefault();

            var status = $('#warehousePreOrderproductStatus').val();
            var warehousePreorderListingCode = $('#warehousePreorerListingCode').val();

            if(status == 1){
                var statusName = 'Active';
            }else{
                var statusName = 'Inactive'
            }
            Swal.fire({
                title: 'Are you sure you want to change All Product status to '+ statusName +' ?',
                showDenyButton: true,
                confirmButtonText: `Yes`,
                denyButtonText: `No`,
                padding:'10em',
                width:'500px'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#warehousePreorderProductStatusModal').modal('hide');
                    $('#warehousePreorderProduct')[0].reset();
                    $.ajax({
                        url:"{{route('warehouse.warehouse-pre-order.All-products-status.changeStatus')}}",
                        type:"POST",
                        data: {
                            warehouse_preorder_listing_code:warehousePreorderListingCode,
                            is_active: status,
                            _token: '{{csrf_token()}}'
                        },success:function (response){
                           // console.log(response)
                            $('#warehousePreorderProductStatusModal').modal('hide');
                            Swal.fire('Changed Status !', '', 'success')
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        }
                    });


                } else if (result.isDenied) {
                    Swal.fire('change not saved', '', 'info')
                }
            })
    })
</script>

@endpush

