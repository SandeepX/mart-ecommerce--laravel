@extends('admin.common.masterlayout')
@section('content')
    <div class="content-wrapper">
    @include('admin.partials.breadcrumb',
     [
     'page_title'=>App\Helpers\ViewHelper::formatWords($base_route,true),
     'sub_title'=> 'Customer Uploads' ,
     'icon'=>'home',
     'sub_icon'=>$sub_icon,
     'manage_url'=>$base_route
     ])
    <!-- Main content -->
        <section class="content">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 style="font-weight: 600" class="box-title">Customer : {{$customer->full_name_english}} | Reg No. : {{$customer->reg_no}} </h3>
                        </div>
                        <!-- /.box-header -->
                    @include('admin.partials.flash_message')
                    <!-- form start -->

                            <div class="box-body">
                                <form action="{{route('customers.post.uploads',$customer->id)}}" method="post">
                                    {{ csrf_field() }}
                                <b>Important Registration Documents (can add more than one) :</b>
                                <br />
                                <br />
                                <input type="file" id="fileupload" name="customer_files[]" data-url="{{route('customers.post.uploads',$customer->id)}}" multiple />
                                <br />
                                <p id="loading"></p>

                                </form>

                                <div id="files_list"></div>

                                <table id="files_table" role="presentation" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Preview</th>
                                            <th>File Name</th>
                                            <th>Upload on</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                     <tbody>
                                     </tbody>
                                </table>

                            </div>
                            <!-- /.box-body -->
                    </div>
                    <!-- /.box -->

                </div>
                <!--/.col (left) -->

            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
@endsection

@push('scripts')
<script src="{{url('js/vendor/jquery.ui.widget.js')}}"></script>
<script src="{{url('js/jquery.iframe-transport.js')}}"></script>
<script src="{{url('js/jquery.fileupload.js')}}"></script>

<script>
    $(function () {

        callData();

        function readURL(input) {

            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    if(input.files[0].type == 'image/png'
                            || input.files[0].type == 'image/jpg'
                            || input.files[0].type == 'image/jpeg'
                            || input.files[0].type == 'application/pdf'
                            || input.files[0].type == 'application/sql'
                    ){
                        // $('<img/>').attr('src', e.target.result).appendTo($('#files_list'));
                        $('#loading').text('Uploading...');
                        input.submit();
                    }else{
                        $('<p/>').html(input.files[0].name  + ' (Not Allowed) <br>').css('color','red').appendTo($('#files_list'));
                        setTimeout(function () {
                            $('#files_list').html('');
                        },10000);
                        // alert('Declining Invalid Files and Continue Uploading...');
                    }
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        $('#fileupload').fileupload({
            dataType: 'json',
            add: function (e, data) {
                readURL(data);
            },
            done: function (e, data) {

                var tr = $('<tr>');
                var td = $('<td>');
                $.each(data.result.files, function (index, file) {
                    var row =   '<tr data-id="'+file.fileID+'">'+
                            '<td>'+file.preview+'</td>'+
                            '<td>'+file.name+'</td>'+
                            '<td>'+file.upload_on+'</td>'+
                            '<td>'+file.delete_action+'</td>'+
                            '</tr>';

                    $('#files_table tbody').prepend(row);
                });
                $('#loading').text('');
            }
        });
    });

    function callData () {
        $.ajax({

            type:"GET",
            url:"{{route('customers.list.uploads',$customer->id)}}",
            success: function(data) {
                $('#files_table tbody').html(data.uploads_view);
            }
        });
    }

    $(document).on('click','#delete_customer_upload',function () {
        var upload_id = $(this).data('id') ;
        form_data = {
            'upload_id' : upload_id
        };
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN':  $('meta[name="_token"]').attr('content')
            }
        });

        $.ajax({
            type:"POST",
            url:"{{route('customers.delete.upload',$customer->id)}}",
            data : form_data,
            success: function(data) {
                $('#files_table tbody tr[data-id="'+upload_id+'"]').remove();
            }
        });

        return false;
    });


</script>
@endpush