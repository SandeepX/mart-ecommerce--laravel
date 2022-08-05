

<script>
    $(document).ready(function(){

        function pricingLinkFilterParams(){
            let params = {
                product: $('#product').val(),
            }
            return params;
        }

        function getPaginationData(params,url){
            $('#productDetailListing').html('Loading Results ....')
            $.ajax(
                {
                    url: url + '?page=' + params['page'] ,
                    type: "get",
                    datatype: "html",
                    data: params
                }).done(function(response){
                $('#productDetailListing').html("");
                $('#productDetailListing').append(response.html);
            }).fail(function(error){
                location.reload();
                $(".showFlashMessage").css('display','block');
                $(".showFlashMessage").append(error.responseJSON.message);
            });
        }



        $('.pricing-product-link-filter').click(function (){
            var frm = $('#pricing_form_filter');
            $('#productDetailListing').html('Loading Results ....')
            $.ajax({
                type: frm.attr('method'),
                url: frm.attr('action'),
                data: frm.serialize(),
                success: function (response) {
                    //console.log(response);
                    $('#productDetailListing').html("");
                    $('#productDetailListing').append(response.html);
                },
                error: function (error) {
                    location.reload();
                    $(".showFlashMessage").css('display','block');
                    $(".showFlashMessage").append(error.responseJSON.message);
                },
            });
        });


        $('body').on('click', ' .pagination a', function(e) {
            e.preventDefault();
            var myurl = '{{route('product-pricing.index',['linkCode'=>$link])}}';
            var page_value = $(this).attr('href').split('page=')[1];
            var filtered_params = pricingLinkFilterParams();
            filtered_params.page = page_value;
            getPaginationData(filtered_params,myurl);
        });

        setTimeout(function() {
            $('.showFlashMessage').slideUp('slow');
        }, 8000);
    });


</script>

