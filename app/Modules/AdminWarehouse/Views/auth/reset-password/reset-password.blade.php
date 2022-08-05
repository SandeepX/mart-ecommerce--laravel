<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> Reset Password | Alpasal </title>
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
        <b>Reset Password </b>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">
            <b>Reset For Email : {{$login_email}}</b>
        </p>

        <form action="{{route('warehouse.update.reset.password')}}" method="post">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">


            <input type="hidden" name="login_email" value="{{ $login_email }}">



            <div class="form-group has-feedback">
                <input type="password" class="form-control" name="password" placeholder="New Password" autocomplete="off" required>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>

            <div class="form-group has-feedback">
                <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm New Password" autocomplete="off" required>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>

            <div class="row">
                <!-- /.col -->
                <div class="col-xs-12">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Change Password</button>
                </div>
                <!-- /.col -->
            </div>
        </form>
        <br>
        <a href="{{route('warehouse.login')}}" class="text-center">Log In ?</a>


    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->


</body>
</html>
