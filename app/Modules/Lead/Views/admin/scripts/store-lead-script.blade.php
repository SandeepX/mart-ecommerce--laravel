<script>

    $("#lead_form").on("submit", function (event) {
        event.preventDefault();
        var leadData = new FormData(this);
        submitData(leadData);
    });
  

    function submitData(leadData) {

        
        var closeButton =
            '<button type="button" style="color:white !important;opacity: 1 !important;" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
        $.ajaxSetup({
            headers:
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        $.ajax({
            url: "{{ route('admin.leads.store') }}",
            method: "POST",
            data: leadData,
            datatype: "JSON",
            contentType : false,
            cache : false,
            processData: false
        }).done(function(data) {
             var leadDocumentUrl = "{{ route("admin.leads.documents.create", ":lead_code") }}";
             leadDocumentUrl = leadDocumentUrl.replace(':lead_code', data.data.lead_code);
             window.location.href = leadDocumentUrl;
            
            

        }).fail(function(data) {
            if (data.status == 500) {
                $('#showFlashMessage').removeClass().addClass('alert alert-danger').show().empty().html(
                    closeButton + data
                    .responseJSON.errors);

            }
            if (data.status == 400) {
                $('#showFlashMessage').removeClass().addClass('alert alert-danger').show().empty().html(
                    closeButton + data
                    .responseJSON.message);

            }
            if (data.status == 422) {
                var errorString = "<ol type='1'>";
                for (error in data.responseJSON.errors) {
                    errorString += "<li>" + data.responseJSON.errors[error] + "</li>";
                }
                errorString += "</ol>";
                $('#showFlashMessage').removeClass().addClass('alert alert-danger').show().empty().html(
                    closeButton + errorString);
            }
        });

    }

</script>
