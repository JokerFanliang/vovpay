@extends("Admin.Commons.layout")    @section('title',$title)
@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <button type="button" class="btn btn-primary" onclick="showModel('添加角色')">添加角色</button>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table id="example2" class="table table-condensed table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>角色名称</th>
                        <th>权限分配</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($list as $v)
                        <tr>
                            <td>{{ $v['id'] }}</td>
                            <td>{{ $v['name'] }}</td>
                            <td>
                                <a href="{{ route('getRules',array('id'=>$v['id'])) }}" class="btn btn-primary btn-sm">权限编辑</a>
                            </td>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm" onclick="edit('角色编辑',{{ $v['id'] }})">编辑</button>
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
                <form id="ruleForm" action="{{ route('roles.store') }}" class="form-horizontal" role="form">
                    <input type="hidden" name="id">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="" class="col-xs-3 control-label">角色名称</label>
                        <div class="col-xs-9">
                            <input type="text" class="form-control" name="name" placeholder="角色名称">
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
    <script>
        $(function () {
            formValidator();
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
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    name: {
                        validators: {
                            notEmpty: {
                                message: '角色名不能为空！'
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
                url: '/admin/roles/'+id+'/edit',
                dataType:'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(result){
                    if(result.status == 1)
                    {
                        $("input[name='id']").val(result.data['id']);
                        $("input[name='name']").val(result.data['name']);
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
                    url: '/admin/roles',
                    data:{'id':id},
                    dataType:'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success:function(result){
                        if(result.status)
                        {
                            _this.parents('tr').empty();
                            swal(result.msg, "角色已被删除。","success")
                        }else{
                            swal(result.msg, "角色没有被删除。","error")
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



