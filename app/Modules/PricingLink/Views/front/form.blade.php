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
{{--    <div class="row">--}}
{{--        <div>--}}
{{--            @include('PricingLink::partial.message')--}}
{{--        </div>--}}
{{--    </div>--}}
    <div class="row">
        <div class="col-md-12">
            <form action="{{route('product-pricing-form.store')}}" method="POST">
                @csrf
            <input type="hidden" class="form-control" value="{{isset($pricingLink) ? $pricingLink->pricing_master_code : old('pricing_master_code')  }}"
                   name="pricing_master_code" required autocomplete="off">
            <div class="form-group col-md-8">
                <label class="control-label">Full Name<span class="text-red">*</span></label>
                <div>
                    <input type="text" class="form-control" value="{{old('full_name')  }}"
                           name="full_name" required autocomplete="off">
                    @error('full_name')
                    <small class="text-red">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="form-group col-md-8">
                <label class="control-label">Mobile Number<span class="text-red">*</span></label>
                <div class='input-group'>
                    <input type='text' class="form-control"
                           value="{{old('mobile_number')}}"
                           name="mobile_number"/>
                </div>

            </div>

            <div class="col-md-8">
                <div class="form-group">
                    <label class="control-label">Province  *</label>
                    <select required class="form-control" id="province"  >
                        <option selected value="" >--Select An Option--</option>
                        @if(isset($provinces) && count($provinces)>0)
                            @foreach ($provinces as $province)
                                <option value={{ $province->location_code }} {{ isset($locationPath ) ? $locationPath['province']->location_code == $province->location_code ? 'selected' : '' : '' }}>{{ $province->location_name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>

            <div class="col-md-8">
                <div class="form-group">
                    <label class="control-label">District  *</label>
                    <select required class="form-control" id="district" onchange="districtChange()">
                        <option selected value="" >--Select An Option--</option>
                    </select>
                </div>
            </div>

            <div class="col-md-8">
                <div class="form-group">
                    <label class="control-label">Municipality  *</label>
                    <select required class="form-control" id="municipality" onchange="municipalityChange()">
                        <option selected value="" >--Select An Option--</option>
                    </select>
                </div>
            </div>

            <div class="col-md-8">
                <div class="form-group">
                    <label class="control-label">Ward  *</label>
                    <select required class="form-control" id="ward"  name="location_code">
                        <option selected value="" >--Select An Option--</option>
                    </select>
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
@includeIf('PricingLink::front.location-script')

</body>
</html>
