<script>
    $(document).ready(function($) {

        $('#province').change(function() {
            var selectedLocationCode = $('#province option:selected').val();
            var district = "{{ isset($locationPath) ? $locationPath['district']->location_code : '' }}";
            $('#district').empty();
            $('#municipality').empty();
            $('#ward').empty();
            // $('#tole_street').empty();
            if (selectedLocationCode) {

                $.ajax({
                    type: 'GET',
                    url: "{{ url('/api/location-hierarchies') }}" + '/' + selectedLocationCode +
                        '/lower-locations',
                }).done(function(response) {
                    $('#district').append('<option value="" selected >--Select An Option--</option>');
                    response.data.forEach(function(locationHierarchy) {
                        $('#district').append('<option ' + ((locationHierarchy.location_code ===
                            district) ?
                            "selected" : '') + ' value="' + locationHierarchy.location_code + '">' +
                            locationHierarchy.location_name + '</option>');
                    });
                    districtChange();

                });

            }
        }).trigger('change');
    });

    function districtChange() {
        var selectedLocationCode = $('#district option:selected').val();
        var municipality = "{{ isset($locationPath) ? $locationPath['municipality']->location_code : '' }}";
        if (selectedLocationCode) {
            $('#municipality').empty();
            $('#ward').empty();
            //  $('#tole_street').empty();
            if (selectedLocationCode) {
                $.ajax({
                    type: 'GET',
                    url: "{{ url('/api/location-hierarchies') }}" + '/' + selectedLocationCode +
                        '/lower-locations',
                }).done(function(response) {
                    $('#municipality').append('<option value="" selected >--Select An Option--</option>');
                    response.data.forEach(function(locationHierarchy) {
                        $('#municipality').append('<option ' + ((locationHierarchy.location_code ===
                            municipality) ?
                            "selected" : '') + ' value="' + locationHierarchy.location_code + '">' +
                            locationHierarchy.location_name + '</option>');
                    });
                    municipalityChange();
                });

            }
        }
    }

    function municipalityChange() {
        var selectedLocationCode = $('#municipality option:selected').val();
        var ward = "{{ isset($locationPath) ? $locationPath['ward']->location_code : '' }}";
        if (selectedLocationCode) {
            $('#ward').empty();
            // $('#tole_street').empty();
            $.ajax({
                type: 'GET',
                url: "{{ url('/api/location-hierarchies') }}" + '/' + selectedLocationCode +
                    '/lower-locations',
            }).done(function(response) {
                $('#ward').append('<option value="" selected >--Select An Option--</option>');
                response.data.forEach(function(locationHierarchy) {
                    $('#ward').append('<option ' + ((locationHierarchy.location_code ===
                        ward) ?
                        "selected" : '') + ' value="' + locationHierarchy.location_code + '">' +
                        locationHierarchy.location_name + '</option>');
                });
                wardChange();
            });
        }
    }

</script>
