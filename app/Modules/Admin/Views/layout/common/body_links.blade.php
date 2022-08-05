<!-- jQuery 2.2.3 -->
<script src="{{ asset('admin/plugins/jQuery/jquery-2.2.3.min.js') }}"></script>
{{--<!-- jQuery UI 1.11.4 -->--}}
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="{{asset('admin/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js"></script>

{{--<!-- datepicker -->--}}
<script src="{{ asset('admin/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{asset('admin/dist/js/app.min.js')}}"></script>
<!-- DataTables -->
<script src="{{asset('admin/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('admin/plugins/datatables/dataTables.bootstrap.min.js')}}"></script>
{{--<script src="https://code.jquery.com/jquery-3.3.1.js" type="text/javascript" charset="utf-8"></script>--}}
{{--<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" type="text/javascript" charset="utf-8" ></script>--}}
{{--<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js" type="text/javascript" charset="utf-8" ></script>--}}
{{--<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js" type="text/javascript" charset="utf-8" ></script>--}}
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" type="text/javascript" charset="utf-8" ></script>--}}
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js" type="text/javascript" charset="utf-8" ></script>--}}
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js" type="text/javascript" charset="utf-8" ></script>--}}
{{--<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js" type="text/javascript" charset="utf-8" ></script>--}}
{{--<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js" type="text/javascript" charset="utf-8"></script>--}}
{{--<script src="https://cdn.datatables.net/select/1.2.7/js/dataTables.select.min.js" type="text/javascript" charset="utf-8"></script>--}}
{{--<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.colVis.min.js" type="text/javascript" charset="utf-8"></script>--}}
{{--<script src="https://cdn.datatables.net/plug-ins/1.10.19/features/searchHighlight/dataTables.searchHighlight.min.js" type="text/javascript" charset="utf-8"></script>--}}

{{--Custom JS Scripts--}}
{{--Check ALl Permissiosn--}}
<script>
    $("#CheckAll").click(function () {
        $(".filled-in").prop('checked', $(this).prop('checked'));
    });
</script>

<script src="{{asset('admin/plugins/magnific-popup/magnific-popup.js')}}"></script>

<!-- Select2 -->
<script src="{{asset('admin/plugins/select2/select2.full.min.js')}}"></script>
{{--<!-- ChartJS 1.0.1 -->--}}
{{--<script src="{{asset('admin/plugins/chartjs/Chart.js')}}"></script>--}}
<script src="{{asset('admin/plugins/slimScroll/jquery.slimscroll.js')}}"></script>
<script src="{{asset('admin/plugins/sweetalert2/js/sweetalert2.all.min.js')}}"></script>
{{--Nepali Date Picker--}}
{{--<script type="text/javascript" src="{{asset('admin/plugins/nepali_date_picker/nepali.datepicker.v2.2.min.js')}}"></script>--}}
{{--FLash Message Delay--}}

<script>
    $('div.alert.alert-success').not('.alert-important').delay(5000).slideUp(900);
    $('div.alert.alert-danger').not('.alert-important').delay(30000).slideUp(900);
   // $('div.alert.alert-info').not('.alert-important').delay(10000).slideUp(900);
  // $('div.alert.alert-warning').not('.alert-important').delay(10000).slideUp(900);
</script>


{{--<script src="{{asset('admin/plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>--}}
{{--<script>--}}
    {{--$('.timepicker').timepicker({--}}
        {{--minuteStep: 1--}}
    {{--});--}}
{{--</script>--}}
<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip({html: true});
        jQuery(document).find('.select2').select2();

        $('#data-table').dataTable();
    });
</script>

<script>
$(document).ready(function() {
  $('.popup-link').magnificPopup({type: 'image'});
});
</script>



<script src="{{asset('shared/summernote/summernote-bs4.js')}}"></script>

<script>
    $(function () {

        $('.summernote').summernote({
            height: 250, // set editor height
            minHeight: null, // set minimum height of editor
            maxHeight: null, // set maximum height of editor
            focus: false ,// set focus to editable area after initializing summernote,
            toolbar: [
                ["style", ["style"]],
                ["font", ["bold", "italic", "underline", "clear"]],
                ["fontsize", ["fontsize"]],
                ["fontname", ["fontname"]],
                ["color", ["color"]],
                ["para", ["ul", "ol", "paragraph"]],
                ["height", ["height"]],
                ["table", ["table"]],
                ["insert", ["link", "hr","video","picture"]],
                ["view", ["fullscreen", "codeview"]],
                ["help", ["help"]]
            ],
            fontNamesIgnoreCheck: ["Roboto"],
            fontNames: ["Roboto","Arial", "Arial Black", "Comic Sans MS", "Courier New",
                "Helvetica Neue", "Helvetica", "Impact", "Lucida Grande",
                "Tahoma", "Times New Roman", "Verdana"],
        });

        $('.inline-editor').summernote({
            airMode: true
        });

    });
</script>


{{--@push('scripts')--}}
{{--<script>--}}
    {{--$("form").submit(function() {--}}
        {{--($(this).find()("button[type='submit']")).text("Please Wait...").attr('disabled', 'disabled');--}}
        {{--return true;--}}
    {{--});--}}
{{--</script>--}}
{{--@endpush--}}

