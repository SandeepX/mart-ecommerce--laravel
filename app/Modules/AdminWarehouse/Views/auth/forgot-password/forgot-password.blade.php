<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> Forgot Password | Alpasal </title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="{{url('admin/bootstrap/css/bootstrap.min.css')}}">


    <!-- Theme style -->
    <link rel="stylesheet" href="{{url('admin/dist/css/AdminLTE.min.css')}}">


</head>
<body class="hold-transition login-page">

<div class="login-box">
    @include('AdminWarehouse::layout.partials.flash_message')
    <div class="login-logo">
        <b>Forgot Your Password ? </b>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">Enter your email to reset password .</p>

        <form action="{{route('warehouse.send.reset.email')}}" method="post">
            {{csrf_field()}}
            <div class="form-group has-feedback">
                <input type="text" class="form-control" name="login_email" placeholder="Email" required>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>

            <div class="row">
                <!-- /.col -->
                <div class="col-xs-12">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Send Password Reset Link</button>
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
