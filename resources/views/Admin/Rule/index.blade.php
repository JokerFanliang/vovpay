@extends("Admin.Commons.layout")    @section('title',$title)

@section("css")
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-switch/bootstrap-switch.min.css') }}">
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <button type="button" class="btn btn-primary" onclick="showModel('添加菜单')">添加菜单</button>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table id="example2" class="table table-condensed table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>菜单名称</th>
                        <th>菜单路由</th>
                        <th>菜单动作</th>
                        <th>是否验证</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($list as $v)
                        <tr>
                            <td>{{ $v['id'] }}</td>
                            <td>{{ $v['ltitle'] }}</td>
                            <td>{{ $v['uri'] }}</td>
                            <td>{{ $v['rule'] }}</td>
                            <td>
                                <input class="switch-state" data-id="{{ $v['id'] }}" type="checkbox" @if($v['is_check'] == 1) checked @endif >
                            </td>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm" onclick="edit('菜单编辑',{{ $v['id'] }})">编辑</button>
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
                <form id="ruleForm" action="{{ route('rules.store') }}" class="form-horizontal" role="form">
                    <input type="hidden" name="id">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="" class="col-xs-3 control-label">父级菜单</label>
                        <div class="col-xs-9">
                            <select class="form-control selectpicker" name="pid">
                                <option value="0">ROOT</option>
                                @foreach($list as $v)
                                    <option value="{{ $v['id'] }}">{{ $v['ltitle'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-xs-3 control-label">菜单名称</label>
                        <div class="col-xs-9">
                            <input type="text" class="form-control" name="title" placeholder="菜单名称">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-xs-3 control-label">菜单路由</label>
                        <div class="col-xs-9">
                            <input type="text" class="form-control" name="uri" placeholder="菜单路由">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-xs-3 control-label">菜单动作</label>
                        <div class="col-xs-9">
                            <input type="text" class="form-control" name="rule" placeholder="菜单动作">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-xs-3 control-label">菜单图标</label>
                        <div class="col-xs-9">
                            <input type="text" class="form-control" name="icon" placeholder="菜单图标">
                            <span class="help-block" style="font-size: 12px;">
                                <i class="fa fa-info-circle"></i>图标地址
                                <a href="http://fontawesome.io/icons/" target="_blank">http://fontawesome.io/icons</a>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-xs-3 control-label">排序</label>
                        <div class="col-xs-9">
                            <input type="text" class="form-control" value="0" name="sort" placeholder="排序">
                            <span class="help-block" style="font-size: 12px;"><i class="fa fa-info-circle"></i>值越大排名越靠前</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-xs-3 control-label">是否验证</label>
                        <div class="col-xs-9">

                            <select class="form-control" name="is_check">
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
                onText:'是',
                offText:'否' ,
                onColor:"primary",
                offColor:"danger",
                size:"small",
                onSwitchChange:function(event,state) {
                    var id =  $(event.currentTarget).data('id');
                    $.ajax({
                        type: 'POST',
                        url: '/admin/rules/saveCheck',
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
                $("#ruleForm").data('bootstrapValidator').destroy();
                $('#ruleForm').data('bootstrapValidator', null);
                $('#ruleForm').get(0).reset();
                formValidator();
            });

        })

        /**
         * 提交
         */
        function save(_this){
            //开启验证
            $('#ruleForm').data('bootstrapValidator').validate();
            if(!$('#ruleForm').data('bootstrapValidator').isValid()){
                return ;
            }
            _this.removeAttr('onclick');

            var $form = $('#ruleForm');
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
            $('#ruleForm').bootstrapValidator({
                message: '输入值不合法',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    title: {
                        message: '菜单名称不合法',
                        validators: {
                            notEmpty: {
                                message: '菜单名称不能为空！'
                            },
                        }
                    },
                    uri: {
                        validators: {
                            notEmpty: {
                                message: '菜单路由不能为空!'
                            }
                        }
                    },
                    sort: {
                        validators:{
                            notEmpty: {
                                message: '排序不能为空!'
                            },
                            regexp: { //正则校验
                                regexp: /^[0-9]+$/,
                                message:'排序只能是数字'
                            },
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
                url: '/admin/rules/'+id+'/edit',
                dataType:'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(result){
                    if(result.status == 1)
                    {
                        $("input[name='title']").val(result.data['title']);
                        $("input[name='uri']").val(result.data['uri']);
                        $("input[name='rule']").val(result.data['rule']);
                        $("input[name='icon']").val(result.data['icon']);
                        $("input[name='sort']").val(result.data['sort']);
                        $("select[name='is_check']").val(result.data['is_check']);
                        $("select[name='pid']").val(result.data['pid']);
                        $("input[name='id']").val(result.data['id']);
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
                    url: '/admin/rules',
                    data:{'id':id},
                    dataType:'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success:function(result){
                        if(result.status)
                        {
                            _this.parents('tr').empty();
                            swal(result.msg, "菜单已被删除。","success")
                        }else{
                            swal(result.msg, "菜单没有被删除。","error")
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



