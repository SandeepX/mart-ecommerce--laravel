<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login | Alpasal Warehouse</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="{{url('admin/bootstrap/css/bootstrap.min.css')}}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{url('admin/dist/css/AdminLTE.min.css')}}">

</head>
<body class="hold-transition login-page">

<div class="login-box">
    @include('Admin::layout.partials.flash_message')
    <div class="login-logo">
        <b>Alpasal - Warehouse Admin | Login </b>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">Login to start your session</p>

        <form action="{{route('warehouse.login')}}" method="post">
            {{csrf_field()}}
            <div class="form-group has-feedback">
                <input type="text" class="form-control" name="login_email" placeholder="Email" required>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <!-- /.col -->
                <div class="col-xs-12">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                </div>
                <!-- /.col -->
            </div>
        </form>
        <br>
        <a href="{{route('warehouse.forgot.password')}}" class="text-center">Forgot Password ?</a>
       {{-- <a href="{{route('admin.forgot.password')}}" class="text-center">Forgot Password ?</a>--}}


    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

</body>
</html>
