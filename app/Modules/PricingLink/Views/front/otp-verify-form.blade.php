<!DOCTYPE html>
<html>
<head>
    <title>Product Pricing View Form</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
        <div>
            @include('PricingLink::partial.message')
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <form action="{{route('product-pricing.verifyOTPWithoutAuth')}}" method="POST">
                @csrf
                <input type="hidden" class="form-control" value="{{$linkCode}}"
                       name="link_code"  autocomplete="off">
                <input type="hidden" class="form-control" value="{{$mobileNumber}}"
                       name="mobile_number"  autocomplete="off">
                <div class="form-group col-md-8">
                    <label class="control-label">OTP Code<span class="text-red">*</span></label>
                    <div>
                        <input type="text" class="form-control" value="{{old('otp_code')  }}"
                               name="otp_code" required autocomplete="off">
                        @error('otp_code')
                        <small class="text-red">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <button type="submit" class="btn btn-success btn-xs">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Popper JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
{{--@includeIf('PricingLink::front.location-script')--}}

</body>
</html>
