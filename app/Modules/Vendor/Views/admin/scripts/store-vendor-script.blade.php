<script>

    $("#vendor_form").on("submit", function (event) {
        event.preventDefault();
        var vendorData = new FormData(this);
        submitData(vendorData);
    });
    // $(document).on('click', '#save', function(e) {
    //     e.preventDefault();
    //     var vendorData = new FormData($('#vendor_form'));
    //     // var vendorData = $('#vendor_form').serialize();
    //     console.log(vendorData);
    //     submitData(vendorData);
    // });
    function reloadForm() {
        $.ajax(
            {
                type: 'GET',
                url: "{{route('admin.stores.create')}}",
                datatype: "html",
            }).done(function (data) {
            $("#vendor_form").empty().html(data);
        }).fail(function (data) {
            var flashMessage = $('#showFlashMessage');
            flashMessage. removeClass().addClass('alert alert-danger').show().empty();
            flashMessage.html(closeButton + data.responseJSON.message);

        });

    }

    function submitData(vendorData) {
        var closeButton =
            '<button type="button" style="color:white !important;opacity: 1 !important;" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
        $.ajaxSetup({
            headers:
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        $.ajax({
            url: "{{ route('admin.vendors.store') }}",
            method: "POST",
            data: vendorData,
            datatype: "JSON",
            contentType : false,
            cache : false,
            processData: false
        }).done(function(data) {
           // sessionStorage.setItem('save_order',true);
           //// window.location.reload();
            $("#vendor_form")[0].reset();
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

</script>
