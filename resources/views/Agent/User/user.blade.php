@extends("Agent.Commons.layout")
@section('title','商户管理')
@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-switch/bootstrap-switch.min.css') }}"/>
@endsection
@section('content')


    <div class="row" style="margin: 20px 0px">
        <div class="col-xs-12 col-md-12">
            <div class="box">
                <div class="box-header">
                    <button type="button" class="btn btn-primary" onclick="showModel('添加商户')">添加商户</button>
                    <a href="javascript:;" onclick="location.replace(location.href);" class="btn pull-right"><i class="fa fa-undo"></i>刷新</a>
                </div>



                    <div class="box-body">

                        <div class="container-fluid">
                            <!-- Collect the nav links, forms, and other content for toggling -->
                            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1"
                                 style="background: #ffffff">
                                <form action="{{ route('agent.user') }}" method="get">
                                    <div class="form-inline">
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="商户号" name="merchant"
                                                   @if(isset($query['merchant'])) value="{{ $query['merchant'] }}" @endif />
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="用户名" name="username"
                                                   @if(isset($query['username'])) value="{{ $query['username'] }}" @endif />
                                        </div>
                                        <div class="form-group">
                                            <select class="form-control" id="status" name="status">
                                                <option value="-1"
                                                        @if(!isset($query['status']) || $query['status'] =='-1') selected @endif >
                                                    会员状态
                                                </option>
                                                <option value="1"
                                                        @if(isset($query['status']) && $query['status'] =='1') selected @endif >
                                                    启用
                                                </option>
                                                <option value="0"
                                                        @if(isset($query['status']) && $query['status'] =='0') selected @endif>
                                                    禁用
                                                </option>
                                                <option value="2"
                                                        @if(isset($query['status']) && $query['status'] =='2') selected @endif>
                                                    已删除
                                                </option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary" id="btnSearch">查询</button>
                                    </div>
                                </form>

                            </div><!-- /.navbar-collapse -->
                        </div><!-- /.container-fluid -->
                        <br>
                        <table id="example2" class="table table-condensed table-bordered table-hover">
                            <thead>
                            <tr style="font-size: 15px;text-align: center;color: #999999;background: #f5f6f9">
                                <th>商户号</th>
                                <th>用户名</th>
                                <th>用户类型</th>
                                <th>状态</th>
                                <th>注册时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($list[0]))
                                @foreach($list as $v)
                                    <tr>
                                        <td>{{$v->merchant}}</td>
                                        <td>{{$v->username}}</td>
                                        <td>普通商户</td>
                                        <td>
                                            <input class="switch-state" name="status" data-id="{{$v->id}}"
                                                   type="checkbox"
                                                   @if($v->status==1) checked @endif >
                                        </td>
                                        <td>{{$v->created_at}}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('agent.userChannel', array('id'=>$v['id'])) }}"
                                                   class="btn btn-success btn-sm">通道设置</a>
                                                {{--<a href="{{ route('agent.userOrder', array('id'=>$v['id'])) }}"--}}
                                                   {{--class="btn btn-success btn-sm">商户流水</a>--}}

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="9" style="text-align: center;color: #999999">未找到匹配数据</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                        {{$list->appends($query)->links()}}
                    </div>
            </div>
        </div>
    </div>



    {{--添加商户--}}
    <div class="modal fade" id="addModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true"
         data-backdrop="static">
        <div class="modal-dialog" style="margin-top: 123px">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body" style="overflow: auto;">
                    <form id="usersForm" action="{{route('agent.add')}}" class="form-horizontal" role="form"
                          method="post">
                        <input type="hidden" name="groupType" value="1">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="" class="col-xs-3 control-label">用户名</label>
                            <div class="col-xs-9">
                                <input type="text" class="form-control" id="username" name="username"
                                       placeholder="用户名">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-xs-3 control-label">密码</label>
                            <div class="col-xs-9">
                                <input type="password" class="form-control" name="password" placeholder="密码">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-xs-3 control-label">确认密码</label>
                            <div class="col-xs-9">
                                <input type="password" class="form-control" name="password_confirmation"
                                       placeholder="确认密码">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-xs-3 control-label">邮箱</label>
                            <div class="col-xs-9">
                                <input type="text" class="form-control" name="email" placeholder="邮箱">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-xs-3 control-label">电话</label>
                            <div class="col-xs-9">
                                <input type="text" class="form-control" name="phone" placeholder="电话">
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                            <button type="button" class="btn btn-primary" onclick="save($(this))">提交</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    {{--查看--}}
    <section>
        <div class="modal fade" id="showModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog" style="margin-top: 123px;width: 800px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"></h4>
                    </div>
                    <div class="modal-body" style="overflow: auto;">

                            <table class="table table-hover table-bordered">
                                <tr>
                                    <th>#</th>
                                    <th>商户号</th>
                                    <th>系统订单</th>
                                    <th>商户订单</th>
                                    <th>订单金额</th>
                                    <th>手续费</th>
                                    <th>平台收入</th>
                                    <th>代理收入</th>
                                    <th>商户收入</th>
                                    <th>操作</th>
                                </tr>
                                <tr>
                                    <td colspan="10"  style="text-align: center">没有找到匹配数据</td>
                                </tr>
                            </table>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                                <button type="button" class="btn btn-primary" onclick="save($(this))">提交
                                </button>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section("scripts")
    <script src="{{ asset('plugins/bootstrap-switch/bootstrap-switch.min.js') }}"></script>
    <script>
        $(function () {
            // formValidator();

            // 状态修改
            $('.switch-state').bootstrapSwitch({
                onText: '正常',
                offText: '禁用',
                onColor: "primary",
                offColor: "danger",
                size: "small",
                onSwitchChange: function (event, state) {
                    var id = $(event.currentTarget).data('id');
                    $.ajax({
                        type: 'POST',
                        url: '/agent/user/saveStatus',
                        data: {'status': state, 'id': id},
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (result) {
                            if (result.status) {
                                toastr.success(result.msg);
                            } else {
                                $('#addModel').modal('hide');
                                toastr.error(result.msg);
                            }
                        },
                        error: function (XMLHttpRequest, textStatus) {
                            toastr.error('通信失败');
                        }
                    })
                }
            })

            // 模态关闭
            $('#addModel').on('hidden.bs.modal', function () {
                $("#ruleForm").data('bootstrapValidator').destroy();
                $('#ruleForm').data('bootstrapValidator', null);
                $('#ruleForm').get(0).reset();
                formValidator();
            });

        })



        function showModel(title) {
            $('.modal-title').html(title);
            $('#addModel').modal('show');
        }

        function rate(title) {
            $('.modal-title').html(title);
            $('#rateModel').modal('show');
        }

        function show(title) {
            $('.modal-title').html(title);
            $('#showModel').modal('show');
        }

        /**
         * 提交
         */
        function save(_this) {
            formValidator();
            $('#usersForm').data('bootstrapValidator').validate();
            if (!$('#usersForm').data('bootstrapValidator').isValid()) {
                return;
            }
            _this.removeAttr('onclick');

            var $form = $('#usersForm');
            $.post($form.attr('action'), $form.serialize(), function (result) {
                if (result.status) {
                    $('#addModel').modal('hide');
                    setInterval(function () {
                        window.location.reload();
                    }, 1000);

                    toastr.success(result.msg);
                } else {
                    $('#addModel').modal('hide');
                    _this.attr("onclick", "save($(this))");
                    toastr.error(result.msg);
                }
            }, 'json');

        }



        /**
         * 表单验证
         */
        function formValidator() {
            $('#usersForm').bootstrapValidator({
                message: '输入值不合法',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    username: {
                        validators: {
                            notEmpty: {
                                message: '用户名不能为空!'
                            },
                            stringLength: {
                                min: 5,
                                max: 20,
                                message: '用户名长度%s~%s个字符!'
                            },
                            regexp: { //正则校验
                                regexp: /^[A-Z_a-z0-9]+$/,
                                message: '只能使用数字和字母!'
                            },
                            remote: {
                                url: "user/check",
                                message: "用户名已存在!",
                                type: "post",
                                data: function () { // 额外的数据，默认为当前校验字段,不需要的话去掉即可
                                    return {
                                        "value": $("#username").val().trim(),
                                        "type": 'username',
                                        "_token": $('meta[name="csrf-token"]').attr('content'),
                                        "id": $('input[name="id"]').val()
                                    };
                                },
                                delay: 500,
                            }
                        }
                    },
                    password: {
                        validators: {
                            notEmpty: {
                                message: '密码不能为空!'
                            },
                            stringLength: {
                                min: 6,
                                message: '密码最小长度%s个字符!'
                            },
                            different: { // 比较是否不同，否的话校验不通过
                                field: 'username', // 和userName字段比较
                                message: '密码不能与用户名相同!'
                            }
                        }
                    },
                    password_confirmation: {
                        validators: {
                            notEmpty: {
                                message: '确认密码不能为空!'
                            },
                            identical: { // 比较是否相同，否的话校验不通过
                                field: 'password', // 和password字段比较
                                message: '两次密码输入不一致!'
                            }
                        }
                    },
                    email: {
                        validators: {
                            notEmpty: {
                                message: '邮箱不能为空!'
                            },
                            emailAddress: { // 可以不用自己写正则
                                message: '邮箱格式不正确!'
                            },
                            remote: {
                                url: "user/check",
                                message: "邮箱已存在!",
                                type: "post",
                                data: function () { // 额外的数据，默认为当前校验字段,不需要的话去掉即可
                                    return {
                                        "value": $("input[name='email']").val().trim(),
                                        "type": "email",
                                        "_token": $('meta[name="csrf-token"]').attr('content'),
                                        "id": $('input[name="id"]').val()
                                    };
                                },
                                delay: 500,
                            }
                        }
                    },
                    phone: {
                        validators: {
                            notEmpty: {
                                message: '电话不能为空!'
                            },
                            stringLength: {
                                min: 11,
                                max: 11,
                                message: '电话长度%s~%s个字符！'
                            },
                            regexp: { //正则校验
                                regexp: /^[0-9]+$/,
                                message: '电话格式不正确!'
                            },
                            remote: {
                                url: "user/check",
                                message: "电话已存在!",
                                type: "post",
                                data: function () { // 额外的数据，默认为当前校验字段,不需要的话去掉即可
                                    return {
                                        "value": $("input[name='phone']").val().trim(),
                                        "type": 'phone',
                                        "_token": $('meta[name="csrf-token"]').attr('content'),
                                        "id": $('input[name="id"]').val()
                                    };
                                },
                                delay: 500,
                            }
                        }
                    }
                }
            });
        }


    </script>
@endsection