<script type="text/javascript">
    $(document).ready(function(){
        $('#vendor_code').change(function(e){
            e.preventDefault();
            var for_user_type=$(this).val();
            if(for_user_type)
            {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.roles.filter') }}",
                    data: {
                        for_user_type: for_user_type,
                        _token: '{{csrf_token()}}'
                    },
                }).done(function(response) {

                });

            }
        });
    });
</script>
