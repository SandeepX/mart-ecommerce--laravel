<script>
    //withdraw details model popup
    $('body').on('click', '#withdraw_view_btn', function () {
        $.ajax({
            url: $(this).data('url'),
            type: "GET",
            success: function (response) {
                $('.withdraw-detail-modal-content').html("");
                $('.withdraw-detail-modal-content').append(response.html);
            },
            error: function (error) {
                displayErrorMessage(error);
            }
        });
    });

    // withdraw request pagination
    $('body').on('click', '#withdraw-pagination .pagination a', function(e) {
        e.preventDefault();

        // $('li').removeClass('active');
        $(this).parent('li').addClass('active');

        var myurl = '{{route('admin.store.balance.withdraw',['storeCode'=>$storeCode])}}';

        var page=$(this).attr('href').split('page=')[1];
        localStorage.setItem('activePage', page);
        getPaginationDataForBalanceManagementTab(page,myurl);
    });

    function getPaginationDataForBalanceManagementTab(page,url)
    {
        $.ajax(
            {
                url: url + '?page=' + page,
                type: "get",
                datatype: "html"
            }).done(function(response){

            $('#balanceManagementTable').html("");

            $('#balanceManagementTable').append(response.html);

        }).fail(function(error){
            displayErrorMessage(error);
        });
    }

    //balance transaction filter
    $('body').on('click', '#balance_transaction_filter_btn', function (){
        var frm = $('#balance_transaction_filter_form');

        frm.submit(function (e) {

            e.preventDefault();

            $.ajax({
                type: frm.attr('method'),
                url: frm.attr('action'),
                data: frm.serialize(),
                success: function (response) {
                    $('#balanceManagementTable').html("");
                    $('#balanceManagementTable').append(response.html);
                },
                error: function (response) {
                    displayErrorMessage(error);
                },
            });
        });
    });

    // balance-transaction pagination
    $('body').on('click', '#balance-transaction-pagination .pagination a', function(e) {

        e.preventDefault();

        // $('li').removeClass('active');
        $(this).parent('li').addClass('active');

        var myurl = '{{route('admin.store.balance.transaction',['storeCode'=>$storeCode])}}';
        var page = $(this).attr('href').split('page=')[1];
        localStorage.setItem('activePage', page);
        getPaginationDataForBalanceManagementTab(page,myurl);
    });
</script>
