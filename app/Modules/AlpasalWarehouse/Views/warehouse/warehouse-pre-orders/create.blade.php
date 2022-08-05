@extends('AdminWarehouse::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
        @include("AdminWarehouse::layout.partials.breadcrumb",
        [
        'page_title'=>$title,
        'sub_title'=> "Create {$title}",
        'icon'=>'home',
        'sub_icon'=>'',
        'manage_url'=>route($base_route.'index'),
        ])

        <section class="content">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-header with-border">

                            <h3 class="box-title">Add New {{$title}}</h3>

                            <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">
                                @can('View List Of WH Pre Orders')
                                    <a href="{{ route($base_route.'index') }}" style="border-radius: 0px; " class="btn btn-sm btn-primary">
                                        <i class="fa fa-list"></i>
                                        List of {{formatWords($title,true)}}
                                    </a>
                                @endcan
                            </div>

                        </div>

                        <!-- /.box-header -->
                        @include("AdminWarehouse::layout.partials.flash_message")
                        @can('Create WH Pre Order')
                            <div class="box-body">
                                <form class="form-horizontal" role="form" id="createPreOrder" action="{{route($base_route.'store')}}" method="post" enctype="multipart/form-data">
                                    {{csrf_field()}}

                                    <div class="box-body">

                                        @include(''.$module.'.warehouse.warehouse-pre-orders.common.form')

                                    </div>
                                    <!-- /.box-body -->

                                    <div class="box-footer">
                                        <button type="submit" style="width: 49%;margin-left: 17%;" class="btn btn-block btn-primary add">Add</button>
                                    </div>
                                </form>
                            </div>
                        @endcan
                    </div>
                    <!-- /.box -->
                </div>
                <!--/.col (left) -->

            </div>
            <!-- /.row -->
        </section>

    </div>



@endsection
@push('scripts')
    <script>

        $('#createPreOrder').submit(function (e, params) {
            var localParams = params || {};

            if (!localParams.send) {
                e.preventDefault();
            }


            Swal.fire({
                title: 'Are you sure you want to add new Allpasal warehouse preorder ?',
                showCancelButton: true,
                confirmButtonText: `Yes`,
                padding:'10em',
                width:'500px'
            }).then((result) => {
                if (result.isConfirmed) {

                    $(e.currentTarget).trigger(e.type, { 'send': true });
                    Swal.fire({
                        title: 'Please wait...',
                        hideClass: {
                            popup: ''
                        }
                    })
                }
            })
        });


        $(function() {
            $('.datetimepicker').datetimepicker({
              format: 'YYYY-MM-DD HH:mm:ss'
            });
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#image_preview').attr('src', e.target.result).show();
                }
                reader.readAsDataURL(input.files[0]); // convert to base64 string
            }
        }

        $('#banner_image').change(function(){
            readURL(this);
        })
        $('#image_preview').attr('src', e.target.result).hide();
    </script>
@endpush
