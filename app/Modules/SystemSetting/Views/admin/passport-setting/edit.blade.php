@extends('Admin::layout.common.masterlayout')
@section('content')
    <div class="content-wrapper">
        @include("Admin::layout.partials.breadcrumb",
        [
        'page_title'=>$title,
        'sub_title'=> "Edit $title",
        'icon'=>'home',
        'sub_icon'=>'',
        'manage_url'=>route($base_route.'.edit'),
        ])

        <section class="content">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-success">
                        <div class="box-header with-border">

                            <h3 class="box-title">Edit {{$title}}</h3>

                        </div>

                        <!-- /.box-header -->
                        @include("Admin::layout.partials.flash_message")
                        <div class="box-body">
                            <form class="form-horizontal" role="form" id="passportSettingEdit" action="{{route($base_route.'.update')}}" method="post">
                                @csrf

                                <div class="box-body">

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Login Endpoint</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" value="{{isset($passportSetting) ? $passportSetting->passport_login_endpoint : old('passport_login_endpoint')  }}"
                                                   placeholder="Enter passport login endpoint" name="passport_login_endpoint" >
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Client Id</label>
                                        <div class="col-sm-6">
                                            <input type="number" class="form-control" value="{{isset($passportSetting) ? $passportSetting->passport_client_id : old('passport_client_id')  }}"
                                                   placeholder="Enter passport client id" name="passport_client_id" >
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Client Secret</label>
                                        <div class="col-sm-6">
                                            <input type="password" id="password" class="form-control" value="{{isset($passportSetting) ? $passportSetting->passport_client_secret : old('passport_client_secret')  }}"
                                                   placeholder="Enter passport client secret" name="passport_client_secret" >
                                            <i class="fa fa-eye" id="toggle-password"></i>
                                        </div>
                                    </div>


                                </div>
                                <!-- /.box-body -->

                                <div class="box-footer">
                                    <button type="submit" style="width: 49%;margin-left: 17%;" class="btn btn-block btn-primary passportSettingEdit">Update</button>
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
@include("SystemSetting::admin.common.scripts.password-visibility-script")

<script>

    $('#passportSettingEdit').submit(function (e, params) {
        var localParams = params || {};

        if (!localParams.send) {
            e.preventDefault();
        }

        Swal.fire({
            title: 'Are you sure you want to edit passport setting detail ?',
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
