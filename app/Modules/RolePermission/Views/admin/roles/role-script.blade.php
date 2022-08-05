<script>
    $(document).ready(function(){
        $('#for_user_type').change(function(e){
            e.preventDefault();
            var for_user_type=$(this).val();
            console.log(for_user_type);
            if(for_user_type)
            {
               // $('#all_permissions').empty();
                $.ajax({
                    type: 'GET',
                    url: "{{ route('admin.roles.filter') }}",
                    data: {
                        for_user_type: for_user_type,
                        _token: '{{csrf_token()}}'
                    },
                }).done(function(response) {
                  $('#all_permissions').empty().html(response);
                });

            }
        });
    });
    $(document).ready(function(){
        $(document).on('click', '#check_all', function()
        {
            console.log('hello')
            $(".all-permissions").prop('checked', $(this).prop('checked'));
        });
        $(document).on('click', '.group_head', function()
        {
            var clickedGroup = $(this).attr('id');
            $('.'+clickedGroup).prop('checked', $('#'+clickedGroup).prop('checked'));
        });
        // $("#check_all").change(function () {
        //     console.log('hello')
        //     $(".all-permissions").prop('checked', $(this).prop('checked'));
        // });
        // $('.group_head').click(function(){
        //     var clickedGroup = $(this).attr('id');
        //     $('.'+clickedGroup).prop('checked', $('#'+clickedGroup).prop('checked'));
        // });
    });

</script>
