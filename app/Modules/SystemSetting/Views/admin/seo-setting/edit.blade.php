@extends('Admin::layout.common.masterlayout')
@push('css')
    <link rel="stylesheet" href="{{asset('admin/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css')}}">
@endpush
@section('content')
    <div class="content-wrapper">
    @include("Admin::layout.partials.breadcrumb",
    [
    'page_title'=>$title,
    'sub_title'=> "Create {$title}",
    'icon'=>'home',
    'sub_icon'=>'',
    'manage_url'=>route($base_route.'.show'),
    ])

        <section class="content">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-header with-border">

                            <h3 class="box-title">Update {{$title}}</h3>

                            <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">
                                <a href="{{ route('admin.seo-settings.show') }}" style="border-radius: 0px; " class="btn btn-sm btn-primary">
                                    <i class="fa fa-list"></i>
                                    {{$title}}
                                </a>
                            </div>

                        </div>

                        <!-- /.box-header -->
                        @include("Admin::layout.partials.flash_message")
                        <div class="box-body">
                            <form class="form-horizontal" id="seo-form" role="form" action="{{route($base_route.'.store')}}" enctype="multipart/form-data" method="post">
                                {{csrf_field()}}

                                <div class="box-body">

                                    @include(''.$module.'.admin.seo-setting.common.form')

                                </div>
                                <!-- /.box-body -->

                                <div class="box-footer">
                                    <button type="submit" style="width: 49%;margin-left: 17%;" class="btn btn-block btn-primary seoSettingEdit">Save</button>
                                </div>
                            </form>
                        </div>
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
    <script src="{{asset('admin/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js')}}"></script>
    <script>
        $('form input').on('keypress', function(e) {
            return e.which !== 13;
        });

        $('#seo-form').submit(function (e, params) {
            var localParams = params || {};

            if (!localParams.send) {
                e.preventDefault();
            }

            Swal.fire({
                title: 'Are you sure you want to edit seo setting detail  ?',
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

    </script>
@endpush

