@extends("Admin.Commons.layout")    @section('title',$title)


@section("css")
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-switch/bootstrap-switch.min.css') }}">
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <button type="button" class="btn btn-primary" onclick="showModel('添加管理员')">添加管理员</button>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table id="example2" class="table table-condensed table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>用户名</th>
                        <th>用户角色</th>
                        <th>邮箱</th>
                        <th>电话</th>
                        <th>状态</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($list as $v)
                        <tr>
                            <td>{{ $v['id'] }}</td>
                            <td>{{ $v['username'] }}</td>
                            <td><span class="label label-success">{{ $v->roles()->pluck('name')[0] }}</span></td>
                            <td>{{ $v['email'] }}</td>
                            <td>{{ $v['phone'] }}</td>
                            <td>
                                <input class="switch-state" data-id="{{ $v['id'] }}" type="checkbox" @if($v['status'] == 1) checked @endif >
                            </td>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm" onclick="edit('管理员编辑',{{ $v['id'] }})">编辑</button>
                                <button type="button" class="btn btn-danger btn-sm" onclick="del($(this),{{ $v['id'] }})">删除</button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->
<div class="modal fade" id="addModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" style="margin-top: 123px">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body" style="overflow: auto;">
                <form id="adminsForm" action="{{ route('admins.store') }}" class="form-horizontal" role="form">
                    <input type="hidden" name="id">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="" class="col-xs-3 control-label">用户名</label>
                        <div class="col-xs-9">
                            <input type="text" class="form-control" name="username" placeholder="用户名">
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
                            <input type="password" class="form-control" name="password_confirmation" placeholder="确认密码">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-xs-3 control-label">邮箱</label>
                        <div class="col-xs-9">
                            <input type="email" class="form-control" name="email" placeholder="邮箱">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-xs-3 control-label">电话</label>
                        <div class="col-xs-9">
                            <input type="text" class="form-control" name="phone" placeholder="电话">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-xs-3 control-label">角色</label>
                        <div class="col-xs-9">
                            <select class="form-control selectpicker" name="role_id">
                                @foreach($role_list as $v)
                                    <option value="{{ $v['id'] }}">{{ $v['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-xs-3 control-label">状态</label>
                        <div class="col-xs-9">

                            <select class="form-control" name="status">
                                <option value="1">是</option>
                                <option value="0">否</option>
                            </select>
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
@endsection('content')
@section("scripts")
    <script src="{{ asset('plugins/bootstrap-switch/bootstrap-switch.min.js') }}"></script>
    <script>
        $(function () {
            formValidator();

            // 状态修改
            $('.switch-state').bootstrapSwitch({
                onText:'启用',
                offText:'禁用' ,
                onColor:"primary",
                offColor:"danger",
                size:"small",
                onSwitchChange:function(event,state) {
                    var id =  $(event.currentTarget).data('id');
                    $.ajax({
                        type: 'POST',
                        url: '/admin/admins/saveStatus',
                        data:{'status':state,'id':id},
                        dataType:'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success:function(result){
                            if(result.status)
                            {
                                toastr.success(result.msg);
                            }else{
                                $('#addModel').modal('hide');
                                toastr.error(result.msg);
                            }
                        },
                        error:function(XMLHttpRequest,textStatus){
                            toastr.error('通信失败');
                        }
                    })
                }
            })

            // 模态关闭
            $('#addModel').on('hidden.bs.modal', function() {
                $("#adminsForm").data('bootstrapValidator').destroy();
                $('#adminsForm').data('bootstrapValidator', null);
                $('#adminsForm').get(0).reset();
                formValidator();
            });

        })

        /**
         * 提交
         */
        function save(_this){
            //开启验证
            $('#adminsForm').data('bootstrapValidator').validate();
            if(!$('#adminsForm').data('bootstrapValidator').isValid()){
                return ;
            }
            _this.removeAttr('onclick');

            var $form = $('#adminsForm');
            $.post($form.attr('action'), $form.serialize(), function(result) {
                if(result.status)
                {
                    $('#addModel').modal('hide');
                    setInterval(function(){
                        window.location.reload();
                    },1000);

                    toastr.success(result.msg);
                }else{
                    $('#addModel').modal('hide');
                    _this.attr("onclick","save($(this))");
                    toastr.error(result.msg);
                }
            }, 'json');

        }

        /**
         * 表单验证
         */
        function formValidator()
        {
            $('#adminsForm').bootstrapValidator({
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
                                message:'只能使用数字和字母!'
                            },
                            remote: {
                                url: "admins/check",
                                message: "用户名已存在!",
                                type: "post",
                                data: function(){ // 额外的数据，默认为当前校验字段,不需要的话去掉即可
                                    return {
                                        "value" : $("input[name='username']").val().trim(),
                                        "type"  : 'username',
                                        "_token": $('meta[name="csrf-token"]').attr('content'),
                                        "id"    : $('input[name="id"]').val()
                                    };
                                }
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
                        validators:{
                            notEmpty: {
                                message: '邮箱不能为空!'
                            },
                            emailAddress: { // 可以不用自己写正则
                                message: '邮箱格式不正确!'
                            },
                            remote: {
                                url: "admins/check",
                                message: "邮箱已存在!",
                                type: "post",
                                data: function(){ // 额外的数据，默认为当前校验字段,不需要的话去掉即可
                                    return {
                                        "value" : $("input[name='email']").val().trim(),
                                        "type"  : "email",
                                        "_token": $('meta[name="csrf-token"]').attr('content'),
                                        "id"    : $('input[name="id"]').val()
                                    };
                                }
                            }
                        }
                    },
                    phone: {
                        validators:{
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
                                message:'电话格式不正确!'
                            },
                            remote: {
                                url: "admins/check",
                                message: "电话已存在!",
                                type: "post",
                                data: function(){ // 额外的数据，默认为当前校验字段,不需要的话去掉即可
                                    return {
                                        "value" : $("input[name='phone']").val().trim(),
                                        "type"  : 'phone',
                                        "_token": $('meta[name="csrf-token"]').attr('content'),
                                        "id"    : $('input[name="id"]').val()
                                    };
                                }
                            }
                        }
                    }
                }
            });
        }

        /**
         * 显示模态框
         */
        function showModel(title)
        {
            $('.modal-title').html(title);
            $('#addModel').modal('show');
        }

        /**
         * 编辑
         * @param id
         * @param title
         */
        function edit(title, id)
        {
            $.ajax({
                type: 'get',
                url: '/admin/admins/'+id+'/edit',
                dataType:'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(result){
                    if(result.status == 1)
                    {
                        $("input[name='username']").val(result.data['username']);
                        $("input[name='phone']").val(result.data['phone']);
                        $("input[name='email']").val(result.data['email']);
                        $("select[name='status']").val(result.data['status']);
                        $("select[name='role_id']").val(result.data['role_id']);
                        $("input[name='id']").val(result.data['id']);
                        $("input[name='password']").val(result.data['password']);
                        $("input[name='password_confirmation']").val(result.data['password']);
                        $('.modal-title').html(title);
                        $('#addModel').modal('show');
                    }
                },
                error:function(XMLHttpRequest,textStatus){
                    toastr.error('通信失败');
                }
            })
        }

        /**
         * 删除
         * @param _this
         */
        function del(_this,id){
            swal({
                title: "您确定要删除吗？",
                text: "删除后不能恢复！",
                type: "warning",
                showCancelButton: true,
                closeOnConfirm: false,
                showLoaderOnConfirm: true,
            }, function(){
                $.ajax({
                    type: 'delete',
                    url: '/admin/admins',
                    data:{'id':id},
                    dataType:'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success:function(result){
                        if(result.status)
                        {
                            _this.parents('tr').empty();
                            swal(result.msg, "管理员已被删除。","success")
                        }else{
                            swal(result.msg, "管理员没有被删除。","error")
                        }

                    },
                    error:function(XMLHttpRequest,textStatus){
                        toastr.error('通信失败');
                    }
                })
            });
        }
    </script>
@endsection



