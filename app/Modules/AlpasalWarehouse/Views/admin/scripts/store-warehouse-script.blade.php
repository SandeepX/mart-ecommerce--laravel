
<script>
    $(document).ready(function () {

        $("#warehouse_form").on("submit", function (event) {
            event.preventDefault();
            var warehouseData = new FormData(this);
            submitData(warehouseData);
        });

        // $(document).on('click', '#save', function(e) {
        //     e.preventDefault();
        //     var vendorData = new FormData($('#vendor_form'));
        //     // var vendorData = $('#vendor_form').serialize();
        //     console.log(vendorData);
        //     submitData(vendorData);
        // });


        function submitData(warehouseData) {
            var closeButton =
                '<button type="button" style="color:white !important;opacity: 1 !important;" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
            $.ajaxSetup({
                headers:
                    { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });

            $.ajax({
                url: "{{ route('admin.warehouses.store') }}",
                method: "POST",
                data: warehouseData,
                datatype: "JSON",
                contentType : false,
                cache : false,
                processData: false
            }).done(function(data) {
                // window.location.reload();
                $("#warehouse_form")[0].reset();
                $( ".select2" ).val('').trigger('change');
                $('#showFlashMessage').removeClass().addClass('alert alert-success').show().empty().html(
                    closeButton + data
                        .message);

            }).fail(function(data) {
                var flashMessage = $('#showFlashMessage');
                flashMessage. removeClass().addClass('alert alert-danger').show().empty();

                if (data.status == 500) {
                    flashMessage.html(closeButton + data.responseJSON.errors);

                }
                if (data.status == 400) {
                    flashMessage.html(closeButton + data.responseJSON.message);

                }
                if (data.status == 422) {
                    var errorString = "<ol type='1'>";
                    for (error in data.responseJSON.data) {
                        errorString += "<li>" + data.responseJSON.data[error] + "</li>";
                    }
                    errorString += "</ol>";
                    flashMessage.html(closeButton + errorString);
                }
            });

        }
    });


</script>
