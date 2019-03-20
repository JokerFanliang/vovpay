@extends("Admin.Commons.layout")    @section('title',$title)

@section("css")
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-switch/bootstrap-switch.min.css') }}">
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <button type="button" class="btn btn-primary" onclick="showModel('添加通道')">添加通道</button>
                <a href="{{ route('channels.index') }}" class="btn pull-right"><i class="fa fa-undo"></i>刷新</a>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table id="example2" class="table table-condensed table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>通道名称</th>
                        <th>通道编码</th>
                        <th>接口状态</th>
                        <th>通道限额</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($list as $v)
                        <tr>
                            <td>{{ $v['channelName'] }}</td>
                            <td>{{ $v['channelCode'] }}</td>
                            <td><input class="switch-state" data-id="{{ $v['id'] }}" type="checkbox" @if($v['status'] == 1) checked @endif ></td>
                            <td><span style="color: #999999;font-weight: bold">{{ $v['channelQuota'] }}</span></td>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm" onclick="edit('通道编辑',{{ $v['id'] }})">编辑</button>
                                <button type="button" class="btn btn-danger btn-sm" onclick="del($(this),{{ $v['id'] }})">删除</button>
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

<div class="modal fade" id="addModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" style="margin-top: 123px">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body" style="overflow: auto;">
                <form id="adminsForm" action="{{ route('channels.store') }}" class="form-horizontal" role="form">
                    <input type="hidden" name="id">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="" class="col-xs-3 control-label">通道名称</label>
                        <div class="col-xs-9">
                            <input type="text" class="form-control" name="channelName" placeholder="通道名称">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-xs-3 control-label">通道编码</label>
                        <div class="col-xs-9">
                            <input type="text" class="form-control" name="channelCode" placeholder="通道编码">
                            <span class="help-block" style="font-size: 12px;"><i class="fa fa-info-circle"></i>只能是字母,且唯一</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-xs-3 control-label">上游商户号</label>
                        <div class="col-xs-9">
                            <input type="text" class="form-control" name="merchant" placeholder="上游商户号">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-xs-3 control-label">上游密钥</label>
                        <div class="col-xs-9">
                            <input type="text" class="form-control" name="signkey" placeholder="上游密钥">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-xs-3 control-label">通道限额</label>
                        <div class="col-xs-9">
                            <input type="text" class="form-control" value="0" name="channelQuota" placeholder="通道限额">
                            <span class="help-block" style="font-size: 12px;"><i class="fa fa-info-circle"></i>0不限额</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-xs-3 control-label">扩展</label>
                        <div class="col-xs-9">
                            <textarea class="form-control" name="extend" id="" cols="30" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-xs-3 control-label">状态</label>
                        <div class="col-xs-9">

                            <select class="form-control" name="status">
                                <option value="1">启用</option>
                                <option value="0">禁用</option>
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
                        url: '/admin/channels/saveStatus',
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

            // 结算方式修改
            $('.switch-planType').bootstrapSwitch({
                onText:'T+1',
                offText:'T+0' ,
                onColor:"primary",
                offColor:"default",
                size:"small",
                onSwitchChange:function(event,state) {
                    var id =  $(event.currentTarget).data('id');
                    $.ajax({
                        type: 'POST',
                        url: '/admin/channels/savePlanType',
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
                    channelName: {
                        validators: {
                            notEmpty: {
                                message: '通道名称不能为空!'
                            },
                            stringLength: {
                                max: 30,
                                message: '通道名称最大长度%s个字符!'
                            }
                        }
                    },
                    channelCode: {
                        validators: {
                            notEmpty: {
                                message: '通道编码不能为空!'
                            },
                            stringLength: {
                                max: 20,
                                message: '密码最大长度%s个字符!'
                            },
                            regexp: { //正则校验
                                regexp: /^[A-Za-z]+$/,
                                message:'通道编码格式不正确!'
                            },
                        }
                    },
                    channelQuota: {
                        validators:{
                            notEmpty: {
                                message: '限额不能为空!'
                            },
                            regexp: { //正则校验
                                regexp: /^[0-9]+$/,
                                message:'限额只能是整数!'
                            },
                        }
                    },
                    runRate: {
                        validators:{
                            notEmpty: {
                                message: '运营费率不能为空!'
                            },
                            regexp: { //正则校验
                                regexp: /^[0-9]+([.]{1}[0-9]+){0,1}$/,
                                    message:'费率只能是整数或小数!'
                            },
                        }
                    },
                    costRate: {
                        validators:{
                            notEmpty: {
                                message: '成本费率不能为空!'
                            },
                            regexp: { //正则校验
                                regexp: /^[0-9]+([.]{1}[0-9]+){0,1}$/,
                                message:'费率只能是整数或小数!'
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
                url: '/admin/channels/'+id+'/edit',
                dataType:'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(result){
                    if(result.status == 1)
                    {
                        $("input[name='channelName']").val(result.data['channelName']);
                        $("input[name='merchant']").val(result.data['merchant']);
                        $("select[name='status']").val(result.data['status']);
                        $("input[name='id']").val(result.data['id']);
                        $("input[name='signkey']").val(result.data['signkey']);
                        $("input[name='channelCode']").val(result.data['channelCode']);
                        $("input[name='runRate']").val(result.data['runRate']);
                        $("input[name='costRate']").val(result.data['costRate']);
                        $("input[name='refererDomain']").val(result.data['refererDomain']);
                        $("input[name='channelQuota']").val(result.data['channelQuota']);
                        $("textarea[name='extend']").val(result.data['extend']);
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
                    url: '/admin/channels',
                    data:{'id':id},
                    dataType:'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success:function(result){
                        if(result.status)
                        {
                            _this.parents('tr').empty();
                            swal(result.msg, "会员已被删除。","success")
                        }else{
                            swal(result.msg, "会员没有被删除。","error")
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



