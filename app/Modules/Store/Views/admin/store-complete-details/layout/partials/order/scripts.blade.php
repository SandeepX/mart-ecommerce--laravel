<script>
    //order details model popup
    $('body').on('click', '#order_view_btn', function () {
        var orderCode = document.getElementById("order_view_btn").value;
        $.ajax({
            // url: '/admin/stores/' + orderCode + '/order/details',
            url: $(this).data('url'),
            type: "GET",
            success: function (response) {
                $('.order-detail-modal-content').html("");
                $('.order-detail-modal-content').append(response.html);
            },
            error: function (error) {
                displayErrorMessage(error);
            }
        });
    });

    //order filter
    $('body').on('click', '#order-filter-btn', function (){
        var frm = $('#order_filter_form');
        frm.submit(function (e) {
            e.preventDefault();
            $.ajax({
                type: frm.attr('method'),
                url: frm.attr('action'),
                data: frm.serialize(),
                success: function (response) {
                    $('#store_detail').html("");
                    $('#store_detail').append(response.html);
                },
                error: function (error) {
                    displayErrorMessage(error);
                },
            });
        });
    });

    // order table pagination
    $('body').on('click', '#order-pagination .pagination a', function(e) {

        e.preventDefault();

        // $('li').removeClass('active');
        $(this).parent('li').addClass('active');

        var myurl = '{{route('admin.store.order',['storeCode'=>$storeCode])}}';
        var page = $(this).attr('href').split('page=')[1];

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
