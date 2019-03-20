<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }} - [管理后台]</title>
    <link rel="stylesheet" href="{{ asset('AdminLTE/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bootstrapValidator/bootstrapValidator.min.css') }}">
    <link rel="stylesheet" href="{{ asset('AdminLTE/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('AdminLTE/bower_components/Ionicons/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('AdminLTE/dist/css/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ asset('AdminLTE/dist/css/skins/_all-skins.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert/sweetalert.css') }}">
    @yield('css')
</head>
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
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper" style="padding: 0px;">
    <!-- 顶部内容 -->
@include('Admin.Commons._header')
<!-- 左侧导航 -->
@include('Admin.Commons._left')
<!-- 右侧内容 -->
    <div class="content-wrapper">
        <!-- 内容导航 -->
        <section class="content-header">
            <h1>@yield("title",'后台主页') <small>@if (isset($description) && $description) {{ $description }} @endif</small></h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('admin') }}"><i class="fa fa-dashboard"></i> 主页</a></li>
                {{--<li class="active">{{ $title }}</li>--}}
                <li class="active">@yield("title",'后台主页')</li>
            </ol>
        </section>
        <!-- 内容导航结束 -->
        <!-- 主内容 -->
        <section class="content" style="display:none">
            @yield('content')
        </section>
        <!-- /.主内容结束 -->
    </div>
    <!--右侧内容结束-->
    <!--底部-->
    @include('Admin.Commons._footer')
</div>
</body>
<script src="{{ asset('AdminLTE/bower_components/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('AdminLTE/bower_components/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ asset('AdminLTE/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrapValidator/bootstrapValidator.min.js') }}"></script>
<script src="{{ asset('AdminLTE/dist/js/adminlte.min.js') }}"></script>
<script src="{{ asset('plugins/toastr/toastr.js') }}"></script>
<script src="{{ asset('plugins/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ asset('js/iNotify.js') }}"></script>

<script>
    var iNotify = new iNotify({
        message: '有消息了。',//标题
        effect: 'flash', // flash | scroll 闪烁还是滚动
        interval: 300,
        audio:{
            //file: ['/Public/sound/msg.mp4','/Public/sound/msg.mp3','/Public/sound/msg.wav']
            file:'http://tts.baidu.com/text2audio?lan=zh&ie=UTF-8&spd=5&text=有客户申请提现啦'
        }
    });
    setInterval(function() {
        $.ajax({
            type: "post",
            url: "{{ route('withdraw.checkNotice') }}",
            data:{_token:"{{ csrf_token() }}"},
            cache: false,
            success: function (res) {
                if (res.data.count > 0) {
                    iNotify.setFavicon(res.data.count).setTitle('提现通知').notify({
                        title: "新通知",
                        body: "有客户，提现啦..."
                    }).player();
                }
            }
        });
    },60000);
</script>

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
