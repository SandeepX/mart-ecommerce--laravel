<div class="modal fade" id="warehousePreorderProductMicroDisableModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                <form method="post" id="warehousePreorderProductMicroDisableForm">

                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label class="control-label">Disable/Enable Micro Packaging</label>
                        <select class="form-control input-sm" name="packaging_status" id="warehousePreOrderproductMicroPackagingStatus" required autocomplete="off">
                            <option value="">select option</option>
                            <option value="1">Enable</option>
                            <option value="0">Disable</option>
                        </select>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit"  class="btn btn-success">Update</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
@push('scripts')
<script>

    $('#warehousePreorderProductMicroDisableForm').submit(function (e){
        e.preventDefault();

        var status = $('#warehousePreOrderproductMicroPackagingStatus').val();
        //var warehousePreorderListingCode = $('#warehousePreorerListingCode').val();

        if(status == 1){
            var statusName = 'Enable';
        }else{
            var statusName = 'Disable'
        }

        let targetUrl ="{{route('warehouse.warehouse-pre-orders.update-micro-packaging',["warehousePreOrderListingCode"=>":code"])}}"
        targetUrl = targetUrl.replace(':code',"{{ $warehousePreOrderListingCode }}");

        let formData  = new FormData(this);
        Swal.fire({
            title: 'Are you sure you want to '+ statusName + 'All Product micro packaging?',
            showDenyButton: true,
            confirmButtonText: `Yes`,
            denyButtonText: `No`,
            padding:'10em',
            width:'500px'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#warehousePreorderProductMicroDisableModal').modal('hide');
                $('#warehousePreorderProductMicroDisableForm')[0].reset();

                $.ajaxSetup({
                    headers:
                        { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') }
                });

                $.ajax({
                    url: targetUrl,
                    method: "POST",
                    data: formData,
                    datatype: "JSON",
                    contentType : false,
                    cache : false,
                    processData: false
                }).done(function(data) {
                    $('#warehousePreorderProductMicroDisableModal').modal('hide');
                    Swal.fire('Changed Status !', '', 'success')
                    setTimeout(function() {
                        location.reload();
                    }, 1000);

                }).fail(function(data) {
                    displayErrorMessage(data)
                });


            } else if (result.isDenied) {
                Swal.fire('change not saved', '', 'info')
            }
        })
    })
</script>
@endpush
