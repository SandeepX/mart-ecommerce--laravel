<script>
    $(document).ready(function() {
        $('#toggle-password').on('click',function (e) {

            $(this).toggleClass("fa-eye fa-eye-slash");
            var passwordInput = $('#password');

            const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
            passwordInput.attr('type', type);
           /* var input = $($(this).attr("toggle"));
            if (input.attr("type") == "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }*/
        });
    });
</script>