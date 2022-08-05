<script>
    $(document).ready(function (){

        //close btn of error message
        var closeButton =
            '<button type="button" style="color:white !important;opacity: 1 !important;" class="close" aria-hidden="true"></button>';

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
        //    pre order target
        $('.wpl-modal').on('click', function (e) {
            e.preventDefault();
            warehousePreOrderListingCode = $(this).attr('data-wpl-code');

            let tragetUrl ="{{route('warehouse.warehouse-pre-order-target.pre-order-target',":code")}}"

            tragetUrl = tragetUrl.replace(':code', warehousePreOrderListingCode);

            $.ajax({
                type: 'GET',
                url: tragetUrl,
                // data: formData,
                // dataType: 'html',
            }).done(function(response) {
                $('#showFlashMessage').removeClass().empty();
                $("#wpl-modal").empty().html(response);
                $('#exampleModalWPL').modal({
                    focus: false,
                    backdrop:false,
                });
            }).fail(function(data) {
                displayErrorMessage(data);
            });


        });
        $(document).on('submit','#preOrderTarget',function (e) {
            e.preventDefault();

            var formAction = $('#preOrderTarget').attr('action');
            var formMethod = $('#preOrderTarget').attr('method');
            let preOrderTarget = new FormData(document.getElementById("preOrderTarget"));
            $.ajaxSetup({
                headers:
                    {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')}
            });

            $.ajax({
                url: formAction,
                method: formMethod,
                data: preOrderTarget,
                datatype: "JSON",
                contentType: false,
                cache: false,
                processData: false
            }).done(function (response) {
                $('#exampleModalWPL').modal('hide');
                location.reload();
            }).fail(function (data) {
                displayErrorMessage(data, 'showFlashMessageModal');
                $("#showFlashMessageModal").fadeOut(10000);
            });
        });
        //    pre order target
        $('.pre-order-target-modal').on('click', function (e) {
            e.preventDefault();
            warehousePreOrderListingCode = $(this).attr('data-wplt-code');

            let tragetUrl ="{{route('warehouse.warehouse-pre-order-target.show',":code")}}"

            tragetUrl = tragetUrl.replace(':code', warehousePreOrderListingCode);

            $.ajax({
                type: 'GET',
                url: tragetUrl,
                // data: formData,
                // dataType: 'html',
            }).done(function(response) {
                $('#showFlashMessage').removeClass().empty();
                $("#pre-order-target-modal").empty().html(response);
                $('#preOrderTargetShow').modal({
                    focus: false,
                    backdrop:false,
                });
            }).fail(function(data) {
                displayErrorMessage(data);
            });


        });
    });

</script>
