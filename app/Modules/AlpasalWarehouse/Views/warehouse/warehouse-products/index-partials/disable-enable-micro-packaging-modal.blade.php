<div class="modal fade" id="warehouseProductsMicroDisableModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <strong class="modal-title warehouseName" id="exampleModalLabel">Change status of all product of this warehouse </strong>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="warehouseProductsMicroDisableModalFlashMsg"></div>
                <form method="post" id="warehouseProductsMicroDisableForm">

                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label class="control-label">Disable/Enable Micro Packaging</label>
                        <select class="form-control input-sm" name="packaging_status" id="warehouseProductsMicroPackagingStatus" required autocomplete="off">
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

        $('#warehouseProductsMicroDisableModal').on('hidden.bs.modal', function () {
            $('#warehouseProductsMicroDisableModalFlashMsg').removeClass().empty();
            $('#warehouseProductsMicroDisableForm')[0].reset();
        });

        $('#warehouseProductsMicroDisableForm').submit(function (e){

            e.preventDefault();

            var status = $('#warehouseProductsMicroPackagingStatus').val();
            //var warehousePreorderListingCode = $('#warehousePreorerListingCode').val();

            if(status == 1){
                var statusName = 'Enable';
            }else{
                var statusName = 'Disable'
            }

            let targetUrl ="{{route('warehouse.warehouse-products.update-micro-packaging')}}"
            {{--targetUrl = targetUrl.replace(':code',"{{ $warehouseCode }}");--}}

            let formData  = new FormData(this);
            Swal.fire({
                title: 'Are you sure you want to '+ statusName + ' all products micro packaging?',
                showDenyButton: true,
                confirmButtonText: `Yes`,
                denyButtonText: `No`,
                padding:'10em',
                width:'500px'
            }).then((result) => {
                if (result.isConfirmed) {
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
                        //$('#warehouseProductsMicroDisableModal').modal('hide');
                        $('#warehouseProductsMicroDisableModal').modal('hide');
                        $('#warehouseProductsMicroDisableForm')[0].reset();
                        Swal.fire('Changed Status !', '', 'success')
                        setTimeout(function() {
                            location.reload();
                        }, 1000);

                    }).fail(function(data) {
                        displayErrorMessage(data,'warehouseProductsMicroDisableModalFlashMsg')
                    });


                } else if (result.isDenied) {
                    Swal.fire('change not saved', '', 'info')
                }
            })
        })
    </script>
@endpush

