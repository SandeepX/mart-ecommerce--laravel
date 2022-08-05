<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Alpasal - Warehouse Admin </title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="_token" content="{{csrf_token()}}" />
    <link rel="icon" type="image/png" sizes="256x256" href="">

    @include('AdminWarehouse::layout.common.head_links')

    <style>
        .center {
            margin-top:50px;
        }

        .modal-header {
            padding-bottom: 5px;
        }

        .modal-footer {
            padding: 0;
        }

        .modal-footer .btn-group button {
            height:40px;
            border-top-left-radius : 0;
            border-top-right-radius : 0;
            border: none;
            border-right: 1px solid #ddd;
        }

        .modal-footer .btn-group:last-child > button {
            border-right: 0;
        }
    </style>
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    @stack('css')
</head>
<body  class="hold-transition skin-purple fixed  sidebar-mini">
<div class="wrapper">

    @include('AdminWarehouse::layout.partials.nav-bar')

    @include('AdminWarehouse::layout.partials.sidebar')

    {{--for loader spinner--}}
    <div id="custom-loader">
        <div class="cv-spinner">
            <span class="spinner"></span>
        </div>
    </div>
    {{--for loader spinner--}}


    @yield('content')

    @include('AdminWarehouse::layout.partials.footer')

</div>

@include('AdminWarehouse::layout.common.body_links')



@stack('scripts')

@yield('script_blades')
<script src="{{asset('js/app.js')}}"></script>


</body>
</html>
