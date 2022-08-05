<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBkShwqbN4_vK84kDHYqGU1PC4Cm9M-zgM&libraries=places&callback=initializeMap"
        async defer></script>
<script>

    function initializeMap() {


                {{--  var latitudeValue="{{$individualKyc['latitude']}}";
                 var longitudeValue="{{$individualKyc['longitude']}}";--}}

        var latitudeValue = document.getElementById('latitude').value;
        var longitudeValue = document.getElementById('longitude').value;

        // The location of nepal
        var defaultLocation = {lat: 28.3949, lng: 84.1240};

        if (latitudeValue && longitudeValue) {
            defaultLocation = {lat: parseFloat(latitudeValue), lng: parseFloat(longitudeValue)};
        }

// var location = {lat: parseFloat(latitudeValue), lng: parseFloat(longitudeValue)};

        var mapOptions = {
            zoom: 7,
            scrollwheel: true,
            center: defaultLocation,
        };

        var map = new google.maps.Map(document.getElementById('map-location'), mapOptions);


        geocoder = new google.maps.Geocoder;
        infowindow = new google.maps.InfoWindow;

        // The marker, positioned at nepal
        var cmarker = new google.maps.Marker({
            position: defaultLocation,
            map: map,
            draggable: true,
            animation: google.maps.Animation.DROP,
        });

        map.setCenter(cmarker.getPosition());
        map.setZoom(16);

    }

</script>