<script>
    $(document).ready(function() {

        $('#province_code').change(function() {
            var selectedLocationCode = $('#province_code option:selected').val();
            var district = "{{ isset($filterParameters) ? $filterParameters['district_code'] : '' }}";
            $('#district_code').empty();
            $('#municipality_code').empty();
            $('#ward_code').empty();
            if (selectedLocationCode) {

                $.ajax({
                    type: 'GET',
                    url: "{{ url('/api/location-hierarchies') }}" + '/' + selectedLocationCode +
                        '/lower-locations',
                }).done(function(response) {
                    $('#district_code').append('<option value="" selected >--Select An Option--</option>');
                    response.data.forEach(function(locationHierarchy) {
                        $('#district_code').append('<option ' + ((locationHierarchy.location_code ===
                                district) ?
                                "selected" : '') + ' value="' + locationHierarchy.location_code + '">' +
                            locationHierarchy.location_name + '</option>');
                    });
                    temporaryDistrictChange();

                });

            }
        }).trigger('change');
    });

    function districtChange() {
        var selectedLocationCode = $('#district_code option:selected').val();
        var municipality = "{{ isset($filterParameters) ? $filterParameters['municipality_code'] : '' }}";
        if (selectedLocationCode) {
            $('#municipality_code').empty();
            $('#ward_code').empty();
            //  $('#tole_street').empty();
            if (selectedLocationCode) {
                $.ajax({
                    type: 'GET',
                    url: "{{ url('/api/location-hierarchies') }}" + '/' + selectedLocationCode +
                        '/lower-locations',
                }).done(function(response) {
                    $('#municipality_code').append('<option value="" selected >--Select An Option--</option>');
                    response.data.forEach(function(locationHierarchy) {
                        $('#municipality_code').append('<option ' + ((locationHierarchy.location_code ===
                                municipality) ?
                                "selected" : '') + ' value="' + locationHierarchy.location_code + '">' +
                            locationHierarchy.location_name + '</option>');
                    });
                    temporaryMunicipalityChange();
                });

            }
        }
    }
    function municipalityChange() {
        var selectedLocationCode = $('#municipality_code option:selected').val();
        var ward = "{{ isset($filterParameters) ? $filterParameters['ward_code'] : '' }}";
        if (selectedLocationCode) {
            $('#ward_code').empty();
            // $('#tole_street').empty();
            $.ajax({
                type: 'GET',
                url: "{{ url('/api/location-hierarchies') }}" + '/' + selectedLocationCode +
                    '/lower-locations',
            }).done(function(response) {
                $('#ward_code').append('<option value="" selected >--Select An Option--</option>');
                response.data.forEach(function(locationHierarchy) {
                    $('#ward_code').append('<option ' + ((locationHierarchy.location_code ===
                            ward) ?
                            "selected" : '') + ' value="' + locationHierarchy.location_code + '">' +
                        locationHierarchy.location_name + '</option>');
                });
            });
        }
    }


</script>
