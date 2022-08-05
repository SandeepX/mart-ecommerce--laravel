<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBkShwqbN4_vK84kDHYqGU1PC4Cm9M-zgM&libraries=places"
        async defer></script>

<script>

    $(document).ready(function () {

        var landMarkInput = $('#landmark');

        landMarkInput.on('focus',function () {
            initialize('landmark');//initializing map
        });



    });

    function initialize(inputID) {
        var input = document.getElementById(inputID);
        var options = {
            componentRestrictions: {country: "np"}
        };
        var autocomplete = new google.maps.places.Autocomplete(input,options)
        autocomplete.addListener('place_changed', function() {
            var place = autocomplete.getPlace();
           // console.log(place)
            $('#landmark_lat').empty().val(place.geometry['location'].lat());
            $('#landmark_long').empty().val( place.geometry['location'].lng());

            // --------- show lat and long ---------------
//            $("#lat_area").removeClass("d-none");
//            $("#long_area").removeClass("d-none");
        });
    }
</script>
