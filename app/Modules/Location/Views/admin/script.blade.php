<script>

    $('#location_type').change(function() {
        var selectedLocationType = this.value;
        if (selectedLocationType) {
            var districtDiv= $('#district-div');
            var municipalityDiv= $('#municipality-div');
            var wardDiv= $('#ward-div');
            districtDiv.css('display','none');
            municipalityDiv.css('display','none');
            municipalityDiv.css('display','none');

            if(selectedLocationType == 'municipality'){
                districtDiv.css('display','block');
            }

            if(selectedLocationType == 'ward'){
                districtDiv.css('display','block');
                municipalityDiv.css('display','block');
            }
        }

    }).trigger('change');

    $('#province').change(function() {
        var selectedLocationCode = $('#province option:selected').val();
        $('#district').empty();
        $('#municipality').empty();
        $('#ward').empty();
        if (selectedLocationCode) {
            ajaxCall(selectedLocationCode, 'district');
        }

    }).trigger('change');

    $('#district').change(function() {
        var selectedLocationCode = $('#district option:selected').val();
        $('#municipality').empty();
        $('#ward').empty();
        if (selectedLocationCode) {
            ajaxCall(selectedLocationCode, 'municipality');
        }
    });

    /*$('#municipality').change(function() {
        var selectedLocationCode = $('#municipality option:selected').val();
        $('#ward').empty();
        if (selectedLocationCode) {
            ajaxCall(selectedLocationCode, 'ward');
        }
    });*/

    function ajaxCall(selectedLocationCode, locationType) {

        $.ajax({
            type: 'GET',
            url: "{{ url('/api/location-hierarchies') }}" + '/' + selectedLocationCode +
                '/lower-locations',
        }).done(function(response) {
            $('#' + locationType).append('<option value="" selected disabled>--Select An Option--</option>');
            response.data.forEach(function(locationHierarchy) {
                var option = new Option('', locationHierarchy.location_code);
                $(option).html(locationHierarchy.location_name);
                $('#' + locationType).append(option);
            });
        });
    }

    $(document).on('click', '#add', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Are you sure you want to add new location ?',
            showDenyButton: true,
            confirmButtonText: `Yes`,
            denyButtonText: `No`,
            padding:'10em',
            width:'500px'
        }).then((result) => {
            if (result.isConfirmed) {
                let locationHierarchy = $('#locationHierarchyForm').serialize();
                submitData(locationHierarchy);
            }
        })

    });
    $('#showFlashMessage').hide();


    function submitData(locationHierarchy) {
        var closeButton =
            '<button type="button" style="color:white !important;opacity: 1 !important;" class="close" aria-hidden="true"></button>';
        $.ajax({
            type: 'POST',
            url: "{{ route('admin.location-hierarchies.store') }}",
            data: locationHierarchy,
            datatype: "json",
        }).done(function(data) {
           // window.location.reload();
            $("#locationHierarchyForm")[0].reset();
            $('#showFlashMessage').removeClass().addClass('alert alert-success').show().empty().html(
                closeButton + data
                .message);

        }).fail(function(data) {
            var flashMessage = $('#showFlashMessage');
            flashMessage. removeClass().addClass('alert alert-danger').show().empty();

            if (data.status == 500) {
                flashMessage.html(closeButton + data.responseJSON.errors);

            }
            if (data.status == 400) {
                flashMessage.html(closeButton + data.responseJSON.message);

            }
            if (data.status == 422) {
                var errorString = "<ol type='1'>";
                for (error in data.responseJSON.data) {
                    errorString += "<li>" + data.responseJSON.data[error] + "</li>";
                }
                errorString += "</ol>";
                flashMessage.html(closeButton + errorString);
            }
        });
    }

</script>
