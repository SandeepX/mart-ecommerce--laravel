<script>
    $('body').on('click', '#message_view_btn', function () {
       var messageUrl = $(this).data('url');
        localStorage.setItem('activeMessageUrl',messageUrl)
        $.ajax({
            url: messageUrl,
            type: "GET",
            success: function (response) {
                $('#general-content').html("");
                $('#general-content').append(response.html);
            },
            error: function (error) {
                displayErrorMessage(error);
            }
        });
    });
</script>
