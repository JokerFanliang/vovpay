
<header class="main-header">
    <!-- Logo -->
    <a href="#" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><img width="41" src="/images/logo/logo-ico.png" alt="User Image"></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><img width="135" src="/images/logo/logo.png" alt="User Image"></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="/AdminLTE/dist/img/user4-128x128.jpg" class="user-image" alt="User Image">
                        <span class="hidden-xs">{{ Auth::user()->username }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="/AdminLTE/dist/img/user4-128x128.jpg" class="img-circle" alt="User Image">

                            <p>
                                {{ Auth::user()->username }}
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <li class="user-body">
                            <div class="row">
                                <div class="col-xs-4 text-center">
                                    <a href="#" onclick="editPwd('编辑密码')">修改密码</a>
                                </div>
                                <div class="col-xs-4 text-center">

                                </div>
                                <div class="col-xs-4 text-center">
                                    <a href="{{route('court.signOut')}}">退出</a>
                                </div>
                            </div>
                            <!-- /.row -->
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>

<section>
    <div class="modal fade" id="editPwdModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog" style="margin-top: 123px">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body" style="overflow: auto;">
                    <form id="ruleForm" action="{{route('court.editPassword')}}" class="form-horizontal" role="form" method="post">
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

<script src="{{ asset('AdminLTE/bower_components/jquery/dist/jquery.min.js') }}"></script>

<script>
    $().ready(function () {
        $('#ruleForm').bootstrapValidator({
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
        $('#ruleForm').data('bootstrapValidator').validate();
        if (!$('#ruleForm').data('bootstrapValidator').isValid()) {
            return;
        }
        _this.removeAttr('onclick');

        var $form = $('#ruleForm');
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