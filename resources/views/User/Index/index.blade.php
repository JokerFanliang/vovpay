@extends("User.Commons.layout")
@section('title','我的信息')
@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-switch/bootstrap-switch.min.css') }}">
@endsection
@section('content')
    <div class="content-wrapper" style="padding: 0;margin: 0">
        <br>
                    <div class="row">
                        <div class="col-md-9 col-lg-9 col-sm-6" style="background: #ffffff">

                            <table class="table table-hover table-bordered" style="margin-top: 10px">
                                <tr style="background: #f5f6f9">
                                    <th style="width: 200px">名称</th>
                                    <th>信息</th>
                                </tr>
                                <tr>
                                    <td>商户号</td>
                                    <td>{{$user->merchant}}</td>
                                </tr>
                                <tr>
                                    <td>用户名</td>
                                    <td style="color:green"><b>{{$user->username}}</b></td>
                                </tr>
                                <tr>
                                    <td>商户密钥</td>
                                    <td>{{$user->apiKey}}</td>
                                </tr>
                                <tr>
                                    <td>账户余额</td>
                                    <td style="color: red"><b>￥ @if(isset($statistical->handlingFeeBalance)) {{$statistical->handlingFeeBalance}} @else 0.00 @endif</b></td>
                                </tr>
                                <tr>
                                    <td>手机</td>
                                    <td>{{$user->phone}}</td>
                                </tr>
                                <tr>
                                    <td>邮箱</td>
                                    <td>{{$user->email}}</td>
                                </tr>
                                <tr>
                                    <td>创建时间</td>
                                    <td>{{$user->created_at}}</td>
                                </tr>
                                <tr>
                                    <td>支付密码</td>
                                    <td><button type="buttom" class="btn btn-primary" onclick="editPaypwd()">修改支付密码</button></td>
                                </tr>

                            </table>

                            {{--<a href="{{route('user.order')}}" style="margin: 15px">查看更多</a>--}}
                            <br>
                            <br>

                        </div>

                    </div>

    </div>

    <section>
        <div class="modal fade" id="editpayForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog" style="margin-top: 123px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"></h4>
                    </div>
                    <div class="modal-body" style="overflow: auto;">
                        <form id="payPwdForm" action="{{route('user.editPaypwd')}}" class="form-horizontal" role="form" method="post">
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
                                <button type="button" class="btn btn-primary" onclick="save1($(this))">提交</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="{{ asset('AdminLTE/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script type="text/javascript">
        function editPaypwd(){
            $('.modal-title').html('修改支付密码');
            $('#editpayForm').modal('show');
        }

        $().ready(function () {
            $('#payPwdForm').bootstrapValidator({
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
    /**
     * 提交
     */
    function save1(_this) {
        // formValidator();
        $('#payPwdForm').data('bootstrapValidator').validate();
        if (!$('#payPwdForm').data('bootstrapValidator').isValid()) {
            return;
        }
        _this.removeAttr('onclick');

        var $form = $('#payPwdForm');
        $.post($form.attr('action'), $form.serialize(), function (result) {
            if (result.status) {
                $('#editPwdModel').modal('hide');
                setInterval(function () {
                    window.location.reload();
                }, 1000);

                toastr.success(result.msg);
            } else {
                $('#editPwdModel').modal('hide');
                _this.attr("onclick", "save($(this))");
                toastr.error(result.msg);
            }
        }, 'json');

    }
    </script>
@endsection