<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('app.name') }} | 登录</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('AdminLTE/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('AdminLTE/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('AdminLTE/bower_components/Ionicons/css/ionicons.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('AdminLTE/dist/css/AdminLTE.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/iCheck/square/blue.css') }}">
    <!-- toastr -->
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
    <!-- bootstrapValidator -->
    <link rel="stylesheet" href="{{ asset('plugins/bootstrapValidator/bootstrapValidator.min.css') }}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-box-body">
        <p class="login-box-msg">欢迎回来！请登录到您的帐户。</p>
        <form action="{{ route('user.login') }}" method="post" id="logoForm">
            {{ csrf_field() }}
            <div class="form-group">
                <input type="text" class="form-control" name="username" required value="{{ old('username') }}" placeholder="用户名"
                       @if($google_auth)
                       onchange="checkGoogle(this)"
                        @endif>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" required value="{{ old('password') }}" placeholder="密码">
            </div>

            @if($google_auth)
                <div class="form-group" style="display: none" id="google-auth">
                    <input type="password" class="form-control" name="auth_code"  value="{{ old('auth_code') }}" placeholder="google验证码">
                </div>
            @endif

            <div class="form-group">
                <div class="row">
                    <div class="col-xs-6">
                        <input type="text" name="captcha" maxlength="6" required placeholder="请输入验证码" autocomplete="off" class="form-control">
                    </div>
                    <div class="col-xs-6 text-right">
                        <img src="{{ captcha_src('flat') }}" alt="图形验证码" id="captcha" class="lau-sign-captcha" onclick="this.src='/captcha/flat?'+Math.random()">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <button type="submit" class="btn btn-primary btn-block btn-flat btn-login"  id="submit-login">登录</button>
                </div>
            </div>
        </form>
    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
<script src="{{ asset('AdminLTE/bower_components/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('AdminLTE/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('AdminLTE/plugins/iCheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/toastr/toastr.js') }}"></script>
<script src="{{ asset('plugins/bootstrapValidator/bootstrapValidator.min.js') }}"></script>
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' /* optional */
        });

        toastr.options = {
            closeButton: false,
            debug: false,
            progressBar: false,
            positionClass: "toast-top-center",
            onclick: null,
            showDuration: "300",
            hideDuration: "1000",
            timeOut: "3000",
            extendedTimeOut: "1000",
            showEasing: "swing",
            hideEasing: "linear",
            showMethod: "fadeIn",
            hideMethod: "fadeOut"
        };

        $('#logoForm').bootstrapValidator({
            message: 'This value is not valid',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                username: {
                    message: '用户名不能为空',
                    validators: {
                        notEmpty: {
                            message: '用户名不能为空！'
                        },
                    }
                },
                password: {
                    validators: {
                        notEmpty: {
                            message: '密码不能为空!'
                        }
                    }
                },

                captcha: {
                    validators: {
                        notEmpty: {
                            message: '验证码不能为空!'
                        },
                        stringLength: {
                            min: 6,
                            max: 6,
                            message: '验证码为6位!'
                        },
                    }
                }
            }
        }).on('success.form.bv', function(e) {
                e.preventDefault();
                var $form = $(e.target);
                $.post($form.attr('action'), $form.serialize(), function(result) {
                    if(result.status)
                    {
                        toastr.success(result.msg);
                        setInterval(function(){
                            window.location.href = '/user';
                        },500);
                    }else{
                        $('#captcha').click();
                        toastr.error(result.msg);
                        $('#submit-login').attr('disabled',false);
                    }
                }, 'json');


            });
    });


    /**
     * 检查Googlekey是否配置
     * @param element
     */
    function checkGoogle(element) {

        username = $(element).val();
        $.ajax({
            type: 'get',
            url: "{{route('user.hasGoogle')}}",
            data: 'username=' + username,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (result) {

                if (result.status == 1) {
                    $('#google-auth').slideDown(200);
                    $('#logoForm').bootstrapValidator("addField", "auth_code", {
                        validators: {
                            notEmpty: {
                                message: 'google认证码不能为空!'
                            }
                        }
                    });
                }else{

                    $('#logoForm').bootstrapValidator('removeField','auth_code');
                    $('#google-auth').slideUp(200);

                }
            },
            error: function (XMLHttpRequest, textStatus) {
                toastr.error('通信失败');
            }
        })
    }

</script>
</body>
</html>

