<script>

    // miscellaneous model popup
    $('body').on('click', '#miscellaneous-view-btn', function () {

        var paymentCode = document.getElementById("miscellaneous-view-btn").value;
        $.ajax({
            // url: '/admin/stores/' + paymentCode + '/miscellaneous/details',
            url: $(this).data('url'),
            type: "GET",
            success: function (response) {

                // console.log(response)
                $('.miscellaneous-detail-modal-content').html("");
                $('.miscellaneous-detail-modal-content').append(response.html);
            },
            error: function (error) {
                displayErrorMessage(error);
            }
        });
    });


    //miscellaneous payment respond toggle
    $('body').on('click', '#respond_btn', function () {
        $("#respond_form").toggle();
    });


    // miscellaneous payment filter
    $('body').on('click', '#miscellaneous_filter_btn', function (){
        var frm = $('#miscellaneous_filter_form');
        frm.submit(function (e) {
            e.preventDefault();

            $.ajax({
                type: frm.attr('method'),
                url: frm.attr('action'),
                data: frm.serialize(),
                success: function (response) {
                    $('#general-content').html("");
                    $('#general-content').append(response.html);
                },
                error: function (error) {
                    displayErrorMessage(error);
                },
            });
        });
    });

    // miscellaneous payment pagination
    $('body').on('click', '#miscellaneous-pagination .pagination a', function(e) {

        e.preventDefault();

        // $('li').removeClass('active');
        $(this).parent('li').addClass('active');

        var myurl = '{{route('admin.store.miscellaneous',['storeCode'=>$storeCode])}}';
        var page=$(this).attr('href').split('page=')[1];

        localStorage.setItem('activePage', page);
        getPaginationData(page,myurl);

    });
    function getPaginationData(page,url){
        $.ajax(
            {
                url: url + '?page=' + page,
                type: "get",
                datatype: "html"
            }).done(function(response){
            $('#general-content').html("");
            $('#general-content').append(response.html);

        }).fail(function(error){
            displayErrorMessage(error);
        });
    }
</script>
