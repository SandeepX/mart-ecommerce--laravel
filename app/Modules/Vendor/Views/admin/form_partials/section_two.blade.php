<div class="row">


    <div class="col-md-3 col-lg-4">
        <div class="form-group">
            <label class="control-label">Province  *</label>
        
            <select class="form-control" id="province"  >
                <option selected value="" >--Select An Option--</option>
                @if(isset($provinces) && count($provinces)>0)
                   
                    @foreach ($provinces as $province)
                        <option value={{ $province->location_code }} {{ isset($locationPath ) ? $locationPath['province']->location_code == $province->location_code ? 'selected' : '' : '' }}>{{ $province->location_name }}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>

    <div class="col-md-3 col-lg-4">
        <div class="form-group">
            <label class="control-label">District  *</label>
            <select class="form-control" id="district" onchange="districtChange()">
                <option selected value="" >--Select An Option--</option>
            </select>
        </div>
    </div>


    <div class="col-md-3 col-lg-4">
        <div class="form-group">
            <label class="control-label">Municipality  *</label>
            <select class="form-control" id="municipality" onchange="municipalityChange()">
                <option selected value="" >--Select An Option--</option>
            </select>
        </div>
    </div>


    <div class="col-md-4 col-lg-4">
        <div class="form-group">
            <label class="control-label">Ward  *</label>
            <select class="form-control" id="ward"  name="vendor_location_code" >
                <option selected value="" >--Select An Option--</option>
            </select>
        </div>
    </div>


    <!-- <div class="col-md-4 col-lg-4">
        <div class="form-group">
            <label class="control-label">Tole/Street *</label>
            <select class="form-control" id="tole_street"  name="vendor_location_code" >
                <option selected value="" >--Select An Option--</option>
            </select>
        </div>
    </div> -->

    <div class="col-md-4 col-lg-4">
        <div class="form-group">
            <label class="control-label">
                Landmark
            </label>
            <input id="landmark" class="form-control" value="{{isset($vendor) ? $vendor->vendor_landmark : old('vendor_landmark') }}" name="vendor_landmark" />
            <div  style="color:green;margin-top: 5px;">
                <input id ="landmark_lat" type="hidden" name="landmark_latitude" value="{{isset($vendor) ? $vendor->landmark_latitude : old('landmark_latitude') }}">
                <input id ="landmark_long" type="hidden" name="landmark_longitude" value="{{isset($vendor) ? $vendor->landmark_longitude : old('landmark_longitude') }}" >
            </div>

        </div>
    </div>


</div>