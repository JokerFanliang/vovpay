<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('app.name') }} - [场外第三方后台]</title>
{{--<title>{{ config('app.name', 'Laravel') }}</title>--}}
<!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{ asset('AdminLTE/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bootstrapValidator/bootstrapValidator.min.css') }}">
    <link rel="stylesheet" href="{{ asset('AdminLTE/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('AdminLTE/bower_components/Ionicons/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('AdminLTE/dist/css/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ asset('AdminLTE/dist/css/skins/_all-skins.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert/sweetalert.css') }}">
    @yield('css')
    <style>
        .toast-top-right {
            margin-top: 110px;
        }

        body {
            overflow-x: hidden;
            overflow-y: hidden;
        }

        .wrapper {
            overflow-x: hidden;
            overflow-y: hidden;
        }
    </style>
</head>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <!--header-->
@include('Court.Commons._header')

<!-- Left side column. contains the logo and sidebar -->
@include('Court.Commons._lift')

<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- 内容导航 -->
        <section class="content-header">
            <h1>@yield("title",'后台主页')</h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('court') }}"><i class="fa fa-dashboard"></i> 主页</a></li>
                <li class="active">@yield("title",'后台主页')</li>
            </ol>
        </section>
        <!-- 内容导航结束 -->
        <section class="content" style="display:none">
            @yield("content")
        </section>
    </div>
    <!--footer-->
    @include('Court.Commons._footer')
</div>
</body>
<script src="{{ asset('AdminLTE/bower_components/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('AdminLTE/bower_components/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ asset('AdminLTE/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrapValidator/bootstrapValidator.min.js') }}"></script>
<script src="{{ asset('AdminLTE/dist/js/adminlte.min.js') }}"></script>
<script src="{{ asset('plugins/toastr/toastr.js') }}"></script>
<script src="{{ asset('plugins/sweetalert/sweetalert.min.js') }}"></script>
<script>
    $(function () {
        toastr.options = {
            closeButton: false,
            debug: false,
            progressBar: false,
            positionClass: "toast-top-right",
            onclick: null,
            showDuration: "3000",
            hideDuration: "1000",
            timeOut: "3000",
            extendedTimeOut: "1000",
            showEasing: "swing",
            hideEasing: "linear",
            showMethod: "fadeIn",
            hideMethod: "fadeOut"
        };
        $('.content').hide().fadeIn(1000);
        $('body').css('overflow-y', 'auto');
    })
</script>
@yield('scripts')
</html>
