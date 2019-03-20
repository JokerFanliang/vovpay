<!-- 顶部 -->
<header class="main-header">
    <!-- Logo -->
    <a href="#" class="logo">
        <!-- 侧边栏小标志 -->
        <span class="logo-mini"><img width="41" src="/images/logo/logo-ico.png" alt="User Image"></span>
        <!-- 常规状态的标志 -->
        <span class="logo-lg"><img width="135" src="/images/logo/logo.png" alt="User Image"></span>
    </a>
    <!-- 首部导航条 -->
    <nav class="navbar navbar-static-top">
        <!-- 侧边栏切换按钮-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="{{ asset('AdminLTE/dist/img/user2-160x160.jpg') }}" class="user-image" alt="User Image">
                        <span class="hidden-xs">admin</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- 用户图片 -->
                        <li class="user-header">
                            <img src="{{ asset('AdminLTE/dist/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">

                            <p>{{ Auth::user()->username }} - 网站开发者</p>
                        </li>
                        <!-- 用户退出登录-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="#" onclick="editPwd('编辑密码')">修改密码</a>
                            </div>
                            <div class="pull-right">
                                <a href="{{ route('admin.dropout') }}" class="btn btn-default btn-flat">退出</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
<!-- 顶部结束-->
<script src="{{ asset('AdminLTE/bower_components/jquery/dist/jquery.min.js') }}"></script>
<section>
    <div class="modal fade" id="editPwdModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog" style="margin-top: 123px">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body" style="overflow: auto;">
                    <form id="ruleForm1" action="{{route('admin.editpwd')}}" class="form-horizontal" role="form" method="post">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="" class="col-xs-3 control-label">原密码:</label>
                            <div class="col-xs-9">
                                <input type="password" class="form-inline" name="password" placeholder="请输入原密码"
                                       style="width: 150px;height: 35px;margin-right: 10px">
                            </div>
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="" class="col-xs-3 control-label">新密码:</label>
                            <div class="col-xs-9">
                                <input type="password" class="form-inline" name="newPassword" placeholder="输入新密码"
                                       style="width: 150px;height: 35px;margin-right: 10px">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-xs-3 control-label">再次输入:</label>
                            <div class="col-xs-9">
                                <input type="password" class="form-inline" name="rpassword" placeholder="再次输入新密码"
                                       style="width: 150px;height: 35px;margin-right: 10px">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                            <button type="button" class="btn btn-primary" onclick="savePasswd($(this))">提交</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $().ready(function () {
        $('#ruleForm1').bootstrapValidator({
            message: 'This value is not valid',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                newPassword: {
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
                            field: 'newPassword',
                            message: '两次输入的密码不相符<br>'
                        },
                    }
                },
            }
        })
    });

    function editPwd(title) {
        $('.modal-title').html(title);
        $('#editPwdModel').modal('show');
    }

    /**
     * 提交
     */
    function savePasswd(_this) {
        // formValidator();
        $('#ruleForm1').data('bootstrapValidator').validate();
        if (!$('#ruleForm1').data('bootstrapValidator').isValid()) {
            return;
        }
        _this.removeAttr('onclick');

        var $form = $('#ruleForm1');
        $.post($form.attr('action'), $form.serialize(), function (result) {
            if (result.status) {
                $('#editPwdModel').modal('hide');
                setInterval(function () {
                    window.location.reload();
                }, 1000);

                toastr.success(result.msg);
            } else {
                $('#editPwdModel').modal('hide');
                _this.attr("onclick", "savePasswd($(this))");
                toastr.error(result.msg);
            }
        }, 'json');

    }

</script>