
<script>
    function initialize() {
        const locations = {!! json_encode($mapLocations) !!};

        var mapProp= {
            center:new google.maps.LatLng(locations[0].lat,locations[0].long),
            zoom:20,
        };
        var map = new google.maps.Map(document.getElementById("mapCanvas"),mapProp);

        const infoWindow = new google.maps.InfoWindow({
            content: "",
            disableAutoPan: true,
        });

        for(i=0 ; i<locations.length;i++){
            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(locations[i].lat,locations[i].long),
                map: map,
            });
            google.maps.event.addListener(marker, 'mouseover', (function(marker, i) {
                return function() {
                    infoWindow.setContent(locations[i].content);
                    infoWindow.open(map, marker);
                }
            })(marker, i));
        }
    }
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBkShwqbN4_vK84kDHYqGU1PC4Cm9M-zgM&&callback=initialize"
        async defer></script>
