@extends("Admin.Commons.layout")    @section('title',$title)

@section("css")
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-switch/bootstrap-switch.min.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <button type="button" class="btn btn-primary" onclick="showModel('添加商户')">添加商户</button>
                    <a href="{{ route('users.index',['user']) }}" class="btn pull-right"><i class="fa fa-undo"></i>刷新</a>
                </div>
                <!-- /.box-header -->
                <div class="box box-primary">
                    <div class="box-body">
                        <form action="{{ route('users.index',['user']) }}" method="get">
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
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary" id="btnSearch">查询</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.box-body -->
                </div>
                <div class="box-body">
                    <table id="example2" class="table table-condensed table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>商户号</th>
                            <th>用户名</th>
                            <th>余额</th>
                            <th>上级代理</th>
                            <th>Email</th>
                            <th>电话</th>
                            <th>状态</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($list as $v)
                            <tr>
                                <td>{{ $v['id'] }}</td>
                                <td>{{ $v['merchant'] }}</td>
                                <td><span style="color: #4682B4;font-weight: bold">{{ $v['username'] }}</span></td>
                                <td><span style="color: #5F9EA0;font-weight: bold">{{ $v->Statistical->handlingFeeBalance }}</span></td>
                                <td><span style="color: #008080;font-weight: bold">{{ $v['agentName'] }}</span></td>
                                <td>{{ $v['email'] }}</td>
                                <td>{{ $v['phone'] }}</td>
                                <td>
                                    <input class="switch-state" data-id="{{ $v['id'] }}" type="checkbox"
                                           @if($v['status'] == 1) checked @endif >
                                </td>
                                <td>
                                    <a href="{{ route('users.channel', array('id'=>$v['id'])) }}"
                                       class="btn btn-success btn-sm">通道设置</a>
                                    <button type="button" class="btn btn-primary btn-sm"
                                            onclick="edit('会员编辑',{{ $v['id'] }})">编辑
                                    </button>
                                    <button type="button" class="btn btn-primary btn-sm"
                                            onclick="editBlance('余额加减',{{ $v['id'] }})">余额加减
                                    </button>
                                    {{--<button type="button" class="btn btn-danger btn-sm"--}}
                                            {{--onclick="del($(this),{{ $v['id'] }})">删除--}}
                                    {{--</button>--}}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @include('Admin.Commons._page')
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="modal fade" id="addModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
         data-backdrop="static">
        <div class="modal-dialog" style="margin-top: 123px">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body" style="overflow: auto;">
                    <form id="usersForm" action="{{ route('users.store') }}" class="form-horizontal" role="form">
                        <input type="hidden" name="id">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="" class="col-xs-3 control-label">用户名</label>
                            <div class="col-xs-9">
                                <input type="text" class="form-control" id="username" name="username" placeholder="用户名">
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
                        <div class="form-group">
                            <label for="" class="col-xs-3 control-label">上级代理</label>
                            <div class="col-xs-9">
                                <select class="form-control selectpicker" name="parentId">
                                    <option value="0">无</option>
                                    @foreach($agent_list as $v)
                                        <option value="{{ $v['id'] }}">{{ $v['username'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-xs-3 control-label">状态</label>
                            <div class="col-xs-9">

                                <select class="form-control" name="status">
                                    <option value="1">启用</option>
                                    <option value="0">禁用</option>
                                    <option value="2">删除</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="groupType" value="1">
                            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                            <button type="button" class="btn btn-primary" onclick="save($(this))">提交</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{--余额--}}
    <div class="modal fade" id="balanceModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
         data-backdrop="static">
        <div class="modal-dialog" style="margin-top: 123px">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title quota_modal-title"></h4>
                </div>
                <div class="modal-body" style="overflow: auto;">
                    <form id="balanceForm" action="{{ route('users.balance') }}" class="form-horizontal" role="form">
                        <input type="hidden" name="uid">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="" class="col-xs-3 control-label">金额</label>
                            <div class="col-xs-9">
                                <input type="text" class="form-control" id="amount" name="amount" placeholder="金额">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-xs-3 control-label">修改类型</label>
                            <div class="col-xs-9">
                                <select class="form-control" name="balance_type">
                                    <option value="0">增加</option>
                                    <option value="1">减少</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                            <button type="button" class="btn btn-primary" onclick="balancsave($(this))">提交</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection('content')
@section("scripts")
    <script src="{{ asset('plugins/bootstrap-switch/bootstrap-switch.min.js') }}"></script>
    <script>
        $(function () {
            formValidator();

            // 状态修改
            $('.switch-state').bootstrapSwitch({
                onText: '启用',
                offText: '禁用',
                onColor: "primary",
                offColor: "danger",
                size: "small",
                onSwitchChange: function (event, state) {
                    var id = $(event.currentTarget).data('id');
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('users.saveStatus') }}",
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
                $("#usersForm").data('bootstrapValidator').destroy();
                $('#usersForm').data('bootstrapValidator', null);
                $('#usersForm').get(0).reset();
                $("input[name='id']").val('');
                formValidator();
            });

            // 模态关闭
            $('#balanceModel').on('hidden.bs.modal', function () {
                $('#balanceForm').get(0).reset();
                $("input[name='uid']").val('');
            });

        })

        /**
         * 余额加减
         */
        function editBlance(title,id) {
            $("input[name='uid']").val(id);
            $('.quota_modal-title').html(title);
            $('#balanceModel').modal('show');
        }

        /**
         * 余额修改提交
         */
        function balancsave(_this) {

            swal({
                title: "您确定要改为成功吗？",
                text: "修改后不能恢复！",
                type: "warning",
                showCancelButton: true,
                closeOnConfirm: false,
                showLoaderOnConfirm: true,
            }, function(){
                var $form = $('#balanceForm');
                _this.removeAttr('onclick');
                $.post($form.attr('action'), $form.serialize(), function (result) {
                    if (result.status) {
                        $('#balanceModel').modal('hide');
                        setInterval(function () {
                            window.location.reload();
                        }, 1000);

                        toastr.success(result.msg);
                    } else {
                        $('#balanceModel').modal('hide');
                        _this.attr("onclick", "balancsave($(this))");
                        toastr.error(result.msg);
                    }
                }, 'json');
            });
        }

        /**
         * 提交
         */
        function save(_this) {
            // formValidator();
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
                                url: "{{ route('users.check') }}",
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
                                url: "{{ route('users.check') }}",
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
                                url: "{{ route('users.check') }}",
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

        /**
         * 显示模态框
         */
        function showModel(title) {
            $('#addModel .modal-title').html(title);
            $('#addModel').modal('show');
        }

        /**
         * 编辑
         * @param id
         * @param title
         */
        function edit(title, id) {
            $.ajax({
                type: 'get',
                url: '/admin/users/' + id + '/edit',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (result) {
                    if (result.status == 1) {
                        $("#username").val(result.data['username']);
                        $("input[name='phone']").val(result.data['phone']);
                        $("input[name='email']").val(result.data['email']);
                        $("select[name='status']").val(result.data['status']);
                        $("select[name='parentId']").val(result.data['parentId']);
                        $("input[name='id']").val(result.data['id']);
                        $("input[name='password']").val(result.data['password']);
                        $("input[name='password_confirmation']").val(result.data['password']);
                        $('.modal-title').html(title);
                        $('#addModel').modal('show');
                    }
                },
                error: function (XMLHttpRequest, textStatus) {
                    toastr.error('通信失败');
                }
            })
        }

        /**
         * 删除
         * @param _this
         */
        function del(_this, id) {
            swal({
                title: "您确定要删除吗？",
                text: "删除后不能恢复！",
                type: "warning",
                showCancelButton: true,
                closeOnConfirm: false,
                showLoaderOnConfirm: true,
            }, function () {
                $.ajax({
                    type: 'delete',
                    url: '/admin/users',
                    data: {'id': id},
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (result) {
                        if (result.status) {
                            _this.parents('tr').empty();
                            swal(result.msg, "会员已被删除。", "success")
                        } else {
                            swal(result.msg, "会员没有被删除。", "error")
                        }

                    },
                    error: function (XMLHttpRequest, textStatus) {
                        toastr.error('通信失败');
                    }
                })
            });
        }
    </script>
@endsection



