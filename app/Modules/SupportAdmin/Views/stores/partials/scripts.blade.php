

<script>
    $('document').ready(function(){

        $('.back').click(function(e){
           var url = $(this).attr('href');
            window.location.href = url;
        });

        function loadMainContent(url) {
            $('#store_detail').html('Loading Results ....')
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json"
            }).done(function (response) {
                $('#store_detail').html("");
                $('#store_detail').append(response.html);
            }).fail(function (error) {
                $(".showFlashMessage").css('display','block');
                $(".showFlashMessage").append(error.responseJSON.message);

            });
        }

        function getPaginationData(params,url)
        {
            // console.log(params['page']);
            $('#store_detail').html('Loading Results ....')
            $.ajax(
                {
                    url: url + '?page=' + params['page'] ,
                    type: "get",
                    datatype: "html",
                    data: params
                }).done(function(response){
                $('#store_detail').html("");
                $('#store_detail').append(response.html);
            }).fail(function(error){
                $(".showFlashMessage").css('display','block');
                $(".showFlashMessage").append(error.responseJSON.message);
            });
        }

        setTimeout(function() {
            $('.showFlashMessage').slideUp('slow');
        }, 8000);





        //store kyc scripts

        $('.store_individual_kyc').click(function(e){
            e.preventDefault();
            var url = $(".store_individual_kyc").attr('href');
            loadMainContent(url)
        })

        $('body').on('click', '#kyc_individual_btn', function () {
            var kycCode = document.getElementById("kyc_individual_btn").value;
            $.ajax({
                url: $(this).data('url'),
                type: "GET",
                success: function (response) {
                    $('.kyc-individual-detail-modal-content').html("");
                    $('.kyc-individual-detail-modal-content').append(response.html);
                },
                error: function (error) {
                    console.log(error);
                    $(".showFlashMessage").css('display','block');
                    $(".showFlashMessage").append(error.responseJSON.message);
                }
            });
        });

        $('.store_firm_kyc').click(function(e){
            e.preventDefault();
            var url = $(".store_firm_kyc").attr('href');
            //alert(url);
            loadMainContent(url)
        })

        $('body').on('click', '#kyc_firm_btn', function () {
            var kycCode = document.getElementById("kyc_firm_btn").value;
            $.ajax({
                url: $(this).data('url'),
                type: "GET",
                success: function (response) {
                    $('.kyc-firm-detail-modal-content').html("");
                    $('.kyc-firm-detail-modal-content').append(response.html);
                },
                error: function (error) {
                    console.log(error);
                    $(".showFlashMessage").css('display','block');
                    $(".showFlashMessage").append(error.responseJSON.message);
                }
            });
        });


        //store withdraw scripts

        $('.store_withdraw').click(function(e){
            e.preventDefault();
            var url = $(".store_withdraw").attr('href');
            loadMainContent(url)
        });

        $('body').on('click', '#store_all_withdraw_request', function () {
            var withdrawListUrl = $(this).data('url');
            loadMainContent(withdrawListUrl)
        });

        $('body').on('click', '#withdraw_view_btn', function () {
            $.ajax({
                url: $(this).data('url'),
                type: "GET",
                success: function (response) {
                    $('.withdraw-detail-modal-content').html("");
                    $('.withdraw-detail-modal-content').append(response.html);
                },
                error: function (error) {
                    console.log(error);
                    $(".showFlashMessage").css('display','block');
                    $(".showFlashMessage").append(error.responseJSON.message);
                }
            });
        });

        //store_payment scripts

        $('.store_payment').click(function(e){
            e.preventDefault();
            var url = $(".store_payment").attr('href');
            loadMainContent(url)
        });

        $('body').on('click', '#store_payment_list', function () {
            var storePaymentListUrl = $(this).data('url');
            loadMainContent(storePaymentListUrl)
        });

        function storePaymentfilterParams(){
            let params = {
                payment_code: $('#payment_code').val(),
                payment_type: $('#payment_type').val(),
                amount_condition: $('#amount_condition').val(),
                payment_date_from: $('#payment_date_from').val(),
                payment_date_to: $('#payment_date_to').val(),
                payment_status: $('#payment_status').val()
            }
            return params;
        }

        $('body').on('click', '#payment-pagination .pagination a', function(e) {
            e.preventDefault();
            var myurl = $(this).attr('href');
            var page_value = $(this).attr('href').split('page=')[1];
            var filtered_params = storePaymentfilterParams();
            filtered_params.page = page_value;
            getPaginationDataForPayment(filtered_params,myurl);
        });

        function getPaginationDataForPayment(params,url)
        {
            $('#store_detail').html('Loading Results ....')
            $.ajax(
                {
                    url: url,
                    type: "get",
                    datatype: "html",
                    data: params
                }).done(function(response){
                $('#store_detail').html("");
                $('#store_detail').append(response.html);
            }).fail(function(error){
                $(".showFlashMessage").css('display','block');
                $(".showFlashMessage").append(error.responseJSON.message);
            });
        }

        $('body').on('click', '#store-payment-filter-btn', function (){
            var frm = $('#payment_filter_form');
            $.ajax({
                type: frm.attr('method'),
                url: frm.attr('action'),
                data: frm.serialize(),
                success: function (response) {
                    $('#store_detail').html("");
                    $('#store_detail').append(response.html);
                },
                error: function (error) {
                    $(".showFlashMessage").css('display','block');
                    $(".showFlashMessage").append(error.responseJSON.message);
                },
            });
        });

        $('body').on('click', '#store_payment_detail_btn', function () {
            $.ajax({
                url: $(this).data('url'),
                type: "GET",
                success: function (response) {
                    $('.payment-detail-modal-content').html("");
                    $('.payment-detail-modal-content').append(response.html);
                },
                error: function (error) {
                    console.log(error);
                    $(".showFlashMessage").css('display','block');
                    $(".showFlashMessage").append(error.responseJSON.message);
                }
            });
        });

        //transaction statement

        $('.transaction_statement').click(function(e){
            e.preventDefault();
            var url = $(".transaction_statement").attr('href');
            loadMainContent(url)
        });

        $('body').on('click', '#extra_remark_btn', function () {
            $.ajax({
                url: $(this).data('url'),
                type: "GET",
                success: function (response) {
                    $('.view-extra-remark-modal-content').html("");
                    $('.view-extra-remark-modal-content').append(response.html);
                },
                error: function (error) {
                    console.log(error);
                    $(".showFlashMessage").css('display','block');
                    $(".showFlashMessage").append(error.responseJSON.message);
                }
            });
        });

        function storeTransactionStatementfilterParams(){
            let params = {
                transaction_type: $('#transaction_type').val(),
                transaction_date_from: $('#transaction_date_from').val(),
                transaction_date_to: $('#transaction_date_to').val(),
                wallet_transaction_code: $('#wallet_transaction_code').val(),
                page : 1
            }
            return params;
        }

        $('body').on('click', '#statement-pagination .pagination a', function(e) {
            e.preventDefault();
            var myurl = $(this).attr('href');
            var page_value = $(this).attr('href').split('page=')[1];
            var filtered_params = storeTransactionStatementfilterParams();
            filtered_params.page = page_value;
            getPaginationData(filtered_params,myurl);
        });

        $('body').on('click', '#filter-store-transaction-btn', function (){
            var frm = $('#transaction_statement_filter');
            $.ajax({
                type: frm.attr('method'),
                url: frm.attr('action'),
                data: frm.serialize(),
                success: function (response) {
                    $('#store_detail').html("");
                    $('#store_detail').append(response.html);
                },
                error: function (error) {
                    $(".showFlashMessage").css('display','block');
                    $(".showFlashMessage").append(error.responseJSON.message);
                },
            });
        });


        //investment scripts

        function storeInvestmentfilterParams(){
            let params = {
                maturity_date_from: $('#maturity_date_from').val(),
                maturity_date_to: $('#maturity_date_to').val(),
                status: $('#status').val(),
                is_active: $('#is_active').val(),
                referred_by: $('#referred_by').val(),
                invested_amount: $('#invested_amount').val(),
                interest_rate_condition: $('#interest_rate_condition').val(),
                amount_condition: $('#amount_condition').val(),
                interest_rate: $('#interest_rate').val(),
                investment_plan_name: $('#investment_plan_name').val(),
            }
            return params;
        }


        $('.store_investment').click(function(e){
            e.preventDefault();
            var url = $(".store_investment").attr('href');
            loadMainContent(url)
        });

        $('body').on('click', '#investment_return_view_btn', function () {
            $.ajax({
                url: $(this).data('url'),
                type: "GET",
                success: function (response) {
                    $('#view-investment-return-detail').html("");
                    $('#view-investment-return-detail').append(response.html);
                },
                error: function (error) {
                    console.log(error);
                    $(".showFlashMessage").css('display','block');
                    $(".showFlashMessage").append(error.responseJSON.message);
                }
            });
        });

        $('body').on('click', '#investment-pagination .pagination a', function(e) {
            e.preventDefault();
            var myurl = $(this).attr('href');
            var page_value = $(this).attr('href').split('page=')[1];
            var filtered_params = storeInvestmentfilterParams();
            filtered_params.page = page_value;
            getPaginationData(filtered_params,myurl);
        });

        $('body').on('click', '#investment-filter-btn', function (){
            var frm = $('#investment_plan_form');
            $.ajax({
                type: frm.attr('method'),
                url: frm.attr('action'),
                data: frm.serialize(),
                success: function (response) {
                    $('#store_detail').html("");
                    $('#store_detail').append(response.html);
                },
                error: function (error) {
                    $(".showFlashMessage").css('display','block');
                    $(".showFlashMessage").append(error.responseJSON.message);
                },
            });
        });


        //store all order scripts

        $('.store_order').click(function(e){
            e.preventDefault();
            var url = $(".store_order").attr('href');
            loadMainContent(url)
        })

        function storeOrderfilterParams(){
            let params = {
                order_code: $('#order_code').val(),
                order_status: $('#order_status').val(),
                order_type: $('#order_type').val(),
                price_condition: $('#price_condition').val(),
                payment_status: $('#payment_status').val(),
                order_date_from: $('#order_date_from').val(),
                order_date_to: $('#order_date_to').val(),
                total_price: $('#total_price').val(),
                payable_price_from : $('#payable_price_from').val(),
                payable_price_to : $('#payable_price_to').val(),
                records_per_page: $('#records_per_page').val(),
            }
            return params;
        }

        // store order table pagination

        $('body').on('click', '#order-filter-btn', function (){
            var frm = $('#order_filter_form');
            $.ajax({
                type: frm.attr('method'),
                url: frm.attr('action'),
                data: frm.serialize(),
                success: function (response) {
                    //console.log(response);
                    $('#store_detail').html("");
                    $('#store_detail').append(response.html);
                },
                error: function (error) {
                    $(".showFlashMessage").css('display','block');
                    $(".showFlashMessage").append(error.responseJSON.message);
                },
            });
        });


        $('body').on('click', '#order-pagination .pagination a', function(e) {
            e.preventDefault();
            var myurl = '{{route('support-admin.store-order',['storeCode'=>$storeDetail['store_code']])}}';
            var page_value = $(this).attr('href').split('page=')[1];
            var filtered_params = storeOrderfilterParams();
            filtered_params.page = page_value;
            getPaginationData(filtered_params,myurl);
        });


        $('body').on('click', '#order_view_btn', function () {
            var orderCode = document.getElementById("order_view_btn").value;
            $.ajax({
                url: $(this).data('url'),
                type: "GET",
                success: function (response) {
                    $('.order-detail-modal-content').html("");
                    $('.order-detail-modal-content').append(response.html);
                },
                error: function (error) {
                    $(".showFlashMessage").css('display','block');
                    $(".showFlashMessage").append(error.responseJSON.message);
                }
            });
        });

        $('body').on('click', '#preorder_view_btn', function () {
            var preorderCode = document.getElementById("preorder_view_btn").value;
            $.ajax({
                url: $(this).data('url'),
                type: "GET",
                success: function (response) {
                    $('.order-detail-modal-content').html("");
                    $('.order-detail-modal-content').append(response.html);
                },
                error: function (error) {
                    console.log(error);
                    $(".showFlashMessage").css('display','block');
                    $(".showFlashMessage").append(error.responseJSON.message);
                }
            });
        });


        //store order script

        // $('.store_order').click(function(e){
        //     e.preventDefault();
        //     var url = $(".store_order").attr('href');
        //     loadMainContent(url)
        // })
        //
        // function storeOrderfilterParams(){
        //     let params = {
        //         store_order_code: $('#store_order_code').val(),
        //         payment_status: $('#payment_status').val(),
        //         order_date_from: $('#order_date_from').val(),
        //         price_condition: $('#price_condition').val(),
        //         delivery_status: $('#delivery_status').val(),
        //         total_price: $('#total_price').val(),
        //         records_per_page: $('#records_per_page').val(),
        //         payable_price_from : $('#payable_price_from').val(),
        //         payable_price_to : $('#payable_price_to').val(),
        //     }
        //     return params;
        // }
        //
        // // store order table pagination
        //
        // $('body').on('click', '#order-filter-btn', function (){
        //     var frm = $('#order_filter_form');
        //     $.ajax({
        //         type: frm.attr('method'),
        //         url: frm.attr('action'),
        //         data: frm.serialize(),
        //         success: function (response) {
        //             //console.log(response);
        //             $('#store_detail').html("");
        //             $('#store_detail').append(response.html);
        //         },
        //         error: function (error) {
        //             $(".showFlashMessage").css('display','block');
        //             $(".showFlashMessage").append(error.responseJSON.message);
        //         },
        //     });
        // });


        //store preorder scripts

        // $('.store_preorder').click(function(e){
        //     e.preventDefault();
        //     var url = $(".store_preorder").attr('href');
        //     loadMainContent(url)
        // })
        //
        // function storePreOrderfilterParams(){
        //     let params = {
        //         pre_order_name: $('#pre_order_name').val(),
        //         status: $('#status').val(),
        //         payment_status: $('#payment_status').val(),
        //         page : 1
        //     }
        //     return params;
        // }

        {{--$('body').on('click', '#preorder-pagination .pagination a', function(e) {--}}
        {{--    e.preventDefault();--}}
        {{--    var myurl = '{{route('support-admin.store-preorder',['storeCode'=>$storeDetail['store_code']])}}';--}}
        {{--    var page_value = $(this).attr('href').split('page=')[1];--}}
        {{--    var filtered_params = storePreOrderfilterParams();--}}
        {{--    filtered_params.page = page_value;--}}
        {{--    getPaginationData(filtered_params,myurl);--}}
        {{--});--}}

        // $('body').on('click', '#pre-order-filter-btn', function (){
        //     var frm = $('#pre_order_filter_form');
        //     console.log(frm.serialize());
        //     $.ajax({
        //         type: frm.attr('method'),
        //         url: frm.attr('action'),
        //         data: frm.serialize(),
        //         success: function (response) {
        //             //console.log(response);
        //             $('#store_detail').html("");
        //             $('#store_detail').append(response.html);
        //         },
        //         error: function (error) {
        //             $(".showFlashMessage").css('display','block');
        //             $(".showFlashMessage").append(error.responseJSON.message);
        //         },
        //     });
        // });







    });
</script>
