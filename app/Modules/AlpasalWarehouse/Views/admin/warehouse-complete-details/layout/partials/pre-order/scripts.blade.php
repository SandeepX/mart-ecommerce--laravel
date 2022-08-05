<script>
    // order table pagination
    $('body').on('click', '#warehouse-preorder-pagination .pagination a', function(e) {

        e.preventDefault();

        // $('li').removeClass('active');
        $(this).parent('li').addClass('active');

        var myurl = '{{route('admin.warehouse.preorder',['warehouseCode'=>$warehouseCode])}}';
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
