@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
        @include("Admin::layout.partials.breadcrumb",
        [
        'page_title'=>$title,
        'sub_title'=> "Edit the {$title}",
        'icon'=>'home',
        'sub_icon'=>'',
        'manage_url'=>route('admin.site-pages.index'),
        ])

        <section class="content">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-success">
                        <div class="box-header with-border">

                            <h3 class="box-title">Edit the Page : {{$sitePage->content_type}}</h3>

                            <div class="pull-right" style="margin-top: -5px;margin-left: 10px;">
                                <a href="{{ route('admin.site-pages.index') }}" style="border-radius: 0px; " class="btn btn-sm btn-primary">
                                    <i class="fa fa-list"></i>
                                    List of Site Pages
                                </a>
                            </div>

                        </div>

                        <!-- /.box-header -->
                        @include("Admin::layout.partials.flash_message")
                        <div class="box-body">
                            <form class="form-horizontal" id="editSitePages" role="form" action="{{route($base_route.'.update',$sitePage->content_type)}}" enctype="multipart/form-data" method="post">
                                @method('PUT')
                                @csrf

                                <div class="box-body">

                                @include(''.$module.'.admin.site-setting.common.form')

                                </div>
                                <!-- /.box-body -->

                                <div class="box-footer">
                                    <button type="submit" style="width: 49%;margin-left: 17%;" class="btn btn-block btn-primary edit">Save</button>
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


    <script>
        $('#editSitePages').submit(function (e, params) {
            var localParams = params || {};

            if (!localParams.send) {
                e.preventDefault();
            }


            Swal.fire({
                title: 'Are you sure you want to edit site setting detail  ?',
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
