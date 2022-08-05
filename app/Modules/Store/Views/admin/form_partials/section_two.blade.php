<div class="row">

    <div class="col-md-3 col-lg-4">
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

    <div class="col-md-3 col-lg-4">
        <div class="form-group">
            <label class="control-label">District  *</label>
            <select required class="form-control" id="district" onchange="districtChange()">
                <option selected value="" >--Select An Option--</option>
            </select>
        </div>
    </div>


    <div class="col-md-3 col-lg-4">
        <div class="form-group">
            <label class="control-label">Municipality  *</label>
            <select required class="form-control" id="municipality" onchange="municipalityChange()">
                <option selected value="" >--Select An Option--</option>
            </select>
        </div>
    </div>


    <div class="col-md-4 col-lg-4">
        <div class="form-group">
            <label class="control-label">Ward  *</label>
            <select required class="form-control" id="ward"  name="store_location_code">
                <option selected value="" >--Select An Option--</option>
            </select>
        </div>
    </div>



    <div class="col-md-4 col-lg-4">
        <div class="form-group">
            <label class="control-label">
                Landmark
            </label>
            <input required id="landmark" class="form-control" value="{{isset($store) ? $store->store_landmark_name : old('store_landmark_name') }}" name="store_landmark_name" />
            <div  style="color:green;margin-top: 5px;">
                <input id ="landmark_lat" type="hidden" name="latitude" value="{{isset($store) ? $store->latitude : old('latitude') }}">
                <input id ="landmark_long" type="hidden" name="longitude" value="{{isset($store) ? $store->longitude : old('longitude') }}" >
            </div>

        </div>
    </div>

</div>