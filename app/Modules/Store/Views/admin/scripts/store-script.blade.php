<script>

    $("#store_form").on("submit", function (event) {
        event.preventDefault();
        var storeData = new FormData(this);
        submitData(storeData);
    });

    function reloadForm() {
            $.ajax(
                {
                    type: 'GET',
                    url: "{{route('admin.stores.create')}}",
                    datatype: "html",
                }).done(function (data) {
                    $("#store_form").empty().html(data);
            }).fail(function (data) {
                var flashMessage = $('#showFlashMessage');
                flashMessage. removeClass().addClass('alert alert-danger').show().empty();
                flashMessage.html(closeButton + data.responseJSON.message);

            });

    }

    function submitData(storeData) {
        var closeButton =
            '<button type="button" style="color:white !important;opacity: 1 !important;" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
        $.ajaxSetup({
            headers:
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        $.ajax({
            url: "{{ route('admin.stores.store') }}",
            method: "POST",
            data: storeData,
            datatype: "JSON",
            contentType : false,
            cache : false,
            processData: false
        }).done(function(data) {
            // window.location.reload();
            $('#showFlashMessage').removeClass().addClass('alert alert-success').show().empty().html(
                closeButton + data
                .message);
            reloadForm();
            //$("#store_form").empty().html(data);

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

</script>
