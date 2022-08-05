<script>
    $(document).ready(function() {

        $('#temporary_province').change(function() {
            var selectedLocationCode = $('#temporary_province option:selected').val();
            var district = "{{ isset($filterParameters) ? $filterParameters['temporary_district'] : '' }}";
            $('#temporary_district').empty();
            $('#temporary_municipality').empty();
            $('#temporary_ward').empty();
            // $('#tole_street').empty();
            if (selectedLocationCode) {

                $.ajax({
                    type: 'GET',
                    url: "{{ url('/api/location-hierarchies') }}" + '/' + selectedLocationCode +
                        '/lower-locations',
                }).done(function(response) {
                    $('#temporary_district').append('<option value="" selected >--Select An Option--</option>');
                    response.data.forEach(function(locationHierarchy) {
                        $('#temporary_district').append('<option ' + ((locationHierarchy.location_code ===
                            district) ?
                            "selected" : '') + ' value="' + locationHierarchy.location_code + '">' +
                            locationHierarchy.location_name + '</option>');
                    });
                    temporaryDistrictChange();

                });

            }
        }).trigger('change');
    });

    function temporaryDistrictChange() {
        var selectedLocationCode = $('#temporary_district option:selected').val();
        var municipality = "{{ isset($filterParameters) ? $filterParameters['temporary_municipality'] : '' }}";
        if (selectedLocationCode) {
            $('#temporary_municipality').empty();
            $('#temporary_ward').empty();
            //  $('#tole_street').empty();
            if (selectedLocationCode) {
                $.ajax({
                    type: 'GET',
                    url: "{{ url('/api/location-hierarchies') }}" + '/' + selectedLocationCode +
                        '/lower-locations',
                }).done(function(response) {
                    $('#temporary_municipality').append('<option value="" selected >--Select An Option--</option>');
                    response.data.forEach(function(locationHierarchy) {
                        $('#temporary_municipality').append('<option ' + ((locationHierarchy.location_code ===
                            municipality) ?
                            "selected" : '') + ' value="' + locationHierarchy.location_code + '">' +
                            locationHierarchy.location_name + '</option>');
                    });
                    temporaryMunicipalityChange();
                });

            }
        }
    }
    function temporaryMunicipalityChange() {
        var selectedLocationCode = $('#temporary_municipality option:selected').val();
        var ward = "{{ isset($filterParameters) ? $filterParameters['temporary_ward'] : '' }}";
        if (selectedLocationCode) {
            $('#temporary_ward').empty();
            // $('#tole_street').empty();
            $.ajax({
                type: 'GET',
                url: "{{ url('/api/location-hierarchies') }}" + '/' + selectedLocationCode +
                    '/lower-locations',
            }).done(function(response) {
                $('#temporary_ward').append('<option value="" selected >--Select An Option--</option>');
                response.data.forEach(function(locationHierarchy) {
                    $('#temporary_ward').append('<option ' + ((locationHierarchy.location_code ===
                        ward) ?
                        "selected" : '') + ' value="' + locationHierarchy.location_code + '">' +
                        locationHierarchy.location_name + '</option>');
                });
            });
        }
    }


</script>
