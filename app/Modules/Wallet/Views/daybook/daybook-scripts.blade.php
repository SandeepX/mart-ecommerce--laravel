<script>

    function daybookfilterParams(){
        let params = {
            transaction_date_from: $('#transaction_date_from').val(),
            transaction_date_to: $('#transaction_date_to').val(),
            transaction_flow: $('#transaction_flow').val(),
            transaction_type: $('#transaction_type').val(),
            include_exclude: $('#include_exclude').val(),
            store_code: $('#store_code').val(),
            page : 1
        }
        return params;
    }

    function loadMainContent(url) {
        $('#daybookTable').html('Loading Results ....')
        $.ajax({
            url: url,
            type: "GET",
            dataType: "json"
        }).done(function (response) {
            $('#daybookTable').html("");
            $('#daybookTable').append(response.html);
        }).fail(function (error) {
            $(".showFlashMessage").css('display','block');
            $(".showFlashMessage").append(error.responseJSON.message);

        });
    }

    function getPaginationData(params,url){
        $('#daybookTable').html('Loading Results ....')
        $.ajax(
            {
                url: url + '?page=' + params['page'] ,
                type: "get",
                datatype: "html",
                data: params
            }).done(function(response){
            $('#daybookTable').html("");
            $('#daybookTable').append(response.html);
        }).fail(function(error){
            $(".showFlashMessage").css('display','block');
            $(".showFlashMessage").append(error.responseJSON.message);
        });
    }


    $(document).ready(function(){

        var url = '{{route('admin.daybook.index')}}';
        loadMainContent(url);

        $('#transaction_flow').change(function(e){
            e.preventDefault();
            var flow = $(this).val();
            var filterTransactionType = $('#transaction_type').val();
            $('#transaction_type').empty();
            $.ajax({
                type: 'GET',
                url: "{{ route('admin.daybook.transaction-purpose') }}",
                data : {
                    transaction_flow : flow ,
                    transaction_type:filterTransactionType,
                    _token: '{{ csrf_token() }}'
                }
            }).done(function(data) {
                $('#transaction_type').append(data.data);
            });

        }).trigger('change');

        $('body').on('click', '#daybook-pagination .pagination a', function(e) {
            e.preventDefault();
            var myurl = $(this).attr('href');
            var page_value = $(this).attr('href').split('page=')[1];
            var filtered_params = daybookfilterParams();
            filtered_params.page = page_value;
            getPaginationData(filtered_params,myurl);
        });

        $('body').on('click', '#daybook-filter', function (){
            $('#daybookTable').html('Loading Results ....')
            var frm = $('#daybook_filter');
            $.ajax({
                type: frm.attr('method'),
                url: frm.attr('action'),
                data: frm.serialize(),
                success: function (response) {
                    $('#daybookTable').html("");
                    $('#daybookTable').append(response.html);
                },
                error: function (error) {
                    $(".showFlashMessage").css('display','block');
                    $(".showFlashMessage").append(error.responseJSON.message);
                },
            });
        });

        $('body').on('click', '#extra_remark_view_btn', function () {
            var kycCode = document.getElementById("extra_remark_view_btn").value;
            $.ajax({
                url: $(this).data('url'),
                type: "GET",
                success: function (response) {
                    $('.extra-remark-modal-content').html("");
                    $('.extra-remark-modal-content').append(response.html);
                },
                error: function (error) {
                    console.log(error);
                    $(".showFlashMessage").css('display','block');
                    $(".showFlashMessage").append(error.responseJSON.message);
                }
            });
        });
    });



    setTimeout(function() {
        $('#showFlashMessage').slideUp('slow');
    }, 8000);




</script>
