<!DOCTYPE html>
<html>
<head>
    <title>Product Pricing</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap-theme.min.css" integrity="sha384-6pzBo3FDv/PJ8r2KRkGHifhEocL+1X2rVCTTkUfGk7/0pbek5mMa1upzvWbrUbOZ" crossorigin="anonymous">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

</head>
<style>
    td, th {
        margin: 0 !important;
        padding: 0 !important;
        font-size: 12px !important;
    }
    td {
        font-weight: 400;
    }



</style>
<body>
<section class="container">
    <div class="row">
{{--        <div class="col-md-12">--}}
{{--            @include('PricingLink::partial.message')--}}
{{--        </div>--}}
        <div class=" col-md-12 alert alert-danger showFlashMessage" style="display:none">

        </div>


        <div class='col-md-12'>
            @include('PricingLink::front.filter-form')
        </div>
    </div>
    <hr>

    <div id ="productDetailListing">
        @include('PricingLink::front.pricing-link-table')
    </div>

</section>

</body>
</html>
<!-- Popper JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
{{--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>--}}

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>

@include('PricingLink::partial.scripts')

<script>

    $('#reset-product-field').on('click',function (e) {
        e.preventDefault();
        $("#product").select2("val", "");
    });

    window.onload = function (e) {
        e.preventDefault();
        $("#product").select2("val", "");
    };

    $(".product-name").select2();
</script>






