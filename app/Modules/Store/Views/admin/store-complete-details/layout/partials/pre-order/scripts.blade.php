<script>

    //order filter
    $('body').on('click', '#pre-order-filter-btn', function (){
        var frm = $('#pre_order_filter_form');

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

    // order details model popup
    $('body').on('click', '#preorder_view_btn', function () {
        var preorderCode = document.getElementById("preorder_view_btn").value;

        $.ajax({
            url: '/admin/stores/preorder/'+ preorderCode + '/details',
            type: "GET",
            success: function (response) {
                $('.preorder-detail-modal-content').html("");
                $('.preorder-detail-modal-content').append(response.html);
            },
            error: function (error) {
                console.log(error);
                // displayErrorMessage(error);
            }
        });
    });

    // preorder table pagination
    $('body').on('click', '#preorder-pagination .pagination a', function(e) {

        e.preventDefault();

        // $('li').removeClass('active');
        $(this).parent('li').addClass('active');

        var myurl = '{{route('admin.store.pre-order',['storeCode'=>$storeCode])}}';
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


    $(document).ready(function () {
        $('.summernote').summernote({
            height: 150, // set editor height
            minHeight: null, // set minimum height of editor
            maxHeight: null, // set maximum height of editor
            focus: false // set focus to editable area after initializing summernote
        });

        $('#order_status_submit').on('click',function (e){
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure',
                text: "Once status changed, you will not be able to perform any action on this pre-order!",
                showCancelButton: true,
                customClass: 'swal-wide',
                cancelButtonColor: '#d33',
                confirmButtonText: `Save`,
                width: '800px',
                position:'center',
                heightAuto: false,
                padding:'10em'
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    // $(this).submit();
                    $('#status-form').submit();
                }
            });
        });
    });

</script>
