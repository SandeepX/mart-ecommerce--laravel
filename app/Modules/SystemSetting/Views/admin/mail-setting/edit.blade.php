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
                            <form class="form-horizontal" role="form" id="mailSettingEdit" action="{{route($base_route.'.update')}}" method="post">
                                @csrf

                                <div class="box-body">

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Mail Mailer</label>
                                        <div class="col-sm-6">

                                            <select name="mail_mailer" class="form-control" id="mail_mailer" required>
                                                <option value="" selected disabled>--Select An Option--</option>

                                                    @foreach($mailDrivers as $mailDriver)
                                                        <option {{isset($mailSetting) ? ( $mailSetting->mail_mailer == $mailDriver ? 'selected' : '') : '' }}
                                                                {{old('mail_mailer') == $mailDriver ? 'selected' : '' }} value="{{ $mailDriver }}">
                                                            {{$mailDriver }}
                                                        </option>
                                                    @endforeach

                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Mail Host</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" value="{{isset($mailSetting) ? $mailSetting->mail_host : old('mail_host')  }}"
                                                   placeholder="Eg: smtp.gmail.com" name="mail_host" >
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Mail Port</label>
                                        <div class="col-sm-6">
                                            <input type="number" class="form-control" value="{{isset($mailSetting) ? $mailSetting->mail_port : old('mail_port')  }}"
                                                   placeholder="Eg: 587" name="mail_port" >
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Mail Username</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" value="{{isset($mailSetting) ? $mailSetting->mail_username : old('mail_username')  }}"
                                                   placeholder="Eg: noreply@allpasal.com" name="mail_username" >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password" class="col-sm-2 control-label">Mail Password</label>
                                        <div class="col-sm-6">
                                            <input type="password" id="password" class="form-control" value="{{isset($mailSetting) ? $mailSetting->mail_password : old('mail_password')  }}"
                                                   name="mail_password" >
                                            <i class="fa fa-eye" id="toggle-password"></i>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Mail Encryption</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" value="{{isset($mailSetting) ? $mailSetting->mail_encryption : old('mail_encryption')  }}"
                                                   placeholder="Eg: tls" name="mail_encryption" >
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Mail From Address</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" value="{{isset($mailSetting) ? $mailSetting->mail_from_address : old('mail_from_address')  }}"
                                                   placeholder="Eg: noreply@allpasal.com" name="mail_from_address" >
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Mail From Name</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" value="{{isset($mailSetting) ? $mailSetting->mail_from_name : old('mail_from_name')  }}"
                                                   placeholder="Eg: Allpasal" name="mail_from_name" >
                                        </div>
                                    </div>

                                </div>
                                <!-- /.box-body -->

                                <div class="box-footer">
                                    <button type="submit" style="width: 49%;margin-left: 17%;" class="btn btn-block btn-primary mailSettingEdit">Update</button>
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
        $('#mailSettingEdit').submit(function (e, params) {
            var localParams = params || {};

            if (!localParams.send) {
                e.preventDefault();
            }

            Swal.fire({
                title: 'Are you sure you want to edit mail setting  ?',
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
