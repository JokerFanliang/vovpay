<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>商户注册</title>
    <!--用百度的静态资源库的cdn安装bootstrap环境-->
    <!-- Bootstrap 核心 CSS 文件 -->
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
    <style type="text/css">
        body {
            background: url(/AdminLTE/dist/img/timg.jpg) no-repeat;
            background-size: cover;
            font-size: 16px;
        }

        .form {
            background: rgba(255, 255, 255, 0.2);
            width: 400px;
            margin: 100px auto;
        }

        #login_form {
            display: none;
        }

        #register_form {
            display: block;
        }

        .fa {
            display: inline-block;
            top: 27px;
            left: 6px;
            position: relative;
            color: #ccc;
        }

        input[type="text"], input[type="password"] {
            padding-left: 26px;
        }

        .checkbox {
            padding-left: 21px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="form row" style="margin:150px auto">
        <form class="form-horizontal col-sm-offset-3 col-md-offset-3" id="register_form" method="post"
              action="{{route('user.register')}}">
            {{ csrf_field() }}
            <h3 class="form-title">欢迎注册</h3>
            <input type="hidden" name="groupType" value="1">
            <input type="hidden" name="parentId" value="0">
            <div class="col-sm-9 col-md-9">
                <div class="form-group">
                    <i class="fa fa-user fa-lg"></i>
                    <input class="form-control required" type="text" placeholder="用户名" name="username"
                           autofocus="autofocus"/>
                </div>
                <div class="form-group">
                    <i class="fa fa-lock fa-lg"></i>
                    <input class="form-control required" type="password" placeholder="密码" id="register_password" name="password"/>
                </div>
                <div class="form-group">
                    <i class="fa fa-check fa-lg"></i>
                    <input class="form-control required" type="password" placeholder="确认密码" name="rpassword"/>
                </div>
                <div class="form-group">
                    <i class="fa fa-envelope fa-lg"></i>
                    <input class="form-control eamil" type="text" placeholder="手机号码"
                           name="phone"/>
                </div>
                <div class="form-group">
                    <i class="fa fa-envelope fa-lg"></i>
                    <input class="form-control eamil" type="email" placeholder="Email                           "
                           name="email" style="text-align:center"/>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-success pull-right" value="注册 "/>
                    <input type="reset" class="btn btn-info pull-left" value="重置"/>
                </div>
            </div>
        </form>
    </div>
</div>
<script src="{{ asset('AdminLTE/bower_components/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('AdminLTE/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('AdminLTE/plugins/iCheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/toastr/toastr.js') }}"></script>
<script src="{{ asset('plugins/bootstrapValidator/bootstrapValidator.min.js') }}"></script>
</body>
<script>
    $().ready(function () {
        $('#register_form').bootstrapValidator({
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
                        },
                        stringLength: {
                            min: 6,
                            max: 30,
                            message: '密码长度必须大于6位，小于30位<br>'
                        },
                        identical: {
                            field: 'rpassword',
                            message: '两次输入的密码不相符<br>'
                        },
                    }
                },
                rpassword: {
                    validators: {
                        notEmpty: {
                            message: '密码不能为空!'
                        },
                        stringLength: {
                            min: 6,
                            max: 30,
                            message: '密码长度必须大于6位，小于30位<br>'
                        },
                        identical: {
                            field: 'password',
                            message: '两次输入的密码不相符<br>'
                        },
                    }
                },
                phone: {
                    validators:{
                        notEmpty:{
                            message:'手机号码不能为空'
                        },
                        stringlength:{
                            min:11,
                            max:11,
                            message:'请输入11位手机号码'
                        },
                        regexp:{
                            regexp:/^1[3|5|8]{1}[0-9]{9}$/,
                            message:'请输入正确的手机号码'
                        }
                    }
                },
                email: {
                    validators: {
                        notEmpty: {
                            message: '邮箱不能为空!'
                        },
                        emailAddress: {
                            message: '邮箱址格式有误'
                        }
                    }

                }
            }
        }).on('success.form.bv', function (e) {
            e.preventDefault();
            var $form = $(e.target);
            $.post($form.attr('action'), $form.serialize(), function (result) {
                if (result.status) {
                    toastr.success(result.msg);
                    setInterval(function () {
                        window.location.href = '/user/login';
                    }, 500);
                }
            }, 'json');
        });
    });
</script>
</html>